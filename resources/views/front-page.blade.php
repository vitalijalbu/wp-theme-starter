{{--
  front-page.blade.php — Homepage template
  Sections assembled here. Each @include accepts $variables to override defaults.
  To customise: Appearance → Customizer, or pass $vars inline.
--}}
@extends('layouts.app')

@section('content')
@php
  // ── Hero data from Customizer ──────────────────────────────────────────────
  $hero_image_id  = (int) get_theme_mod('home_hero_image_id', 0);
  $hero_image_url = $hero_image_id
    ? wp_get_attachment_image_url($hero_image_id, 'full')
    : '';
  $hero_heading   = get_theme_mod('home_hero_heading', get_bloginfo('name'));
  $hero_subtext   = get_theme_mod('home_hero_subtext', get_bloginfo('description'));
  $hero_cta_label = get_theme_mod('home_hero_cta_label', __('Esplora la collezione', 'sage'));
  $hero_cta_url   = get_theme_mod('home_hero_cta_url',
    function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop'));

  // ── Brand story (media-text) ───────────────────────────────────────────────
  $mt_image_id  = (int) get_theme_mod('home_story_image_id', 0);
  $mt_image_url = $mt_image_id ? wp_get_attachment_image_url($mt_image_id, 'large') : '';
  $mt_image_alt = $mt_image_id
    ? esc_attr(get_post_meta($mt_image_id, '_wp_attachment_image_alt', true))
    : '';
@endphp

  {{-- 1. HERO ─────────────────────────────────────────────────────────────── --}}
  @include('sections.hero', [
    'image_url'     => $hero_image_url,
    'image_id'      => $hero_image_id,
    'heading'       => $hero_heading,
    'subtext'       => $hero_subtext,
    'cta_label'     => $hero_cta_label,
    'cta_url'       => $hero_cta_url,
    'overlay'       => true,
    'loading'       => 'eager',
    'fetchpriority' => true,
  ])

  {{-- 2. MARQUEE — trust signals ──────────────────────────────────────────── --}}
  @include('sections.marquee', [
    'items' => [
      __('Spedizione gratuita sopra i 50€', 'sage'),
      __('Resi gratuiti entro 30 giorni', 'sage'),
      __('Pagamento sicuro certificato', 'sage'),
      __('Assistenza clienti 7 giorni su 7', 'sage'),
      __('Prodotti selezionati con cura', 'sage'),
    ],
  ])

  {{-- 3. PRODUCT CATEGORIES ───────────────────────────────────────────────── --}}
  @if(function_exists('WC'))
    @include('sections.categories-grid', [
      'section_label' => __('Esplora', 'sage'),
      'section_title' => __('Le nostre categorie', 'sage'),
      'bg'            => 'surface',
      'number'        => 6,
    ])
  @endif

  {{-- 4. NEW ARRIVALS ─────────────────────────────────────────────────────── --}}
  @if(function_exists('WC'))
    @include('sections.products-grid', [
      'section_label' => __('Nuovi arrivi', 'sage'),
      'section_title' => __('Le ultime novità', 'sage'),
      'bg'            => 'cream',
      'orderby'       => 'date',
      'number'        => 8,
      'cta_label'     => __('Vedi tutto lo shop', 'sage'),
      'cta_url'       => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop'),
    ])
  @endif

  {{-- 5. BRAND STORY ───────────────────────────────────────────────────────── --}}
  @include('sections.media-text', [
    'image_url'   => $mt_image_url,
    'image_id'    => $mt_image_id,
    'image_alt'   => $mt_image_alt,
    'label'       => __('La nostra storia', 'sage'),
    'heading'     => get_theme_mod('home_story_heading', __('Qualità e passione, ogni giorno', 'sage')),
    'body'        => get_theme_mod('home_story_body', __('Selezioniamo con cura ogni prodotto per garantire la massima qualità. La nostra missione è offrire un\'esperienza premium, con un servizio attento e personalizzato.', 'sage')),
    'cta_label'   => get_theme_mod('home_story_cta_label', __('Scopri di più', 'sage')),
    'cta_url'     => get_theme_mod('home_story_cta_url', home_url('/chi-siamo')),
    'image_right' => false,
    'bg'          => 'surface',
  ])

  {{-- 6. HOW IT WORKS ──────────────────────────────────────────────────────── --}}
  @include('sections.process-steps', [
    'section_label' => __('Come funziona', 'sage'),
    'section_title' => __('Semplice, veloce, affidabile', 'sage'),
    'bg'            => 'ink',
    'layout'        => 'horizontal',
  ])

  {{-- 7. STATS ─────────────────────────────────────────────────────────────── --}}
  @include('sections.stats', [
    'bg' => 'cream',
  ])

  {{-- 8. FEATURES / WHY US ─────────────────────────────────────────────────── --}}
  @include('sections.features', [
    'section_label' => __('I nostri vantaggi', 'sage'),
    'section_title' => __('Perché sceglierci', 'sage'),
    'bg'            => 'surface',
  ])

  {{-- 9. TESTIMONIALS ──────────────────────────────────────────────────────── --}}
  @include('sections.testimonials', [
    'section_label' => __('Recensioni', 'sage'),
    'section_title' => __('Cosa dicono i nostri clienti', 'sage'),
    'bg'            => 'surface',
  ])

  {{-- 10. BLOG POSTS ───────────────────────────────────────────────────────── --}}
  @include('sections.blog-posts', [
    'section_label' => __('Blog', 'sage'),
    'section_title' => __('Dal nostro blog', 'sage'),
    'bg'            => 'cream',
    'number'        => 3,
  ])

  {{-- 11. FAQ ──────────────────────────────────────────────────────────────── --}}
  @include('sections.faq-accordion', [
    'section_label' => __('FAQ', 'sage'),
    'section_title' => __('Domande frequenti', 'sage'),
    'layout'        => 'two-col',
    'number'        => 8,
    'bg'            => 'surface',
  ])

  {{-- 12. CTA / NEWSLETTER ─────────────────────────────────────────────────── --}}
  @include('sections.cta-banner', [
    'bg' => 'ink',
  ])

@endsection
