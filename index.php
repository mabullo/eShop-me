<?php
/*
Plugin Name: eShop Massive Editing
Plugin URI: www.elinet.it
Description: Create a page,for the massive editing .
Author: Marco Bullo
Version: 0.1
Author URI: 

*/
include 'include/eShop-me.php';
load_plugin_textdomain('eShop-me', false, dirname( plugin_basename( __FILE__ ) ).'/lang' );
add_action('admin_menu', 'adm_editing');
add_action( 'admin_init', 'EshopMe::register_me_settings' );
function adm_editing(){	
	add_posts_page('Massive editing page', 'eshopMe', 'activate_plugins', 'eshopMe_page', 'EshopMe::view_editing_page');
	add_options_page( 'Setting editing', 'Setting_eShop-Me', 'read', 'eshopMe_settings', 'EshopMe::setting_page');	
}


?>