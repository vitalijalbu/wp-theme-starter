{{--
  front-page.blade.php — Homepage template
  Renders the block editor content set in WordPress → Pages → Home.
--}}
@extends('layouts.app')

@section('content')
  @while(have_posts())
    @php the_post() @endphp
    @php the_content() @endphp
  @endwhile
@endsection
