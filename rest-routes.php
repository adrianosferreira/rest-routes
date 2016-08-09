<?php
/*
Plugin Name: Rest Routes
Plugin URI: http://www.restroutes.com
Description: Building custom endpoints for WP REST API made easy.
Author: WP Making
Version: 1.1
*/

define( 'WPRR_VERSION', '1.0' );
define( 'WPRR_INC_PATH', dirname( __FILE__ ) . '/inc' );
define( 'WPRR_PATH', dirname( __FILE__ ) );
define( 'WPRR_FOLDER', basename(WPRR_PATH) );
define( 'WPRR_URL', plugins_url() . '/'. WPRR_FOLDER );
define( 'TEXTDOMAIN', 'rest-routes' );

include_once( WPRR_INC_PATH . '/' . 'wprr-restroutes.class.php' );

$RestRoutes = new RestRoutes();
?>