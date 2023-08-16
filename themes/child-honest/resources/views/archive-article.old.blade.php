@extends('layouts.app')

@section('content')

  @if( have_posts() )

    <div class="row">
      @while(have_posts()) @php(the_post())
        @include('partials.partial-article-item')
      @endwhile
    </div>

  @else

    {{ __('Data not Found', 'child-honest') }}

  @endif

@endsection
