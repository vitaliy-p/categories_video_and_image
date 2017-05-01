<?php

class CviAdmin{

    private $version;
    protected $youtubeKey;
    protected $vimeoKey;

    public function __construct( $version ) {
        $this->version = $version;
        $this->youtubeKey = get_option('youtube_api_key') ? get_option('youtube_api_key') :'AIzaSyAkYcO_wbmHZOegDAh1uRmIFTYmWXq_tvg';
        $this->vimeoKey = get_option('vimeo_api_key') ? get_option('vimeo_api_key'):'1879705f9d448768900f76618b9fd82a' ;
    }


    public function enqueueScripts() {
        wp_enqueue_media();
        wp_enqueue_script('cvi-admin', PLUGIN_DIR_URL.'js/admin.js', array('jquery'), '1.0.0', true);
        wp_localize_script('cvi-admin','ajax',array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cviAdmin')
        ));

    }

    function addCategoryFields($tag){
        include_once PLUGIN_DIR.'inc/add_category_extra_fields.php';
    }
    function extraCategoryFields( $tag ) {    //check for existing featured ID
        include_once PLUGIN_DIR.'inc/edit_category_extra_fields.php';
    }


    function saveCategoryFields( $term_id ) {
        $cat_image = isset($_POST['cat_image']) ? $_POST['cat_image']:'';
        $cat_video = (isset($_POST['cat_video']) && filter_var($_POST['cat_video'],FILTER_VALIDATE_URL) != false && $this->getVideoFrame($_POST['cat_video']) !== '') ? $_POST['cat_video']:'';
        update_term_meta($term_id,'cat_image',$cat_image);
        update_term_meta($term_id,'cat_video',$cat_video);


    }

    function settingsMenu() {
        //create new top-level menu
        add_menu_page('CVI Settings', 'CVI Settings', 'manage_options', 'cvi-settings', array($this, 'settingsPage'));
    }


    function settingsPage(){
        include_once PLUGIN_DIR.'inc/settings_page.php';
    }


    function registerSettings(){
        register_setting( 'cvi-group', 'vimeo_api_key' );
        register_setting( 'cvi-group', 'youtube_api_key' );
    }

    function addVideoThumb(){

        check_ajax_referer('cviAdmin');
        $url = filter_var($_POST['url'],FILTER_VALIDATE_URL);
        if ($url != false){
            $thumb_url = $this->getVideoFrame($url);
            if($thumb_url != null){
                wp_send_json($thumb_url);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }
    function getVideoFrame($url){
        $thumbnail_url = '';
        if(stripos($url,'youtu')!== false){
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

            $vID = $matches[1];
            $url = "https://www.googleapis.com/youtube/v3/videos?id={$vID}&key={$this->youtubeKey}&part=snippet,statistics&fields=items(id,snippet,statistics)";
            $output = file_get_contents($url);
            $output = json_decode($output);
            $thumbnail_url = $output->items[0]->snippet->thumbnails->medium->url;
        }
        if(stripos($url,'vimeo')!== false){
            $vID = (int) substr(parse_url($url, PHP_URL_PATH), 1);
            $output = file_get_contents("https://api.vimeo.com/videos/$vID?access_token={$this->vimeoKey}");
            $output = json_decode($output);
            $thumbnail_url = $output->pictures->sizes[2]->link;
        }

        return $thumbnail_url;
    }

}