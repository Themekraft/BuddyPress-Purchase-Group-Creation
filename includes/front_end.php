<?php

function bp_user_can_create_groups2( $can_create, $restricted=false ){
    global $wpdb;
    
    $user_ID = get_current_user_id();
    
    /*$result = $wpdb->get_results("SELECT COUNT(*) AS t_groups FROM `".$wpdb->prefix."bp_groups`
                    WHERE `creator_id` = '".$user_ID."' ",ARRAY_A);
    $group_count = (isset($result[0]['t_groups']) && $result[0]['t_groups'] > 0 ? $result[0]['t_groups'] : 0);*/

    $package_count =  (int) get_user_meta($user_ID,"_package_count",true);
    $allowed_group = 0;
    for ($i = 1; $i <= $package_count; $i++) {
        $up_meta =  get_user_meta($user_ID,"_group_package_".$i,true);
        if(isset($up_meta['_allowed_group']) && $up_meta['_allowed_group'] > 0){
            $allowed_group += (int) $up_meta['_allowed_group'];
        }
    }
    //if($allowed_group <= $group_count) {
    if($allowed_group <= 0) {
        $can_create = false;
    }
    return $can_create;
}
add_filter( 'bp_user_can_create_groups', 'bp_user_can_create_groups2', 12, 2 );

