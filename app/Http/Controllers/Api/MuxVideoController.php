<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialTemplate;
use App\Services\MuxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MuxVideoController extends Controller
{
    protected MuxService $muxService;

    public function __construct(MuxService $muxService)
    {
        $this->muxService = $muxService;
    }

    /**
     * Create a direct upload URL for video recording
     */
    public function createUpload(Request $request): JsonResponse
    {
        $request->validate([
            'template_id' => 'nullable|exists:testimonial_templates,id',
        ]);

        // Get origin - allow multiple possible origins for development
        $origin = $request->header('Origin');
        if (!$origin) {
            $origin = $request->header('Referer');
            if ($origin) {
                $parsed = parse_url($origin);
                $origin = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? 'localhost') . (isset($parsed['port']) ? ':' . $parsed['port'] : '');
            }
        }

        // Fallback to wildcard for development
        $corsOrigin = $origin ?: '*';

        Log::info('Mux upload request', [
            'origin' => $origin,
            'cors_origin' => $corsOrigin,
        ]);

        $uploadData = $this->muxService->createDirectUpload([
            'cors_origin' => $corsOrigin,
        ]);

        if (!$uploadData) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create upload URL. Check Mux API credentials.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $uploadData,
        ]);
    }

    /**
     * Get upload status
     */
    public function getUploadStatus(Request $request, string $uploadId): JsonResponse
    {
        $uploadData = $this->muxService->getUploadStatus($uploadId);

        if (!$uploadData) {
            return response()->json([
                'success' => false,
                'message' => 'Upload not found',
            ], 404);
        }

        $response = [
            'success' => true,
            'data' => [
                'status' => $uploadData['status'],
                'asset_id' => $uploadData['asset_id'] ?? null,
            ],
        ];

        // If asset is created, get playback info
        if (isset($uploadData['asset_id'])) {
            $assetData = $this->muxService->getAsset($uploadData['asset_id']);
            if ($assetData) {
                $response['data']['asset_status'] = $assetData['status'];
                if (isset($assetData['playback_ids'][0]['id'])) {
                    $response['data']['playback_id'] = $assetData['playback_ids'][0]['id'];
                }
                if (isset($assetData['duration'])) {
                    $response['data']['duration'] = $assetData['duration'];
                }
            }
        }

        return response()->json($response);
    }

    /**
     * Handle Mux webhooks
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        // Verify webhook signature
        $signature = $request->header('Mux-Signature');
        $webhookSecret = config('services.mux.webhook_secret');

        if ($webhookSecret && $signature) {
            // Basic signature verification (in production, implement full HMAC verification)
            // Parse signature header (format: t=timestamp,v1=signature)
            $parts = [];
            foreach (explode(',', $signature) as $part) {
                [$key, $value] = explode('=', $part, 2);
                $parts[$key] = $value;
            }

            if (isset($parts['t']) && isset($parts['v1'])) {
                $timestamp = $parts['t'];
                $expectedSignature = $parts['v1'];
                $payload = $timestamp . '.' . $request->getContent();
                $computedSignature = hash_hmac('sha256', $payload, $webhookSecret);

                if (!hash_equals($computedSignature, $expectedSignature)) {
                    Log::warning('Mux webhook signature verification failed');
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
            }
        }

        $payload = $request->all();

        Log::info('Mux webhook received', ['type' => $payload['type'] ?? 'unknown']);

        $this->muxService->handleWebhook($payload);

        return response()->json(['success' => true]);
    }

    /**
     * Poll for testimonial video status
     */
    public function getTestimonialVideoStatus(Request $request, Testimonial $testimonial): JsonResponse
    {
        if (!$testimonial->mux_upload_id) {
            return response()->json([
                'success' => false,
                'message' => 'No video upload found',
            ], 404);
        }

        // Refresh status from Mux
        $this->muxService->updateTestimonialFromUpload($testimonial);
        $testimonial->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $testimonial->mux_status,
                'playback_id' => $testimonial->mux_playback_id,
                'duration' => $testimonial->video_duration,
                'thumbnail_url' => $testimonial->getMuxThumbnailUrl(),
                'stream_url' => $testimonial->getMuxStreamUrl(),
            ],
        ]);
    }
}
