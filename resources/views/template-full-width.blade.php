{{--
  Template Name: Full Width
  Template Post Type: page
--}}
@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php the_post() @endphp

    {{-- Page header --}}
    <div class="bg-cream border-b border-border pt-16 pb-10">
      <div class="max-w-360 mx-auto px-6 lg:px-10">
        @include('partials.breadcrumb')
        <h1 class="font-serif text-[clamp(1.75rem,3.5vw,3rem)] font-light text-ink leading-tight mt-4">
          {!! get_the_title() !!}
        </h1>
      </div>
    </div>

    {{-- Content — full width, no sidebar, constrained prose --}}
    <div class="bg-surface">
      <div class="max-w-360 mx-auto px-6 lg:px-10 py-14 lg:py-18">
        <div class="prose max-w-none lg:prose-lg prose-headings:font-serif prose-headings:font-light prose-a:text-primary">
          @php the_content() @endphp
        </div>
        @php
          wp_link_pages([
            'before' => '<nav class="page-links mt-8 text-sm" aria-label="' . __('Pagine', 'sage') . '">',
            'after'  => '</nav>',
          ]);
        @endphp
      </div>
    </div>

  @endwhile
@endsection
