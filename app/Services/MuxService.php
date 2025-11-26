<?php

namespace App\Services;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuxService
{
    protected string $tokenId;
    protected string $tokenSecret;
    protected string $baseUrl = 'https://api.mux.com';

    public function __construct()
    {
        $this->tokenId = config('services.mux.token_id');
        $this->tokenSecret = config('services.mux.token_secret');
    }

    /**
     * Create a direct upload URL for video recording
     * This allows client-side uploads directly to Mux
     */
    public function createDirectUpload(array $options = []): ?array
    {
        // Check if credentials are configured
        if (empty($this->tokenId) || empty($this->tokenSecret)) {
            Log::error('Mux credentials not configured', [
                'token_id_set' => !empty($this->tokenId),
                'token_secret_set' => !empty($this->tokenSecret),
            ]);
            return null;
        }

        try {
            $corsOrigin = $options['cors_origin'] ?? '*';

            Log::info('Mux API request', [
                'cors_origin' => $corsOrigin,
                'token_id' => substr($this->tokenId, 0, 8) . '...',
            ]);

            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->timeout(30)
                ->withOptions([
                    'verify' => false, // Disable SSL verification for local development
                ])
                ->post("{$this->baseUrl}/video/v1/uploads", [
                    'cors_origin' => $corsOrigin,
                    'new_asset_settings' => [
                        'playback_policy' => ['public'],
                        'encoding_tier' => 'baseline',
                        'max_resolution_tier' => '1080p',
                    ],
                    'timeout' => 3600,
                ]);

            Log::info('Mux API response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                return [
                    'upload_id' => $data['id'],
                    'upload_url' => $data['url'],
                    'status' => $data['status'],
                ];
            }

            Log::error('Mux create upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Mux create upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Get upload status
     */
    public function getUploadStatus(string $uploadId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/video/v1/uploads/{$uploadId}");

            if ($response->successful()) {
                return $response->json()['data'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Mux get upload status exception', [
                'upload_id' => $uploadId,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get asset by ID
     */
    public function getAsset(string $assetId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/video/v1/assets/{$assetId}");

            if ($response->successful()) {
                return $response->json()['data'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Mux get asset exception', [
                'asset_id' => $assetId,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Delete an asset
     */
    public function deleteAsset(string $assetId): bool
    {
        try {
            $response = Http::withBasicAuth($this->tokenId, $this->tokenSecret)
                ->withOptions(['verify' => false])
                ->delete("{$this->baseUrl}/video/v1/assets/{$assetId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Mux delete asset exception', [
                'asset_id' => $assetId,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update testimonial with Mux data from webhook or polling
     */
    public function updateTestimonialFromUpload(Testimonial $testimonial): bool
    {
        if (!$testimonial->mux_upload_id) {
            return false;
        }

        $uploadData = $this->getUploadStatus($testimonial->mux_upload_id);

        if (!$uploadData) {
            return false;
        }

        $updates = [
            'mux_status' => $this->mapMuxStatus($uploadData['status']),
        ];

        // If upload is complete, get the asset ID
        if ($uploadData['status'] === 'asset_created' && isset($uploadData['asset_id'])) {
            $updates['mux_asset_id'] = $uploadData['asset_id'];

            // Get asset details for playback ID
            $assetData = $this->getAsset($uploadData['asset_id']);
            if ($assetData) {
                if (isset($assetData['playback_ids'][0]['id'])) {
                    $updates['mux_playback_id'] = $assetData['playback_ids'][0]['id'];
                }
                if (isset($assetData['duration'])) {
                    $updates['video_duration'] = (int) $assetData['duration'];
                }
                if ($assetData['status'] === 'ready') {
                    $updates['mux_status'] = 'ready';
                }
                $updates['mux_data'] = $assetData;
            }
        }

        $testimonial->update($updates);

        return true;
    }

    /**
     * Map Mux status to our internal status
     */
    protected function mapMuxStatus(string $muxStatus): string
    {
        return match ($muxStatus) {
            'waiting' => 'waiting',
            'asset_created' => 'processing',
            'ready' => 'ready',
            'errored' => 'error',
            default => 'processing',
        };
    }

    /**
     * Handle webhook from Mux
     */
    public function handleWebhook(array $payload): bool
    {
        $type = $payload['type'] ?? null;
        $data = $payload['data'] ?? [];

        switch ($type) {
            case 'video.upload.asset_created':
                return $this->handleUploadAssetCreated($data);

            case 'video.asset.ready':
                return $this->handleAssetReady($data);

            case 'video.asset.errored':
                return $this->handleAssetError($data);

            default:
                return true;
        }
    }

    protected function handleUploadAssetCreated(array $data): bool
    {
        $uploadId = $data['id'] ?? null;
        if (!$uploadId) {
            return false;
        }

        $testimonial = Testimonial::where('mux_upload_id', $uploadId)->first();
        if (!$testimonial) {
            return false;
        }

        $testimonial->update([
            'mux_asset_id' => $data['asset_id'] ?? null,
            'mux_status' => 'processing',
        ]);

        return true;
    }

    protected function handleAssetReady(array $data): bool
    {
        $assetId = $data['id'] ?? null;
        if (!$assetId) {
            return false;
        }

        $testimonial = Testimonial::where('mux_asset_id', $assetId)->first();
        if (!$testimonial) {
            return false;
        }

        $updates = [
            'mux_status' => 'ready',
            'mux_data' => $data,
        ];

        if (isset($data['playback_ids'][0]['id'])) {
            $updates['mux_playback_id'] = $data['playback_ids'][0]['id'];
        }

        if (isset($data['duration'])) {
            $updates['video_duration'] = (int) $data['duration'];
        }

        $testimonial->update($updates);

        return true;
    }

    protected function handleAssetError(array $data): bool
    {
        $assetId = $data['id'] ?? null;
        if (!$assetId) {
            return false;
        }

        $testimonial = Testimonial::where('mux_asset_id', $assetId)->first();
        if (!$testimonial) {
            return false;
        }

        $testimonial->update([
            'mux_status' => 'error',
            'mux_data' => $data,
        ]);

        return true;
    }
}
