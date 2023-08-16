<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function ()
{

    bundle('app')->enqueue();
    bundle('articles')->enqueue();

    wp_enqueue_script("jquery");
    $script_data_array = [
        'ajaxurl' => admin_url('admin-ajax.php'),
    ];
    wp_localize_script('jquery', 'ajax_global_handle', $script_data_array);

}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function ()
{
    bundle('editor')->enqueue();
}, 100);

/**
 * After Setup Theme
 */
add_action('after_setup_theme', function ()
{

    add_image_size( 'article-preview', 854, 480 );

});
