<?php
/*
Plugin Name: eShop Massive Editing
Plugin URI: https://github.com/mabullo/eShop-me
Description: Plugin for the rapid administration of the eshop's product 
Author: Marco Bullo
Version: 0.1
Author URI: 

Copyright 2012  MARCO BULLO  (email : mrcbullo@gmail.com)

This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

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