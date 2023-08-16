<?php

/**
 * Register Post Type and Taxonomy - Article
 */
add_action('init', function () {

    register_taxonomy( 'article-category', [ 'article' ], [
        'label'                 => __( 'Category Articles', 'child-honest' ),
        'labels'                => array(
            'name'              => __( 'Category Articles', 'child-honest' ),
            'singular_name'     => __( 'Category', 'child-honest' ),
            'search_items'      => __( 'Search', 'child-honest' ),
            'all_items'         => __( 'All', 'child-honest' ),
            'parent_item'       => __( 'Prev', 'child-honest' ),
            'parent_item_colon' => __( 'Next', 'child-honest' ),
            'edit_item'         => __( 'Edit', 'child-honest' ),
            'update_item'       => __( 'Update', 'child-honest' ),
            'add_new_item'      => __( 'Add', 'child-honest' ),
            'new_item_name'     => __( 'New', 'child-honest' ),
            'menu_name'         => __( 'Category Articles', 'child-honest' ),
        ),
        'description'           => __( 'Category Articles', 'child-honest' ),
        'public'                => true,
        'show_in_nav_menus'     => true,
        'show_ui'               => true,
        'show_tagcloud'         => false,
        'hierarchical'          => true,
        'rewrite'               => array(false, 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false ),
        'show_admin_column'     => true,
        'show_in_rest'          => true,
    ] );

    register_post_type( 'article', [
        'label'               => __( 'Articles', 'child-honest' ),
        'labels'              => array(
            'name'          => __( 'Articles', 'child-honest' ),
            'singular_name' => __( 'Articles', 'child-honest' ),
            'menu_name'     => __( 'Articles', 'child-honest' ),
            'all_items'     => __( 'Articles', 'child-honest' ),
            'add_new'       => __( 'Add', 'child-honest' ),
            'add_new_item'  => __( 'Add New', 'child-honest' ),
            'edit'          => __( 'Edit', 'child-honest' ),
            'edit_item'     => __( 'Edit', 'child-honest' ),
            'new_item'      => __( 'New', 'child-honest' ),
        ),
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'rest_base'           => '',
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-grid-view',
        'exclude_from_search' => false,
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
        'hierarchical'        => false,
        'rewrite'             => array('slug' => 'articles/article', 'hierarchical' => true, 'with_front' => true, 'feed' => false ),
        'has_archive'         => true,
        'query_var'           => true,
        'supports' => [
            'title',
            'editor',
            'custom-fields',
            'page-attributes',
        ],
    ] );

});

/**
 * Add Metabox Preview
 */
add_action('add_meta_boxes', function ()
{

    global $post;

    add_meta_box(
        'article_preview',
        __( 'Preview', 'child-honest' ),
        'article_preview_render',
        [ 'article' ],
        'side',
        'low'
    );

    if(!empty($post))
    {
        $tpl = get_post_meta($post->ID, '_wp_page_template', true);

        if($tpl == 'templates/template-articles.blade.php' )
        {
            add_meta_box(
                'article_preview',
                __( 'Preview', 'child-honest' ),
                'article_preview_render',
                'page',
                'side',
                'low'
            );
        }
    }

});

/**
 * Render Preview Metabox
 * @param $post
 * @return void
 */
function article_preview_render( $post )
{

    global $content_width,
           $_wp_additional_image_sizes;

    $image_id = get_post_meta( $post->ID, '_article_preview', true );

    $old_content_width = $content_width;
    $content_width = 254;

    if ( $image_id && get_post( $image_id ) ) {

        if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
            $thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
        } else {
            $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
        }

        if ( ! empty( $thumbnail_html ) ) {
            $content = $thumbnail_html;
            $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_author_trends_button" >' . esc_html__( 'Remove Image', 'child-honest' ) . '</a></p>';
            $content .= '<input type="hidden" id="article_preview_image" name="_article_preview" value="' . esc_attr( $image_id ) . '" />';
        }

        $content_width = $old_content_width;
    } else {

        $content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
        $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set Image', 'child-honest' ) . '" href="javascript:;" id="upload_article_image_button" id="set-listing-image-trends" data-uploader_title="' . esc_attr__( 'Select Image', 'child-honest' ) . '" data-uploader_button_text="' . esc_attr__( 'Set Image', 'child-honest' ) . '">' . esc_html__( 'Set Image', 'child-honest' ) . '</a></p>';
        $content .= '<input type="hidden" id="article_preview_image" name="_article_preview" value="" />';

    }

    echo $content;
}

/**
 * Save Post
 */
add_action( 'save_post', function ($post_id) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return;

    if( ! current_user_can( 'edit_post', $post_id ) )
        return;

    if ( isset( $_POST['_article_preview'] ) )
    {

        $image = absint($_POST['_article_preview']);
        update_post_meta( $post_id, '_article_preview', $image );

    }

} );

/**
 * Admin footer js
 */