function group_step_custom_js() {
    $user_ID  = get_current_user_id();
    $url_ar = explode("/", $_SERVER['REQUEST_URI']);
    $content  = '
    <script type="text/javascript">
        function set_group_package(){
            jQuery.ajax({
                type: "POST",
                url: "'.admin_url("admin-ajax.php").'",
                data: {"action":"save_group_package","package_id":jQuery("#group-package").val()},
                success: function(response)
                {
                    window.location.reload();
                }
            });
        }
        window.addEventListener("load", (event) => {';
        if (in_array("create", $url_ar) && in_array("step", $url_ar) && in_array("group-details", $url_ar)) {
            
            $group_package = array();
            $user_ID = get_current_user_id();
            $package_count =  (int) get_user_meta($user_ID,"_package_count",true);
            $allowed_group = 0;

            for ($i = 1; $i <= $package_count; $i++) {
                $up_meta =  get_user_meta($user_ID,"_group_package_".$i,true);
                if(isset($up_meta['_allowed_group']) && $up_meta['_allowed_group'] > 0){
                    $group_package[] = $i;
                }
            }
            $group_package = json_encode($group_package);
            $content  .= ' 
                var abc = '.$group_package.';
                jQuery("#group-name").after("<label for=\"group-package\">Group Package (required)</label><select id=\"group-package\" aria-required=\"true\" onchange=\"set_group_package()\"></select>");

                var select = document.getElementById("group-package");
                for(var i = 0; i < abc.length; i++) {
                    var opt = abc[i];
                    var el = document.createElement("option");
                    el.textContent = "Group Package "+opt;
                    el.value = opt;
                    select.appendChild(el);
                }
                ';
            if (session_id() && isset($_SESSION['package_id']) && !empty($_SESSION['package_id'])) {
                $content  .= ' 
                            var package_id = "'.$_SESSION['package_id'].'";
                            jQuery("#group-package").val(package_id);
                ';
            }
            
        }
        if (in_array("create", $url_ar) && session_id() && isset($_SESSION['package_id']) && !empty($_SESSION['package_id']) ) {
            
            $package_id = $_SESSION['package_id'];
            $group_meta = get_user_meta($user_ID,"_group_package_".$package_id,true);

            $privacy_options_check = isset($group_meta['_privacy_options_check']) ? $group_meta['_privacy_options_check'] : '';
            if ($privacy_options_check == "no") {
                $privacy_options = isset($group_meta['_privacy_options']) ? $group_meta['_privacy_options'] : '';
                if ($privacy_options != "") {
                    $content  .= 'jQuery("#group-status-'.$privacy_options.'").click();';
                }
                //$content  .= 'jQuery(".group-status-type").hide();';
                $content  .= '
                    var ov_height = jQuery(".group-status-type").height() + 16;
                    jQuery(".group-status-type").before("<div class=\"group-status-type-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height+"px;\"></div>");';
            }

            $group_invitations_check = isset($group_meta['_group_invitations_check']) ? $group_meta['_group_invitations_check'] : '';
            if ($group_invitations_check == "no") {
                $group_invitations = isset($group_meta['_group_invitations']) ? $group_meta['_group_invitations'] : '';
                if ($group_invitations != "") {
                    $content  .= 'jQuery("#group-invite-status-'.$group_invitations.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height2 = jQuery(".group-invitations").height() + 16;
                    jQuery(".group-invitations").before("<div class=\"group-invitations-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height2+"px;\"></div>");';
            }

            $group_post_form_check = isset($group_meta['_group-post-form_check']) ? $group_meta['_group-post-form_check'] : '';
            if ($group_post_form_check == "no") {
                $group_post_form = isset($group_meta['_group-post-form']) ? $group_meta['_group-post-form'] : '';
                if ($group_post_form != "") {
                    $content  .= 'jQuery("#group-activity-feed-status-'.$group_post_form.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height3 = jQuery(".group-post-form").height() + 16;
                    jQuery(".group-post-form").before("<div class=\"group-media-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height3+"px;\"></div>");';
            }

            $group_media_check = isset($group_meta['_group-media_check']) ? $group_meta['_group-media_check'] : '';
            if ($group_media_check == "no") {
                $group_media = isset($group_meta['_group-media']) ? $group_meta['_group-media'] : '';
                if ($group_media != "") {
                    $content  .= 'jQuery("#group-media-status-'.$group_media.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height4 = jQuery(".group-media").height() + 16;
                    jQuery(".group-media").before("<div class=\"group-post-form-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height4+"px;\"></div>");';
            }

            $group_albums_check = isset($group_meta['_group-albums_check']) ? $group_meta['_group-albums_check'] : '';
            if ($group_albums_check == "no") {
                $group_albums = isset($group_meta['_group-albums']) ? $group_meta['_group-albums'] : '';
                if ($group_albums != "") {
                    $content  .= 'jQuery("#group-albums-status-'.$group_albums.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height5 = jQuery(".group-albums").height() + 16;
                    jQuery(".group-albums").before("<div class=\"group-albums-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height5+"px;\"></div>");';
            }

            $group_document_check = isset($group_meta['_group-document_check']) ? $group_meta['_group-document_check'] : '';
            if ($group_document_check == "no") {
                $group_document = isset($group_meta['_group-document']) ? $group_meta['_group-document'] : '';
                if ($group_document != "") {
                    $content  .= 'jQuery("#group-document-status-'.$group_document.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height6 = jQuery(".group-document").height() + 16;
                    jQuery(".group-document").before("<div class=\"group-document-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height6+"px;\"></div>");';
            }

            $group_messages_check = isset($group_meta['_group-messages_check']) ? $group_meta['_group-messages_check'] : '';
            if ($group_messages_check == "no") {
                $group_messages = isset($group_meta['_group-messages']) ? $group_meta['_group-messages'] : '';
                if ($group_messages != "") {
                    $content  .= 'jQuery("#group-messages-status-'.$group_messages.'").click();';
                }

                //$content  .= 'jQuery(".group-invitations").hide();';
                $content  .= '
                    var ov_height7 = jQuery(".group-messages").height() + 16;
                    jQuery(".group-messages").before("<div class=\"group-messages-overlay\" style=\"background-color: #fff;position: absolute;z-index: 1;width: 100%;opacity: 0.5;height: "+ov_height7+"px;\"></div>");';
            }

            $invite_allowed = isset($group_meta['_invite_allowed']) ? $group_meta['_invite_allowed'] : '';
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
    $content  .= '
        });
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
    if ( session_id() && isset($_SESSION['package_id']) && !empty($_SESSION['package_id']) ) {
        $package_id = $_SESSION['package_id'];
        $group_meta = get_user_meta($user_ID,"_group_package_".$package_id,true);
        
        $invite_allowed = isset($group_meta['_invite_allowed']) ? $group_meta['_invite_allowed'] : 'no';
        $privacy_options_check = isset($group_meta['_privacy_options_check']) ? $group_meta['_privacy_options_check'] : 'no';
        $group_invitations_check = isset($group_meta['_group_invitations_check']) ? $group_meta['_group_invitations_check'] : 'no';

        if ($privacy_options_check == "no" && $group_invitations_check == "no") {
            unset($steps['group-settings']);
        }
        if ($invite_allowed == "no") {
            $steps['group-invites']['name'] = "Finish";
        }
    }
    return $steps;
}

?>