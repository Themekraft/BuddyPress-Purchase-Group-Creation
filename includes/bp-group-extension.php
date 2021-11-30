<?php

/**
 * The bp_is_active( 'groups' ) check is recommended, to prevent problems
 * during upgrade or when the Groups component is disabled
 */


if ( bp_is_active( 'groups' ) ) :
    
    if ( in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))))
    {
        include_once("woocommerce_field.php");
        include_once("buy_product_system.php");
        include_once("member_types.php");
        include_once("front_end.php");
    }

endif;
