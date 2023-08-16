<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class Articles extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'templates.template-articles',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'articles' => $this->articles(),
        ];
    }

    public function articles()
    {

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'article'
        );

        $query = new WP_Query( $args );

        return $query;

    }
}
