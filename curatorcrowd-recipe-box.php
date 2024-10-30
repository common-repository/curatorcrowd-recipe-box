<?php
/*
Plugin Name: CuratorCrowd Recipe Box
Description: The CuratorCrowd Recipe Box is an on-page central hub for your readers to save, manage, and share recipes. It was built specifically for food blogs and publishers to help increase user engagement and strengthen your relationship with your readers.
Version: 1.2.0
Author: American Hometown Media, Inc.
Plugin URI: https://console.americanhometownmedia.com/recipe-box-plugin
Author URI: https://www.americanhometownmedia.com
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}

define( 'CURATORCROWD_RECIPE_BOX_VERSION', '1.2.0' );
define( 'CURATORCROWD_RECIPE_BOX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once('class-curatorcrowd-recipe-box.php');
