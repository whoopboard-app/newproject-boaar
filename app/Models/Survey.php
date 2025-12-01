<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory, BelongsToTeam;

    /**
     * Survey types
     */
    const TYPE_NPS = 'nps';
    const TYPE_CSAT = 'csat';
    const TYPE_OPEN_FEEDBACK = 'open_feedback';
    const TYPE_QUICK_POLL = 'quick_poll';
    const TYPE_IDEA_POOL = 'idea_pool';

    /**
     * Survey statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_PAUSED = 'paused';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'title',
        'type',
        'status',
        'general_settings',
        'trigger_type',
        'trigger_delay_seconds',
        'show_survey_timing',
        'show_after_value',
        'show_after_unit',
        'location_type',
        'location_urls',
        'only_identified_users',
        'segment_ids',
        'on_response_action',
        'on_response_show_after_value',
        'on_response_show_after_unit',
        'on_close_action',
        'on_close_show_after_value',
        'on_close_show_after_unit',
        'skip_if_answered_in_session',
        'theme',
        'accent_color',
        'hide_branding',
        'position',
        'label_least_likely',
        'label_most_likely',
        'label_back_button',
        'label_skip_button',
        'label_submit_button',
        'feedback_question',
        'feedback_placeholder',
        'feedback_thank_you',
        'feedback_form_option',
        'show_labels',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'general_settings' => 'array',
        'location_urls' => 'array',
        'segment_ids' => 'array',
        'only_identified_users' => 'boolean',
        'skip_if_answered_in_session' => 'boolean',
        'hide_branding' => 'boolean',
        'show_labels' => 'boolean',
    ];

    /**
     * Check if survey is active (published)
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Get the responses for the survey.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Get default settings for a survey type.
     */
    public static function getDefaultSettings(string $type): array
    {
        $defaults = [
            'branding' => [
                'show_logo' => true,
                'logo_url' => null,
                'primary_color' => '#6366f1',
                'background_color' => '#ffffff',
            ],
            'behavior' => [
                'show_on_load' => false,
                'delay_seconds' => 0,
                'show_once_per_session' => true,
            ],
        ];

        switch ($type) {
            case self::TYPE_NPS:
                return array_merge($defaults, [
                    'question' => 'How likely are you to recommend us to a friend or colleague?',
                    'follow_up_question' => 'What is the main reason for your score?',
                    'thank_you_message' => 'Thank you for your feedback!',
                    'scale' => [
                        'min' => 0,
                        'max' => 10,
                        'min_label' => 'Not at all likely',
                        'max_label' => 'Extremely likely',
                    ],
                ]);

            case self::TYPE_CSAT:
                return array_merge($defaults, [
                    'question' => 'How satisfied are you with our service?',
                    'follow_up_question' => 'How can we improve?',
                    'thank_you_message' => 'Thank you for your feedback!',
                    'scale' => [
                        'min' => 1,
                        'max' => 5,
                        'min_label' => 'Very dissatisfied',
                        'max_label' => 'Very satisfied',
                    ],
                ]);

            case self::TYPE_OPEN_FEEDBACK:
                return array_merge($defaults, [
                    'question' => 'What feedback do you have for us?',
                    'follow_up_question' => '',
                    'thank_you_message' => 'Thank you for your feedback!',
                ]);

            case self::TYPE_QUICK_POLL:
                return array_merge($defaults, [
                    'question' => 'What do you think?',
                    'options' => ['Option 1', 'Option 2', 'Option 3'],
                    'thank_you_message' => 'Thank you for your response!',
                ]);

            case self::TYPE_IDEA_POOL:
            default:
                return array_merge($defaults, [
                    'question' => 'Share your ideas with us',
                    'follow_up_question' => '',
                    'thank_you_message' => 'Thank you for your feedback!',
                ]);
        }
    }

    /**
     * Get all available survey types with their details.
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_NPS => [
                'name' => 'NPS Survey',
                'description' => 'Net Promoter Score - Measure customer loyalty and likelihood to recommend',
                'icon' => 'chart-bar',
            ],
            self::TYPE_CSAT => [
                'name' => 'CSAT Survey',
                'description' => 'Customer Satisfaction - Measure satisfaction with your product or service',
                'icon' => 'mood-happy',
            ],
            self::TYPE_OPEN_FEEDBACK => [
                'name' => 'Open Feedback',
                'description' => 'Collect open-ended feedback from your users',
                'icon' => 'message-dots',
            ],
            self::TYPE_QUICK_POLL => [
                'name' => 'Quick Poll',
                'description' => 'Create quick polls with multiple choice options',
                'icon' => 'list-check',
            ],
            self::TYPE_IDEA_POOL => [
                'name' => 'Idea Pool',
                'description' => 'Collect and manage ideas from your users',
                'icon' => 'bulb',
            ],
        ];
    }

    /**
     * Calculate NPS score from responses.
     */
    public function calculateNpsScore(): ?float
    {
        if ($this->type !== self::TYPE_NPS) {
            return null;
        }

        $responses = $this->responses()->whereNotNull('score')->get();

        if ($responses->isEmpty()) {
            return null;
        }

        $promoters = $responses->where('score', '>=', 9)->count();
        $detractors = $responses->where('score', '<=', 6)->count();
        $total = $responses->count();

        return round((($promoters - $detractors) / $total) * 100, 1);
    }

    /**
     * Get response statistics.
     */
    public function getStatistics(): array
    {
        $responses = $this->responses()->whereNotNull('score')->get();

        if ($responses->isEmpty()) {
            return [
                'total_responses' => 0,
                'average_score' => null,
                'score_distribution' => [],
            ];
        }

        return [
            'total_responses' => $responses->count(),
            'average_score' => round($responses->avg('score'), 1),
            'score_distribution' => $responses->groupBy('score')->map->count()->toArray(),
        ];
    }
}
