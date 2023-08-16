<div class="col-3">

  <a href="{{ get_the_permalink() }}" class="articles__list-item">

    <h5 class="articles__list-item--title">
      {{ the_title() }}
    </h5>

    @php
      $image = get_post_meta( get_the_ID(), '_article_preview', true );
      if( $image )
      {
          $img = wp_get_attachment_image( $image, 'article-preview' );

          echo '<div class="articles__list-item--preview">';
          echo $img;
          echo '</div>';
      }

    @endphp

  </a>

</div>
