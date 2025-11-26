<?php

namespace App\View\Composers;

use App\Models\AppSettings;
use App\Models\KnowledgeBoardTheme;
use Illuminate\View\View;

class PublicMenuComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Get the settings from the view data if available
        $settings = $view->getData()['settings'] ?? null;

        if ($settings && $settings->team_id) {
            // Get menu order from any active custom theme for this team
            $theme = KnowledgeBoardTheme::where('team_id', $settings->team_id)
                ->where('theme_type', 'custom')
                ->where('is_active', true)
                ->first();

            if ($theme && $theme->menu_order) {
                $menuOrder = $theme->menu_order;
            } else {
                $menuOrder = KnowledgeBoardTheme::getDefaultMenuOrder();
            }

            // Sort by order and filter visible items
            usort($menuOrder, fn($a, $b) => ($a['order'] ?? 0) - ($b['order'] ?? 0));

            $view->with('menuOrder', $menuOrder);
        } else {
            $view->with('menuOrder', KnowledgeBoardTheme::getDefaultMenuOrder());
        }
    }
}
