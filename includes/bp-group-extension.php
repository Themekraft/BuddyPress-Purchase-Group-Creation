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

        function set_cookie_from_this_step($current_step)
        {
            global $bp;
            $final_steps = array();

            foreach($bp->groups->group_creation_steps as $key=>$value) {
                $final_steps[$value['position']] = $key;
            }
            ksort($final_steps);

            $steps_ar = array();
            foreach ($final_steps as $key => $value) {
                $steps_ar[] = $value;
                if ($value == $current_step) {
                    break;
                }
            }

            setcookie("bp_completed_create_steps", "", time()-3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
            setcookie( 'bp_completed_create_steps', base64_encode( json_encode($steps_ar) ), time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
        }

        function group_steps_allowed_fn()
        {
            global $bp;
            $user_ID  = get_current_user_id();
            $current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $url_ar = explode("/", $current_url);
            $current_step = $url_ar[count($url_ar)-2];

            $forum_allowed = get_user_meta($user_ID, "_forum_allowed", true);
            $photo_allowed = get_user_meta($user_ID, "_photo_allowed", true);
            $cover_allowed = get_user_meta($user_ID, "_cover_allowed", true);
            
            if (in_array("create", $url_ar)) {
                if ($current_step == "forum") {
                    if ($forum_allowed == "no") {
                        set_cookie_from_this_step("forum");

                        $url_ar[count($url_ar)-2] = "group-avatar";
                        $url = implode("/", $url_ar);
                        wp_redirect("//".$url);
                        exit();

                    }
                }

                if ($current_step == "group-avatar") {
                    if ($photo_allowed == "no") {
                        set_cookie_from_this_step("group-avatar");

                        $url_ar[count($url_ar)-2] = "group-cover-image";
                        $url = implode("/", $url_ar);
                        wp_redirect("//".$url);
                        exit();
                    }
                }

                if ($current_step == "group-cover-image") {
                    if ($cover_allowed == "no") {
                        set_cookie_from_this_step("group-cover-image");

                        $url_ar[count($url_ar)-2] = "group-invites";
                        $url = implode("/", $url_ar);
                        wp_redirect("//".$url);
                        exit();
                    }
                }

                if ($current_step == "group-invites") {
                    set_cookie_from_this_step("group-invites");
                }
                if ($forum_allowed == "no") {
                    unset($bp->groups->group_creation_steps['forum']);
                }
                if ($photo_allowed == "no") {
                    unset($bp->groups->group_creation_steps['group-avatar']);
                }
                if ($cover_allowed == "no") {
                    unset($bp->groups->group_creation_steps['group-cover-image']);
                }
            }
        }
        add_action( 'wp_head',"group_steps_allowed_fn",10,1);
    }

endif;
