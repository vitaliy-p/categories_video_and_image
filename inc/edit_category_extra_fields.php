<?php
$tID = $tag->term_id;
$catMeta = get_term_meta($tID);
if(isset($catMeta['cat_video']) && $catMeta['cat_video'][0]!= ''){
    $video = $catMeta['cat_video'][0];
}
$imgID = get_term_meta($tID, 'cat_image', true);
$imgSrc = wp_get_attachment_image_src($imgID, 'medium');
$uploadLink = esc_url(get_upload_iframe_src('image'));
$haveImg = is_array($imgSrc);
?>
<tr class="form-field">
    <th scope="row" valign="top"><label><?php _e('Category Image'); ?></label></th>
    <td>
        <div id="custom_cat_image">
            <div class="custom-img-container">
                <?php if ($haveImg) : ?>
                    <img src="<?php echo $imgSrc[0] ?>" alt="" style="max-width:100%;"/>
                <?php endif; ?>
            </div>
            <p class="hide-if-no-js">
                <a class="upload-custom-img button button-primary button-large <?php if ($haveImg) {
                    echo 'hidden';
                } ?>"
                   href="<?php echo $uploadLink ?>">
                    <?php _e('Add Image') ?>
                </a>
                <a class="delete-custom-img button button-primary button-large <?php if (!$haveImg) {
                    echo 'hidden';
                } ?>"
                   href="#">
                    <?php _e('Remove this image') ?>
                </a>
            </p>
            <input type="hidden" name="cat_image" id="cat_image_id"
                   value="<?php echo $catMeta['cat_image'][0] ? $catMeta['cat_image'][0] : ''; ?>"><br/>
        </div>
        <!--        <span class="description">--><?php //_e('Image for category: use full url with '); ?><!--</span>-->
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="cat_video_input"><?php _e('Category Video (Youtube/Vimeo)'); ?></label></th>
    <td>
       <div id="custom_cat_video">
           <input type="text" name="cat_video" id="cat_video_input" style="margin-bottom: 20px" value="<?php echo isset($video) ? $video:''; ?>">
           <span class="spinner" style="float: none"></span>
           <?php if(isset($video)){
               echo "<img src='{$this->getVideoFrame($video)}'>";
           } ?>
           <p class="hide-if-no-js">

               <a class="delete-custom-video button button-primary button-large <?php echo !isset($video) ? 'hidden':'';?>"
                  href="#">
                   <?php _e('Remove this Video') ?>
               </a>
           </p>
       </div>
    </td>

</tr>