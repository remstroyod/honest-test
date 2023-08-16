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

@endsection
