{{--
  Template Name: Articles Page Template
--}}

@extends('layouts.app')

@section('content')

  @php
    $image = get_post_meta( get_the_ID(), '_article_preview', true );
    if( $image )
    {
        $img = wp_get_attachment_image( $image, 'full' );

        echo '<div class="articles__preview">';
        echo $img;
        echo '</div>';
    }

  @endphp

  @if( $articles->have_posts() )

    <div class="row">
      @while($articles->have_posts()) @php($articles->the_post())
      @include('partials.partial-article-item')
      @endwhile
    </div>
    @php(wp_reset_postdata())
  @else

    {{ __('Data not Found', 'child-honest') }}

  @endif

  <div class="acf_fields">
    <div class="row">
      <div class="col-3">
        {{ get_field('article_field_1') }}
      </div>
      <div class="col-3">
        {{ get_field('article_field_2') }}
      </div>
      <div class="col-3">
        {{ get_field('article_field_3') }}
      </div>
      <div class="col-3">
        {{ get_field('article_field_4') }}
      </div>
      <div class="col-3">
        {{ get_field('article_field_5') }}
      </div>
    </div>
  </div>

@endsection
