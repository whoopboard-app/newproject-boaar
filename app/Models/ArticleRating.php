<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'rateable_type',
        'rateable_id',
        'rating_value',
        'rating_type',
        'comment',
        'voter_ip',
        'voter_fingerprint',
    ];

    /**
     * Get the team that owns the rating.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all ratings for a specific changelog.
     */
    public static function forChangelog($changelogId, $teamId = null)
    {
        $query = self::where('rateable_type', 'changelog')
            ->where('rateable_id', $changelogId);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get all ratings for a specific article.
     */
    public static function forArticle($articleId, $teamId = null)
    {
        $query = self::where('rateable_type', 'article')
            ->where('rateable_id', $articleId);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get average rating for a rateable item.
     */
    public static function getAverageRating($rateableType, $rateableId, $teamId = null)
    {
        $query = self::where('rateable_type', $rateableType)
            ->where('rateable_id', $rateableId);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $ratings = $query->get();

        if ($ratings->isEmpty()) {
            return null;
        }

        // Get the rating type from the first rating
        $ratingType = $ratings->first()->rating_type;

        // Calculate based on rating type
        switch ($ratingType) {
            case 'yes_no':
                $yesCount = $ratings->where('rating_value', 'yes')->count();
                $totalCount = $ratings->count();
                return [
                    'type' => 'yes_no',
                    'yes_count' => $yesCount,
                    'no_count' => $totalCount - $yesCount,
                    'total' => $totalCount,
                    'percentage' => $totalCount > 0 ? round(($yesCount / $totalCount) * 100) : 0,
                ];

            case 'emoji':
            case 'star':
                $numericValues = $ratings->map(function ($rating) {
                    return (int) $rating->rating_value;
                });
                $average = $numericValues->avg();
                return [
                    'type' => $ratingType,
                    'average' => round($average, 1),
                    'total' => $ratings->count(),
                    'max' => 5,
                ];

            case 'numeric':
                $numericValues = $ratings->map(function ($rating) {
                    return (int) $rating->rating_value;
                });
                $average = $numericValues->avg();
                return [
                    'type' => 'numeric',
                    'average' => round($average, 1),
                    'total' => $ratings->count(),
                    'max' => 10,
                ];

            case 'comment_only':
                return [
                    'type' => 'comment_only',
                    'total' => $ratings->count(),
                ];

            default:
                return null;
        }
    }

    /**
     * Get rating statistics grouped by value.
     */
    public static function getRatingStats($rateableType, $rateableId, $teamId = null)
    {
        $query = self::where('rateable_type', $rateableType)
            ->where('rateable_id', $rateableId);

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->selectRaw('rating_value, COUNT(*) as count')
            ->groupBy('rating_value')
            ->pluck('count', 'rating_value')
            ->toArray();
    }
}
