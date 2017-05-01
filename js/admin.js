(function ($) {
    var frame,
        metaBox = $('#custom_cat_image'), // Your meta box id here
        addImgLink = metaBox.find('.upload-custom-img'),
        delImgLink = metaBox.find( '.delete-custom-img'),
        imgContainer = metaBox.find( '.custom-img-container'),
        imgIdInput = metaBox.find( '#cat_image_id' );

    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Media Of Your Chosen Persuasion',
            button: {
                text: 'Use this media'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field.
            imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;width: 300px"/>' );

            // Send the attachment id to our hidden input
            imgIdInput.val( attachment.id );

            // Hide the add image link
            addImgLink.addClass( 'hidden' );

            // Unhide the remove image link
            delImgLink.removeClass( 'hidden' );
        });

        // Finally, open the modal on click
        frame.open();
    });


    // DELETE IMAGE LINK
    delImgLink.on( 'click', function( event ){

        event.preventDefault();

        // Clear out the preview image
        imgContainer.html( '' );

        // Un-hide the add image link
        addImgLink.removeClass( 'hidden' );

        // Hide the delete image link
        delImgLink.addClass( 'hidden' );

        // Delete the image id from the hidden input
        imgIdInput.val( '' );

    });
    var videoWrap = $('#custom_cat_video');
    var videoDelete = videoWrap.find('.delete-custom-video');
    var videoInput = videoWrap.find('input');
    videoInput.focusout(function(){

        var val = $(this).val();
        videoInput.next('img').remove();
        videoDelete.addClass('hidden');
        videoWrap.find('.spinner').addClass('is-active');
        $.ajax({
            url: ajax.url,
            method: "POST",
            dataType: 'json',
            data:{
                action: 'add_video_thumb',
                _ajax_nonce: ajax.nonce,
                url: val,
            },
            success: function(data){

                if(data != 0){
                    videoInput.after('<img src="'+data+'">');
                    videoDelete.removeClass('hidden');
                }
                videoWrap.find('.spinner').removeClass('is-active');

            },
            error: function (data) {
                console.log(data)

            }
        })
    });
    videoDelete.click(function (e) {
        e.preventDefault();
        videoInput.val('');
        videoInput.next().remove();
        $(this).addClass('hidden');
    });
    $('.edit-tags-php .submit input').click(function () {
        imgContainer.html('');
        delImgLink.addClass('hidden');
        addImgLink.removeClass('hidden');
        videoDelete.addClass('hidden');
        videoInput.next('img').remove();

    })

})(jQuery);