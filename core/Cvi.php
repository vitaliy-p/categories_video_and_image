<?php
class Cvi{
    protected $loader;
    protected $admin;
    protected $pluginSlug;
    protected $version;
    protected $youtubeKey;
    protected $vimeoKey;

    public function __construct() {

        $this->plugin_slug = 'cvi';
        $this->version = '1.0';
        $this->youtubeKey = get_option('youtube_api_key') ? get_option('youtube_api_key') :'AIzaSyAkYcO_wbmHZOegDAh1uRmIFTYmWXq_tvg';
        $this->vimeoKey = get_option('vimeo_api_key') ? get_option('vimeo_api_key'):'1879705f9d448768900f76618b9fd82a' ;
        $this->loadDependencies();
        $this->defineAdminHooks();

    }

    private function loadDependencies() {

        require_once PLUGIN_DIR . 'core/admin/CviAdmin.php';

        require_once PLUGIN_DIR . 'core/CviLoader.php';
        $this->loader = new CviLoader();
        $this->admin = new CviAdmin($this->getVersion());

    }
    public function addVideoImageToCategory($content){
        if(is_category()){
            $tag = get_queried_object();
            $tID = $tag->term_id;
            $img = '';
            $iframe = '';
            $imgID = get_term_meta($tID, 'cat_image', true);
            $video = get_term_meta($tID, 'cat_video', true);
            if($imgID != ''){
                $imgUrl = wp_get_attachment_image_src($imgID,'large');
            }

            if(stripos($video,'youtube')!== false){
                parse_str( parse_url( $video, PHP_URL_QUERY ), $vars );
                $vID = $vars['v'];
                $iframe = "<iframe width=\"1000\" height=\"500\" src=\"//www.youtube.com/embed/{$vID}\" frameborder=\"0\" allowfullscreen></iframe>";
            }
            if(stripos($video,'vimeo')!== false){
                $vID = substr(parse_url($video, PHP_URL_PATH), 1);
                $output = file_get_contents("https://api.vimeo.com/videos/$vID?access_token={$this->vimeoKey}");
                $output = json_decode($output);
                $iframe = $output->embed->html;
            }
            if(isset($imgUrl)){
                $img = "<img src='{$imgUrl[0]}' class='cat-image'>";
            }


            $output = $img.$iframe.$content;
            return $output;
        }
        else{
            return $content;
        }
    }

    private function defineAdminHooks() {

        $this->loader->addAction( 'admin_enqueue_scripts', $this->admin, 'enqueueScripts' );
        $this->loader->addAction( 'edit_category_form_fields', $this->admin, 'extraCategoryFields' );
        $this->loader->addAction( 'category_add_form_fields', $this->admin, 'addCategoryFields' );
        $this->loader->addAction( 'edited_category', $this->admin, 'saveCategoryFields' );
        $this->loader->addAction( 'create_category', $this->admin, 'saveCategoryFields' );
        $this->loader->addAction( 'admin_init', $this->admin, 'registerSettings' );
        $this->loader->addAction( 'admin_menu', $this->admin, 'settingsMenu' );
        $this->loader->addAction( 'wp_ajax_add_video_thumb', $this->admin, 'addVideoThumb' );
        $this->loader->addAction( 'wp_ajax_nopriv_add_video_thumb', $this->admin, 'addVideoThumb' );
        $this->loader->addFilter( 'category_description', $this, 'addVideoImageToCategory' );
        $this->loader->addFilter( 'plugin_action_links', $this, 'addActionLinks', 10, 5 );

    }

    function addActionLinks ( $actions, $plugin_file ) {
        static $plugin;

        if (!isset($plugin))
            $plugin = PLUGIN_BASENAME;
        if ($plugin == $plugin_file) {

            $settings = array('settings' => '<a href="'.get_admin_url(null, 'admin.php?page=cvi-settings').'">' . __('Settings', 'General') . '</a>');
            $actions = array_merge($settings, $actions);
        }

        return $actions;

    }

    public function run() {
        $this->loader->run();
    }

    public function getVersion() {
        return $this->version;
    }
}