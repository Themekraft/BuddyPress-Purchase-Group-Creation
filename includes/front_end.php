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
    $url_ar = explode("/", $_SERVER['REQUEST_URI']);
    $content  = '<script type="text/javascript">
                    window.addEventListener("load", (event) => {
                ';
    if (in_array("create", $url_ar)) {
        
        $privacy_options_check = get_user_meta($user_ID, "_privacy_options_check", true);
        if ($privacy_options_check == "no") {
            $privacy_options = get_user_meta($user_ID, "_privacy_options", true);
            if ($privacy_options != "") {
                $content  .= 'jQuery("#group-status-'.$privacy_options.'").click();';
            }
            //$content  .= 'jQuery(".group-status-type").hide();';
            $content  .= '
                var ov_height = jQuery(".group-status-type").height() + 16;
                jQuery(".group-status-type").before("<div class=\"group-status-type-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height+"px;\"></div>");';
        }

        $group_invitations_check = get_user_meta($user_ID, "_group_invitations_check", true);
        if ($group_invitations_check == "no") {
            $group_invitations = get_user_meta($user_ID, "_group_invitations", true);
            if ($group_invitations != "") {
                $content  .= 'jQuery("#group-invite-status-'.$group_invitations.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height2 = jQuery(".group-invitations").height() + 16;
                jQuery(".group-invitations").before("<div class=\"group-invitations-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height2+"px;\"></div>");';
        }

        $group_post_form_check = get_user_meta($user_ID, "_group-post-form_check", true);
        if ($group_post_form_check == "no") {
            $group_post_form = get_user_meta($user_ID, "_group-post-form", true);
            if ($group_post_form != "") {
                $content  .= 'jQuery("#group-activity-feed-status-'.$group_post_form.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height3 = jQuery(".group-post-form").height() + 16;
                jQuery(".group-post-form").before("<div class=\"group-media-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height3+"px;\"></div>");';
        }

        $group_media_check = get_user_meta($user_ID, "_group-media_check", true);
        if ($group_media_check == "no") {
            $group_media = get_user_meta($user_ID, "_group-media", true);
            if ($group_media != "") {
                $content  .= 'jQuery("#group-media-status-'.$group_media.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height4 = jQuery(".group-media").height() + 16;
                jQuery(".group-media").before("<div class=\"group-post-form-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height4+"px;\"></div>");';
        }

        $group_albums_check = get_user_meta($user_ID, "_group-albums_check", true);
        if ($group_albums_check == "no") {
            $group_albums = get_user_meta($user_ID, "_group-albums", true);
            if ($group_albums != "") {
                $content  .= 'jQuery("#group-albums-status-'.$group_albums.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height5 = jQuery(".group-albums").height() + 16;
                jQuery(".group-albums").before("<div class=\"group-albums-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height5+"px;\"></div>");';
        }

        $group_document_check = get_user_meta($user_ID, "_group-document_check", true);
        if ($group_document_check == "no") {
            $group_document = get_user_meta($user_ID, "_group-document", true);
            if ($group_document != "") {
                $content  .= 'jQuery("#group-document-status-'.$group_document.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height6 = jQuery(".group-document").height() + 16;
                jQuery(".group-document").before("<div class=\"group-document-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height6+"px;\"></div>");';
        }

        $group_messages_check = get_user_meta($user_ID, "_group-messages_check", true);
        if ($group_messages_check == "no") {
            $group_messages = get_user_meta($user_ID, "_group-messages", true);
            if ($group_messages != "") {
                $content  .= 'jQuery("#group-messages-status-'.$group_messages.'").click();';
            }

            //$content  .= 'jQuery(".group-invitations").hide();';
            $content  .= '
                var ov_height7 = jQuery(".group-messages").height() + 16;
                jQuery(".group-messages").before("<div class=\"group-messages-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height7+"px;\"></div>");';
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
    }

    /*----------------Group Edit Steps------------------*/

    $group_slug = "admin-created-group";
    $step_name = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[5]) ? $url_ar[5] : "");
    $editor = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[4]) ? $url_ar[4] : "");
    $group = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[2]) ? $url_ar[2] : "");
    $group_slug = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[3]) ? $url_ar[3] : "");

    if ($editor == "admin" && $group == "organization-directory" && $step_name != "" ) {
        $privacy_options_check      = get_group_meta($group_slug,"_privacy_options_check");
        $group_invitations_check    = get_group_meta($group_slug,"_group_invitations_check");
        $photo_allowed              = get_group_meta($group_slug,"_photo_allowed");
        $forum_allowed              = get_group_meta($group_slug,"_forum_allowed");
        $cover_allowed              = get_group_meta($group_slug,"_cover_allowed");
        $invite_allowed             = get_group_meta($group_slug,"_invite_allowed");
        
        if ($forum_allowed == "no") {
            $content  .= 'jQuery("#forum-groups-li").hide();';
        }
        if ($step_name == "forum" && $forum_allowed == "no") {
            $content  .= 'jQuery("#group-settings-form").hide();';
        }
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
    } /*else if ($group == "organization-directory") {
        $invite_allowed             = get_group_meta($group_slug,"_invite_allowed");
        $step_name = (is_array($url_ar) && count($url_ar) > 0 && isset($url_ar[4]) ? $url_ar[4] : "");
        if ($invite_allowed == "no") {
            $content  .= 'jQuery("#invite-groups-li").hide();';
        }
        if ($step_name == "send-invites" && $invite_allowed == "no") {
            $content  .= 'jQuery("#invite-groups-li").hide();
            jQuery("#group-invites-container").hide();';
        }
    }*/


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

?>