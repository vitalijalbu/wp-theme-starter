<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

/**
 * View Composer for the homepage (front-page.blade.php).
 * Provides pre-resolved data to avoid inline WP queries in the template.
 */
class FrontPage extends Composer
{
    protected static $views = [
        'front-page',
    ];

    /**
     * Customizer hero data.
     */
    public function heroData(): array
    {
        $image_id  = (int) get_theme_mod('home_hero_image_id', 0);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';

        return [
            'image_id'    => $image_id,
            'image_url'   => $image_url,
            'heading'     => get_theme_mod('home_hero_heading', get_bloginfo('name')),
            'subtext'     => get_theme_mod('home_hero_subtext', get_bloginfo('description')),
            'cta_label'   => get_theme_mod('home_hero_cta_label', __('Esplora la collezione', 'sage')),
            'cta_url'     => get_theme_mod('home_hero_cta_url', $this->defaultShopUrl()),
        ];
    }

    /**
     * Customizer brand-story (media-text) data.
     */
    public function storyData(): array
    {
        $image_id  = (int) get_theme_mod('home_story_image_id', 0);
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
        $image_alt = $image_id
            ? esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true))
            : '';

        return [
            'image_id'  => $image_id,
            'image_url' => $image_url,
            'image_alt' => $image_alt,
            'heading'   => get_theme_mod('home_story_heading', __('Qualità e passione, ogni giorno', 'sage')),
            'body'      => get_theme_mod('home_story_body', __('Selezioniamo con cura ogni prodotto per garantire la massima qualità.', 'sage')),
            'cta_label' => get_theme_mod('home_story_cta_label', __('Scopri di più', 'sage')),
            'cta_url'   => get_theme_mod('home_story_cta_url', home_url('/chi-siamo')),
        ];
    }

    /**
     * Default shop URL — works with or without WooCommerce.
     */
    private function defaultShopUrl(): string
    {
        return function_exists('wc_get_page_permalink')
            ? (string) wc_get_page_permalink('shop')
            : home_url('/shop');
    }
}
