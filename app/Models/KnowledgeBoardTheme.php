<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBoardTheme extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'knowledge_board_id',
        'theme_type',
        'is_active',
        'header_background_color',
        'header_text_color',
        'header_intro_text',
        'header_short_description',
        'footer_background_color',
        'footer_text_color',
        'meta_title',
        'meta_description',
        'google_analytics_id',
        'menu_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'menu_order' => 'array',
    ];

    /**
     * Default menu order
     */
    public static function getDefaultMenuOrder(): array
    {
        return [
            ['key' => 'changelog', 'label' => 'Changelog', 'visible' => true, 'order' => 1],
            ['key' => 'feedback', 'label' => 'Feedback', 'visible' => true, 'order' => 2],
            ['key' => 'roadmap', 'label' => 'Roadmap', 'visible' => true, 'order' => 3],
            ['key' => 'testimonial', 'label' => 'Testimonials', 'visible' => true, 'order' => 4],
            ['key' => 'knowledge_board', 'label' => 'Knowledge Board', 'visible' => true, 'order' => 5],
        ];
    }

    /**
     * Default theme values
     */
    public static function getDefaults(): array
    {
        return [
            'theme_type' => 'default',
            'header_background_color' => '#11939A',
            'header_text_color' => '#FFFFFF',
            'header_intro_text' => null,
            'header_short_description' => null,
            'footer_background_color' => '#11939A',
            'footer_text_color' => '#FFFFFF',
            'meta_title' => null,
            'meta_description' => null,
            'google_analytics_id' => null,
            'menu_order' => self::getDefaultMenuOrder(),
        ];
    }

    /**
     * Get menu order (returns saved or default)
     */
    public function getMenuOrder(): array
    {
        return $this->menu_order ?? self::getDefaultMenuOrder();
    }

    /**
     * Get sorted visible menu items
     */
    public function getSortedMenuItems(): array
    {
        $menuOrder = $this->getMenuOrder();

        // Filter visible and sort by order
        $visibleItems = array_filter($menuOrder, fn($item) => $item['visible'] ?? true);
        usort($visibleItems, fn($a, $b) => ($a['order'] ?? 0) - ($b['order'] ?? 0));

        return $visibleItems;
    }

    /**
     * Get theme for a knowledge board (returns active custom theme or default values)
     */
    public static function forKnowledgeBoard($knowledgeBoardId, $teamId)
    {
        $theme = self::where('knowledge_board_id', $knowledgeBoardId)
            ->where('team_id', $teamId)
            ->first();

        // If no theme exists or theme is not active, return default theme object
        if (!$theme || !$theme->is_active) {
            return new self(self::getDefaults());
        }

        return $theme;
    }

    /**
     * Check if theme is customized
     */
    public function isCustom(): bool
    {
        return $this->theme_type === 'custom';
    }

    /**
     * Reset theme to defaults
     */
    public function resetToDefaults(): void
    {
        $defaults = self::getDefaults();
        $this->update($defaults);
    }

    /**
     * Get the team that owns the theme.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the knowledge board for this theme.
     */
    public function knowledgeBoard()
    {
        return $this->belongsTo(KnowledgeBoard::class);
    }
}
