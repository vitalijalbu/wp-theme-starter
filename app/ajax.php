<?php

/**
 * Centralized AJAX / REST handlers.
 * Covers: contact form, live search, wishlist toggle.
 */

namespace App;

// ── Contact form (admin-post.php) ─────────────────────────────────────────────

add_action('admin_post_theme_contact',        __NAMESPACE__ . '\\handle_contact_form');
add_action('admin_post_nopriv_theme_contact', __NAMESPACE__ . '\\handle_contact_form');

/**
 * Process the contact form submission.
 * Validates nonce, honeypot, required fields, then fires wp_mail().
 */
function handle_contact_form(): void
{
    // Honeypot check
    if (!empty($_POST['honeypot'])) {
        wp_send_json(['success' => false, 'message' => ''], 400);
    }

    // Nonce verification
    if (
        empty($_POST['_contact_nonce'])
        || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_contact_nonce'])), 'theme_contact_form')
    ) {
        wp_send_json([
            'success' => false,
            'message' => __('Verifica di sicurezza fallita. Ricarica la pagina.', 'sage'),
        ], 403);
    }

    $name    = sanitize_text_field(wp_unslash($_POST['contact_name']    ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['contact_email']        ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['contact_subject'] ?? __('Nuovo messaggio dal sito', 'sage')));
    $message = sanitize_textarea_field(wp_unslash($_POST['contact_message'] ?? ''));
    $privacy = !empty($_POST['contact_privacy']);

    if (! $name || ! is_email($email) || ! $message || ! $privacy) {
        wp_send_json([
            'success' => false,
            'message' => __('Compila tutti i campi obbligatori.', 'sage'),
        ], 422);
    }

    $to      = sanitize_email(get_option('admin_email'));
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        sprintf('Reply-To: %s <%s>', $name, $email),
    ];

    $body = sprintf(
        "<p><strong>Nome:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Messaggio:</strong></p><p>%s</p>",
        esc_html($name),
        esc_html($email),
        nl2br(esc_html($message))
    );

    /**
     * Allow plugins/integrations to intercept before sending.
     * Return false to skip wp_mail (e.g. send to CRM instead).
     */
    $send = apply_filters('theme_before_contact_mail', true, compact('name', 'email', 'subject', 'message'));

    $sent = $send ? wp_mail($to, '[' . get_bloginfo('name') . '] ' . $subject, $body, $headers) : true;

    if ($sent) {
        do_action('theme_contact_form_sent', compact('name', 'email', 'subject', 'message'));
        wp_send_json([
            'success' => true,
            'message' => __('Messaggio inviato. Ti risponderemo al più presto.', 'sage'),
        ]);
    } else {
        wp_send_json([
            'success' => false,
            'message' => __('Invio non riuscito. Riprova o contattaci via email.', 'sage'),
        ], 500);
    }
}

// ── Live Search — REST endpoint ───────────────────────────────────────────────
// GET /wp-json/theme/v1/search?q=keyword&per_page=6&type=any

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/search', [
        'methods'             => 'GET',
        'callback'            => __NAMESPACE__ . '\\live_search',
        'permission_callback' => '__return_true',
        'args'                => [
            'q' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => fn($v) => strlen(trim($v)) >= 2,
            ],
            'per_page' => [
                'default'           => 6,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'type' => [
                'default'           => 'any',
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ],
        ],
    ]);
});

/**
 * Live search handler — returns posts + products matching query.
 *
 * @param \WP_REST_Request $request
 * @return \WP_REST_Response
 */
function live_search(\WP_REST_Request $request): \WP_REST_Response
{
    $query    = sanitize_text_field($request->get_param('q'));
    $per_page = min((int) $request->get_param('per_page'), 12);
    $type     = sanitize_key($request->get_param('type'));

    $post_types = ['post', 'page'];
    if (function_exists('WC') && in_array($type, ['any', 'product'], true)) {
        $post_types[] = 'product';
    }
    if ($type !== 'any' && in_array($type, $post_types, true)) {
        $post_types = [$type];
    }

    $wp_query = new \WP_Query([
        's'              => $query,
        'post_type'      => $post_types,
        'posts_per_page' => $per_page,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ]);

    $results = [];
    foreach ($wp_query->posts as $pid) {
        $pid       = (int) $pid;
        $thumb_id  = get_post_thumbnail_id($pid);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'thumbnail') : '';
        $post_type = get_post_type($pid);

        $price = '';
        if ($post_type === 'product' && function_exists('wc_get_product')) {
            $product = wc_get_product($pid);
            $price   = $product ? wp_strip_all_tags($product->get_price_html()) : '';
        }

        $results[] = [
            'id'        => $pid,
            'title'     => esc_html(get_the_title($pid)),
            'url'       => esc_url(get_permalink($pid)),
            'thumb'     => esc_url($thumb_url),
            'type'      => $post_type,
            'price'     => $price,
            'excerpt'   => wp_trim_words(get_the_excerpt($pid), 12, '…'),
        ];
    }

    return rest_ensure_response([
        'query'   => $query,
        'count'   => count($results),
        'results' => $results,
        'more_url' => esc_url(home_url('/?s=' . urlencode($query))),
    ]);
}

// ── Wishlist — REST endpoint ──────────────────────────────────────────────────
// POST /wp-json/theme/v1/wishlist  { "product_id": 123, "action": "add"|"remove" }

add_action('rest_api_init', function () {
    register_rest_route('theme/v1', '/wishlist', [
        'methods'             => 'POST',
        'callback'            => __NAMESPACE__ . '\\wishlist_toggle',
        'permission_callback' => '__return_true',
        'args'                => [
            'product_id' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'action' => [
                'default'           => 'toggle',
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ],
        ],
    ]);
});

/**
 * Server-side wishlist — stores in user meta for logged-in users.
 * Guests use client-side localStorage; this syncs on login.
 *
 * @param \WP_REST_Request $request
 * @return \WP_REST_Response|\WP_Error
 */
function wishlist_toggle(\WP_REST_Request $request): \WP_REST_Response|\WP_Error
{
    if (! is_user_logged_in()) {
        // For guests, client-side localStorage is the primary store.
        return rest_ensure_response(['success' => true, 'guest' => true]);
    }

    $user_id    = get_current_user_id();
    $product_id = (int) $request->get_param('product_id');
    $action     = sanitize_key($request->get_param('action'));

    if (! $product_id || ! get_post($product_id)) {
        return new \WP_Error('invalid_product', __('Prodotto non trovato.', 'sage'), ['status' => 404]);
    }

    $wishlist = (array) get_user_meta($user_id, '_theme_wishlist', true);

    if ($action === 'add' || ($action === 'toggle' && ! in_array($product_id, $wishlist, true))) {
        $wishlist[] = $product_id;
        $added      = true;
    } else {
        $wishlist = array_values(array_filter($wishlist, fn($id) => $id !== $product_id));
        $added    = false;
    }

    update_user_meta($user_id, '_theme_wishlist', array_unique($wishlist));
    do_action('theme_wishlist_updated', $user_id, $product_id, $added);

    return rest_ensure_response([
        'success'  => true,
        'added'    => $added,
        'wishlist' => array_values(array_unique($wishlist)),
    ]);
}
