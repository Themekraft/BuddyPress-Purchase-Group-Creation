<?php
/*
* Plugin Name: WooCommerce BP Group
* Plugin URI: https://oxygensoft.net/
* Description: WooCommerce BP Group is a custom plugin to work with WooCommerce
* Author: Faizan Gill
* Version: 1.0.6
* Text Domain: wc_bp_group
* Domain Path: /languages
* Author URI: https://oxygensoft.net/
*/

if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins'))) )
{
	include_once("woocommerce_field.php");
	include_once("buy_product_system.php");
	include_once("member_types.php");
	include_once("front_end.php");
}

?>