<?php
/**
 * @package Categories_Video_And_Image
 * @version 1.0
 */
/*
Plugin Name: Categories Video And Image
Description: Plugin for add video and image to categories
Author: Vitaliy Pylypenko
Version: 1.0
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}
define('PLUGIN_DIR',plugin_dir_path( __FILE__ ));
define('PLUGIN_DIR_URL',plugin_dir_url( __FILE__ ));
define('PLUGIN_BASENAME',plugin_basename(__FILE__));
require_once plugin_dir_path( __FILE__ ) . 'core/Cvi.php';

function runCvi() {

    $cvi = new Cvi();
    $cvi->run();

}

runCvi();
