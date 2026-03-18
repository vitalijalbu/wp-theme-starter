@props([
    'id'            => 0,
    'alt'           => '',
    'class'         => 'w-full h-full object-cover',
    'loading'       => 'lazy',
    'sizes'         => '(max-width: 640px) 100vw, (max-width: 1024px) 75vw, 50vw',
    'size'          => 'large',
    'fetchpriority' => null,
    'data'          => [],
])
@php
    $img_id = (int) $id;
    if (! $img_id) {
        return;
    }

    $src    = wp_get_attachment_image_url($img_id, $size) ?: '';
    $srcset = wp_get_attachment_image_srcset($img_id, $size) ?: '';

    if (! $src) {
        return;
    }

    // Build WebP srcset from WP 5.8+ sub-size sources (if WebP generation is active)
    $webp_srcset = '';
    $metadata    = wp_get_attachment_metadata($img_id);
    if (isset($metadata['file'], $metadata['sizes']) && is_array($metadata['sizes'])) {
        $upload     = wp_get_upload_dir();
        $file_dir   = $upload['baseurl'] . '/' . dirname($metadata['file']);
        $webp_parts = [];
        foreach ($metadata['sizes'] as $size_data) {
            $webp_file  = $size_data['sources']['image/webp']['file'] ?? '';
            $webp_width = (int) ($size_data['width'] ?? 0);
            if ($webp_file && $webp_width) {
                $webp_parts[] = esc_url($file_dir . '/' . $webp_file) . ' ' . $webp_width . 'w';
            }
        }
        if (! empty($webp_parts)) {
            $webp_srcset = implode(', ', $webp_parts);
        }
    }

    // data-* attributes string
    $data_attrs = '';
    foreach ($data as $key => $val) {
        $data_attrs .= ' data-' . esc_attr($key) . '="' . esc_attr($val) . '"';
    }
@endphp

<picture>
    @if($webp_srcset)
        <source
            type="image/webp"
            srcset="{{ $webp_srcset }}"
            sizes="{{ $sizes }}"
        >
    @endif
    @if($srcset)
        <source
            srcset="{{ $srcset }}"
            sizes="{{ $sizes }}"
        >
    @endif
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        loading="{{ $loading }}"
        decoding="async"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        {!! $data_attrs !!}
    >
</picture>
