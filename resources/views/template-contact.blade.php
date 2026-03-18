{{--
  Template Name: Contatti
  Template Post Type: page
--}}
@extends('layouts.app')

@section('content')
@while(have_posts())
  @php the_post() @endphp
  @php
    // Customizer contact data
    $address = get_theme_mod('contact_address', '');
    $phone   = get_theme_mod('contact_phone',   '');
    $email   = get_theme_mod('contact_email',   get_bloginfo('admin_email'));
    $hours   = get_theme_mod('contact_hours',   '');
    $map_url = get_theme_mod('contact_map_embed_url', '');

    $social_ig = get_theme_mod('social_instagram', '');
    $social_fb = get_theme_mod('social_facebook',  '');
    $social_tk = get_theme_mod('social_tiktok',    '');
  @endphp

  {{-- Page header --}}
  <div class="bg-cream border-b border-border pt-16 pb-10">
    <div class="max-w-360 mx-auto px-6 lg:px-10">
      @include('partials.breadcrumb')
      <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight mt-4">
        {!! get_the_title() !!}
      </h1>
      @php $intro = get_the_excerpt(); @endphp
      @if($intro)
        <p class="section-subtitle mt-3">{{ $intro }}</p>
      @endif
    </div>
  </div>

  <div class="bg-surface">
    <div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-20">

      <div class="grid lg:grid-cols-[1fr_420px] gap-16 lg:gap-20">

        {{-- LEFT: Contact form --}}
        <div>
          <span class="section-label text-muted">{{ __('Scrivici', 'sage') }}</span>
          <h2 class="section-title text-ink mb-10">{{ __('Invia un messaggio', 'sage') }}</h2>

          @php the_content() @endphp

          {{-- Fallback form if no shortcode / plugin form in content --}}
          @if(!str_contains(get_the_content(), '[') && !str_contains(get_the_content(), 'wpcf7'))
            <form
              method="post"
              action="{{ esc_url(admin_url('admin-post.php')) }}"
              class="contact-form space-y-6"
              novalidate
              x-data="contactForm()"
              @submit.prevent="submit($el)"
            >
              @php wp_nonce_field('theme_contact_form', '_contact_nonce'); @endphp
              <input type="hidden" name="action" value="theme_contact">
              <input type="text" name="honeypot" class="sr-only" tabindex="-1" autocomplete="off" aria-hidden="true">

              <div class="grid sm:grid-cols-2 gap-6">
                <div>
                  <label for="contact-name" class="contact-label">{{ __('Nome', 'sage') }} <span aria-hidden="true">*</span></label>
                  <input
                    id="contact-name"
                    type="text"
                    name="contact_name"
                    required
                    autocomplete="given-name"
                    class="contact-input"
                    :class="errors.name ? 'contact-input--error' : ''"
                    x-model="fields.name"
                  >
                  <p x-show="errors.name" class="contact-error" x-text="errors.name" role="alert"></p>
                </div>
                <div>
                  <label for="contact-email" class="contact-label">{{ __('Email', 'sage') }} <span aria-hidden="true">*</span></label>
                  <input
                    id="contact-email"
                    type="email"
                    name="contact_email"
                    required
                    autocomplete="email"
                    class="contact-input"
                    :class="errors.email ? 'contact-input--error' : ''"
                    x-model="fields.email"
                  >
                  <p x-show="errors.email" class="contact-error" x-text="errors.email" role="alert"></p>
                </div>
              </div>

              <div>
                <label for="contact-subject" class="contact-label">{{ __('Oggetto', 'sage') }}</label>
                <input
                  id="contact-subject"
                  type="text"
                  name="contact_subject"
                  autocomplete="off"
                  class="contact-input"
                  x-model="fields.subject"
                >
              </div>

              <div>
                <label for="contact-message" class="contact-label">{{ __('Messaggio', 'sage') }} <span aria-hidden="true">*</span></label>
                <textarea
                  id="contact-message"
                  name="contact_message"
                  rows="6"
                  required
                  class="contact-input resize-none"
                  :class="errors.message ? 'contact-input--error' : ''"
                  x-model="fields.message"
                ></textarea>
                <p x-show="errors.message" class="contact-error" x-text="errors.message" role="alert"></p>
              </div>

              <div class="flex items-start gap-3">
                <input
                  id="contact-privacy"
                  type="checkbox"
                  name="contact_privacy"
                  required
                  class="mt-1 w-4 h-4 border-border accent-primary"
                  x-model="fields.privacy"
                >
                <label for="contact-privacy" class="font-sans text-sm text-muted leading-relaxed">
                  {{ __('Ho letto e accetto la', 'sage') }}
                  <a href="{{ esc_url(get_privacy_policy_url()) }}" target="_blank" rel="noopener" class="text-primary underline-offset-2 hover:underline">{{ __('Privacy Policy', 'sage') }}</a>
                </label>
              </div>

              <div>
                <button
                  type="submit"
                  class="btn-primary"
                  :disabled="loading"
                  :class="loading ? 'opacity-60 cursor-wait' : ''"
                >
                  <span x-show="!loading">{{ __('Invia messaggio', 'sage') }}</span>
                  <span x-show="loading" aria-live="polite">{{ __('Invio in corso…', 'sage') }}</span>
                </button>
              </div>

              {{-- Status message --}}
              <div
                x-show="status"
                x-transition
                :class="success ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
                class="border p-4 font-sans text-sm"
                role="alert"
                aria-live="assertive"
              >
                <p x-text="status"></p>
              </div>

            </form>
          @endif
        </div>

        {{-- RIGHT: Info sidebar --}}
        <aside>
          <span class="section-label text-muted">{{ __('Dove siamo', 'sage') }}</span>
          <h2 class="font-serif text-2xl font-light text-ink mb-8">{{ __('Informazioni di contatto', 'sage') }}</h2>

          <dl class="space-y-7">

            @if($address)
              <div class="flex gap-4">
                <dt class="shrink-0 mt-0.5">
                  <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-label="{{ __('Indirizzo', 'sage') }}"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                </dt>
                <dd class="font-sans text-sm text-muted leading-relaxed">{!! nl2br(esc_html($address)) !!}</dd>
              </div>
            @endif

            @if($phone)
              <div class="flex gap-4">
                <dt class="shrink-0 mt-0.5">
                  <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-label="{{ __('Telefono', 'sage') }}"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                </dt>
                <dd>
                  <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="font-sans text-sm text-muted hover:text-ink transition-colors">
                    {{ $phone }}
                  </a>
                </dd>
              </div>
            @endif

            @if($email)
              <div class="flex gap-4">
                <dt class="shrink-0 mt-0.5">
                  <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-label="{{ __('Email', 'sage') }}"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </dt>
                <dd>
                  <a href="mailto:{{ esc_attr($email) }}" class="font-sans text-sm text-muted hover:text-ink transition-colors">
                    {{ $email }}
                  </a>
                </dd>
              </div>
            @endif

            @if($hours)
              <div class="flex gap-4">
                <dt class="shrink-0 mt-0.5">
                  <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-label="{{ __('Orari', 'sage') }}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </dt>
                <dd class="font-sans text-sm text-muted leading-relaxed">{!! nl2br(esc_html($hours)) !!}</dd>
              </div>
            @endif

          </dl>

          {{-- Social links --}}
          @if($social_ig || $social_fb || $social_tk)
            <div class="mt-10 pt-8 border-t border-border">
              <p class="font-sans text-xs font-semibold tracking-[0.2em] uppercase text-muted mb-4">
                {{ __('Seguici', 'sage') }}
              </p>
              <div class="flex items-center gap-4">
                @foreach(array_filter(['Instagram' => $social_ig, 'Facebook' => $social_fb, 'TikTok' => $social_tk]) as $name => $url)
                  <a
                    href="{{ esc_url($url) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="font-sans text-xs font-semibold tracking-[0.12em] uppercase text-muted hover:text-ink transition-colors pb-0.5 border-b border-transparent hover:border-ink"
                  >{{ $name }}</a>
                @endforeach
              </div>
            </div>
          @endif

          {{-- Map embed --}}
          @if($map_url)
            <div class="mt-10 aspect-[4/3] overflow-hidden">
              <iframe
                src="{{ esc_url($map_url) }}"
                class="w-full h-full border-0 grayscale"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="{{ __('Mappa', 'sage') }}"
                aria-label="{{ __('Mappa della nostra sede', 'sage') }}"
              ></iframe>
            </div>
          @endif

        </aside>

      </div>
    </div>
  </div>

