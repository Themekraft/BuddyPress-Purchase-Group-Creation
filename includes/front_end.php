<?php

function bp_user_can_create_groups2( $can_create, $restricted=false ){
    global $wpdb;
    
    $user_ID = get_current_user_id();
    
    $result =  $wpdb->get_results(" SELECT COUNT(*) AS t_groups
                FROM `".$wpdb->prefix."bp_groups` a 
                    LEFT JOIN ".$wpdb->prefix."bp_groups_groupmeta b ON a.id=b.group_id
                WHERE `creator_id` = '".$user_ID."' AND 
                      `meta_key`   = 'group_complete' AND meta_value='1'
                ",ARRAY_A);

    $group_cnt = (isset($result[0]['t_groups']) && $result[0]['t_groups'] > 0 ? $result[0]['t_groups'] : 0);
    $allowed_group = (get_user_meta($user_ID, "_allowed_group", true));

    if($allowed_group <= $group_cnt) {
        $can_create = false;
    }
    return $can_create;
}
add_filter( 'bp_user_can_create_groups', 'bp_user_can_create_groups2', 12, 2 );

function group_step_custom_js() {
    $user_ID  = get_current_user_id();
    $content  = '<script type="text/javascript">
                    window.addEventListener("load", (event) => {
                ';
    $privacy_options_check = get_user_meta($user_ID, "_privacy_options_check", true);
    if ($privacy_options_check == "no") {
        $privacy_options = get_user_meta($user_ID, "_privacy_options", true);
        if ($privacy_options != "") {
            $content  .= 'jQuery("#group-status-'.$privacy_options.'").click();';
        }
        $content  .= 'jQuery(".group-status-type").hide();';
    }

    $group_invitations_check = get_user_meta($user_ID, "_group_invitations_check", true);
    if ($group_invitations_check == "no") {
        $group_invitations = get_user_meta($user_ID, "_group_invitations", true);
        if ($group_invitations != "") {
            $content  .= 'jQuery("#group-invite-status-'.$group_invitations.'").click();';
        }

        $content  .= 'jQuery(".group-invitations").hide();';
    }

    $invite_allowed = get_user_meta($user_ID, "_invite_allowed", true);
    if ($invite_allowed == "no") {
        $step = get_group_step();
        if ($step == "group-invites") {
            $content  .= '
            jQuery(".creation-step-name").hide();
            jQuery("#group-invites-container").hide();';
        }
    }

    /*----------------Group Edit Steps------------------*/

    $group_slug = "admin-created-group";
    $url_ar = explode("/", $_SERVER['REQUEST_URI']);
    $step_name = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[5]) ? $url_ar[5] : "");
    $editor = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[4]) ? $url_ar[4] : "");
    $group = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[2]) ? $url_ar[2] : "");
    $group_slug = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[3]) ? $url_ar[3] : "");

    if ($editor == "admin" && $group == "groups" && $step_name != "" ) {
        $privacy_options_check      = get_group_meta($group_slug,"_privacy_options_check");
        $group_invitations_check    = get_group_meta($group_slug,"_group_invitations_check");
        $photo_allowed              = get_group_meta($group_slug,"_photo_allowed");
        $cover_allowed              = get_group_meta($group_slug,"_cover_allowed");
        $invite_allowed             = get_group_meta($group_slug,"_invite_allowed");
        $invite_allowed             = get_group_meta($group_slug,"_invite_allowed");
        
        if ($invite_allowed == "no") {
            $content  .= 'jQuery("#invite-groups-li").hide();';
        }

        if ($privacy_options_check == "no" && $group_invitations_check == "no") {
            $content  .= 'jQuery("#group-settings-groups-li").hide();';
        }
        if ($step_name == "group-settings") {
            if ($privacy_options_check == "no") {
                $content  .= 'jQuery(".group-status-type").hide();';
            }

            if ($group_invitations_check == "no") {
                $content  .= 'jQuery(".group-invitations").hide();';
            }

            if ($privacy_options_check == "no" && $group_invitations_check == "no") {
                $content  .= 'jQuery("#group-settings-groups-li").hide();
                jQuery("#group-settings-form").hide();';
            }
        }

        if ($step_name == "group-avatar" && $photo_allowed == "no") {
            $content  .= 'jQuery("#group-avatar-groups-li").hide();
            jQuery("#group-settings-form").hide();';
        }
        else if ($photo_allowed == "no") {
            $content  .= 'jQuery("#group-avatar-groups-li").hide();';
        }

        if ($step_name == "group-cover-image" && $cover_allowed == "no") {
            $content  .= 'jQuery("#group-cover-image-groups-li").hide();
            jQuery("#group-settings-form").hide();';
        }
        else if ($cover_allowed == "no") {
            $content  .= 'jQuery("#group-avatar-groups-li").hide();';
        }

        if ($step_name == "send-invites" && $cover_allowed == "no") {
            $content  .= 'jQuery("#group-cover-image-groups-li").hide();
            jQuery("#group-settings-form").hide();';
        }
        else if ($cover_allowed == "no") {
            $content  .= 'jQuery("#group-cover-image").hide();';
        }
    } else if ($group == "groups") {
        $invite_allowed             = get_group_meta($group_slug,"_invite_allowed");
        $step_name = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[4]) ? $url_ar[4] : "");
        if ($invite_allowed == "no") {
            $content  .= 'jQuery("#invite-groups-li").hide();';
        }
        if ($step_name == "send-invites" && $invite_allowed == "no") {
            $content  .= 'jQuery("#invite-groups-li").hide();
            jQuery("#group-invites-container").hide();';
        }
    }


    $content .= '   });
                </script>';
    echo $content;
}

add_action( 'wp_head', 'group_step_custom_js' );

function get_group_step()
{
    $step_name = "";
    global $wp;
    $url = home_url( $wp->request );
    $url_ar = explode("/", home_url( $wp->request ));
    if (is_array($url_ar) && count($url_ar) > 0) {
        $count = count($url_ar) -1;
        $step_name = $url_ar[$count];
    }
    return $step_name;
}

add_filter( 'groups_create_group_steps', 'groups_create_group_steps_new', 11, 1 );
function groups_create_group_steps_new( $steps = array() ) 
{

    $user_ID  = get_current_user_id();
    $invite_allowed = get_user_meta($user_ID, "_invite_allowed", true);
    $privacy_options_check = get_user_meta($user_ID, "_privacy_options_check", true);
    $group_invitations_check = get_user_meta($user_ID, "_group_invitations_check", true);
    if ($privacy_options_check == "no" && $group_invitations_check == "no") {
        unset($steps['group-settings']);
    }
    if ($invite_allowed == "no") {
        $steps['group-invites']['name'] = "Finish";
    }
    return $steps;
}

function group_steps_allowed_fn()
{
    global $bp;
    $user_ID  = get_current_user_id();
    $photo_allowed = get_user_meta($user_ID, "_photo_allowed", true);
    $cover_allowed = get_user_meta($user_ID, "_cover_allowed", true);
    
    if ($photo_allowed == "no") {
        unset($bp->groups->group_creation_steps['group-avatar']);
    }

    if ($cover_allowed == "no") {
        unset($bp->groups->group_creation_steps['group-cover-image']);
    }
}
add_action('init','group_steps_allowed_fn');



?>