jQuery(function ($) {

  'use strict';

  /******************************************************************
   * Articles
   * @type {{init: Articles.init, install: Articles.install}}
   * @since 1.0
   * @author Alex Cherniy
   * @date 15.08.2023
   */
  var Articles = {

    /**
     * Init
     */
    init: function () {

      this.install = this.install(this)


    },

    /**
     * Install
     */
    install: function () {

      /**
       * Form Submit
       */
      $(document).on(
        'submit',
        '.addArticle',
        this.submit)



    },

    /**
     * Add Article
     */
    submit: function(e)
    {

      e.preventDefault()

      let $this = $(this),
          $submit = $this.find('[type="submit"]'),
          form = $('#addArticle')[0],
          data = new FormData(form)

      data.append('preview', data);

      $.ajax({
        beforeSend: function(xhr)
        {
          $this.addClass('preload')
        },
        enctype: 'multipart/form-data',
        data: data,
        type: 'POST',
        processData: false,
        contentType: false,
        cache: false,
        complete: function()
        {
          $this.removeClass('preload')
        },
        error: function(response)
        {


        },
        success: function(response)
        {

          $this.find('.form__message').html(response.data.message)

          if( response.success === true )
          {
            $this.trigger("reset")
          }

        },
        url: ajax_global_handle.ajaxurl,
      });
    },

  }

  Articles.init()

});