@endwhile

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('contactForm', () => ({
    loading: false,
    status: '',
    success: false,
    fields: { name: '', email: '', subject: '', message: '', privacy: false },
    errors: {},

    validate() {
      this.errors = {};
      if (!this.fields.name.trim())
        this.errors.name = '<?php echo esc_js(__("Il nome è obbligatorio.", "sage")); ?>';
      if (!this.fields.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.fields.email))
        this.errors.email = '<?php echo esc_js(__("Inserisci un indirizzo email valido.", "sage")); ?>';
      if (!this.fields.message.trim())
        this.errors.message = '<?php echo esc_js(__("Il messaggio è obbligatorio.", "sage")); ?>';
      return Object.keys(this.errors).length === 0;
    },

    async submit(form) {
      if (!this.validate()) return;
      this.loading = true;
      this.status  = '';
      try {
        const data = new FormData(form);
        const res  = await fetch('<?php echo esc_js(admin_url("admin-post.php")); ?>', {
          method: 'POST',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const json = await res.json();
        this.success = json.success ?? false;
        this.status  = json.message ?? (this.success
          ? '<?php echo esc_js(__("Messaggio inviato. Ti risponderemo presto.", "sage")); ?>'
          : '<?php echo esc_js(__("Si è verificato un errore. Riprova.", "sage")); ?>');
        if (this.success) this.fields = { name: '', email: '', subject: '', message: '', privacy: false };
      } catch {
        this.success = false;
        this.status = '<?php echo esc_js(__("Errore di rete. Riprova più tardi.", "sage")); ?>';
      } finally {
        this.loading = false;
      }
    },
  }));
});
</script>
@endsection