add_action( 'in_admin_footer', function ()
{

    global $post;

    $screen = get_current_screen();
    $tpl = null;

    if( isset($post->ID) )
    {
        $tpl = get_post_meta($post->ID, '_wp_page_template', true);
    }

    if(('post'== $screen->base && 'article' == $screen->id) || $tpl == 'templates/template-articles.blade.php'){
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {

                var file_frame_article;

                jQuery.fn.article_preview_image = function( button ) {
                    var button_id = button.attr('id'),
                        field_id = 'article_preview_image';

                    if ( file_frame_article )
                    {
                        file_frame_article.open();
                        return;
                    }

                    file_frame_article = wp.media.frames.file_frame_article = wp.media({
                        title: jQuery( this ).data( 'uploader_title' ),
                        button: {
                            text: jQuery( this ).data( 'uploader_button_text' ),
                        },
                        multiple: false
                    });

                    file_frame_article.on( 'select', function()
                    {
                        var attachment = file_frame_article.state().get('selection').first().toJSON();

                        jQuery("#"+field_id).val(attachment.id);
                        jQuery("#article_preview img").attr('src',attachment.url);
                        jQuery( '#article_preview img' ).show();
                        jQuery( '#' + button_id ).attr( 'id', 'remove_article_preview_button' );
                        jQuery( '#remove_article_preview_button' ).text( 'Remove Image' );
                    });

                    file_frame_article.open();
                };


                jQuery('#article_preview').on( 'click', '#upload_article_image_button', function( event )
                {
                    event.preventDefault();
                    jQuery.fn.article_preview_image( jQuery(this) );
                });

                jQuery('#article_preview').on( 'click', '#remove_author_trends_button', function( event )
                {
                    event.preventDefault();
                    jQuery( '#article_preview_image' ).val( '' );
                    jQuery( '#article_preview img' ).attr( 'src', '' );
                    jQuery( '#article_preview img' ).hide();
                    jQuery( this ).attr( 'id', 'upload_article_image_button' );
                    jQuery( '#upload_article_image_button' ).text( 'Set Image' );
                });

            });
        </script>
        <?php
    }

} );

/**
 * Ajax: Add Article
 */
add_action( 'wp_ajax_nopriv_add_article', 'add_article_ajax_handler' );
add_action( 'wp_ajax_add_article', 'add_article_ajax_handler' );
function add_article_ajax_handler()
{

    $email = sanitize_email($_POST['email']);
    $title = sanitize_text_field($_POST['title']);

    $error = [];

    //Check Email
    if ( !$email )
    {
        $error[] = __('Email not Valid', 'child-honest');
    }

    //Check Title
    if ( !$title || empty($title) )
    {
        $error[] = __('Title not Valid', 'child-honest');
    }

    //Check File
    if ( !file_exists($_FILES['preview']['tmp_name']) )
    {
        $error[] = __('You Have not Selected a File', 'child-honest');
    }

    if( !$error )
    {
        //Check File Ext
        $img_ext = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!in_array($_FILES['preview']['type'], $img_ext))
        {
            $error[] = __('File only png, jpeg, jpg, gif', 'child-honest');
        }

        if( !$error )
        {
            //Upload File
            $upload = wp_handle_upload(
                $_FILES['preview'],
                array( 'test_form' => false )
            );

            if( ! empty( $upload[ 'error' ] ) )
            {
                $error[] = __('File upload error', 'child-honest');
            }

            if( !$error )
            {
                $attachment_id = wp_insert_attachment(
                    array(
                        'guid'           => $upload[ 'url' ],
                        'post_mime_type' => $upload[ 'type' ],
                        'post_title'     => basename( $upload[ 'file' ] ),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    ),
                    $upload[ 'file' ]
                );

                if( is_wp_error( $attachment_id ) || ! $attachment_id )
                {
                    $error[] = __('File upload error', 'child-honest');
                }

                if( !$error )
                {
                    wp_update_attachment_metadata(
                        $attachment_id,
                        wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
                    );

                    //Create Post
                    $new_post = [
                        'post_title' => $title,
                        'post_status' => 'publish',
                        'post_type' => 'article',
                        'post_author' => 1,
                        'post_content' => '',
                        'meta_input' => ['_article_preview' => $attachment_id]
                    ];

                    $post_id = wp_insert_post( $new_post, true );

                    if( is_wp_error($post_id) )
                    {
                        $error[] = __('Record Creation Error', 'child-honest');
                    }
                }

            }

        }

    }

    if( $error )
    {

        $message = '<ul class="error">';
        foreach ( $error as $item )
        {
            $message .= sprintf('<li>%s</li>', $item);
        }
        $message .= '</ul>';

        wp_send_json_error([
            'message' => $message
        ]);
    }

    // get the admin email
    $admin_email = get_option('admin_email');

    // send the email
    wp_mail( $admin_email, __('Add Article', 'child-honest'), __('Success', 'child-honest'));

    //send email user
    if( ! wp_next_scheduled( 'article_email_user_event' ) )
    {
        wp_schedule_single_event( time() + 120, 'article_email_user_event', [$email] );
    }

    wp_send_json_success([
        'message' => sprintf('<div class="success">%s</div>', __('Success', 'child-honest'))
    ]);

}

/**
 * Send Email User
 */
add_action( 'article_email_user_event', 'article_send_user_email' );
function article_send_user_email($email)
{
    wp_mail( $email, __('Add Article', 'child-honest'), __('Success', 'child-honest'));
}
