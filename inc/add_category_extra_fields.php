<?php
//if(isset($tag))
//$tID = $tag->term_id;
//$catMeta = get_term_meta($tID);
//if(isset($catMeta['cat_video']) && $catMeta['cat_video'][0]!= ''){
//    $video = $catMeta['cat_video'][0];
//}
//$imgID = get_term_meta($tID, 'cat_image', true);
//$imgSrc = wp_get_attachment_image_src($imgID, 'medium');
//$haveImg = is_array($imgSrc);
$uploadLink = esc_url(get_upload_iframe_src('image'));

?>
<div class="form-field">
    <div scope="row"><label><?php _e('Category Image'); ?></label></div>
    <div>
        <div id="custom_cat_image">
            <div class="custom-img-container">

            </div>
            <p class="hide-if-no-js">
                <a class="upload-custom-img button button-primary button-large"
                   href="<?php echo $uploadLink ?>">
                    <?php _e('Add Image') ?>
                </a>
                <a class="delete-custom-img button button-primary button-large hidden" href="#">
                    <?php _e('Remove this image') ?>
                </a>
            </p>
            <input type="hidden" name="cat_image" id="cat_image_id"
                   value=""><br/>
        </div>
        <!--        <span class="description">--><?php //_e('Image for category: use full url with '); ?><!--</span>-->
    </div>
</div>
<div class="form-field">
    <div scope="row"><label for="cat_video_input"><?php _e('Category Video (Youtube/Vimeo)'); ?></label></div>
    <div>
        <div id="custom_cat_video">
            <input type="text" name="cat_video" id="cat_video_input" style="margin-bottom: 20px" value="">
            <span class="spinner" style="float: none"></span>
            <p class="hide-if-no-js">

                <a class="delete-custom-video button button-primary button-large hidden"
                   href="#">
                    <?php _e('Remove this Video') ?>
                </a>
            </p>
        </div>
    </div>

</div>