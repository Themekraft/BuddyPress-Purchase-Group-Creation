<?php
function wporg_custom_box_html( $post ) {
    //print_r($post->id);
    ?>
    <input type="hidden" name="_group_id" id="_group_id" value="<?php echo $post->id; ?>" />
    <table class="form-table">
        <tr>

            <th><label for="_privacy_options_check"><?php _e("Enable Privacy Options","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_privacy_options_check" id="_privacy_options_check" <?php echo ( get_group_meta($post->id,"_privacy_options_check")  == "yes" ? "checked" : ""); ?>/>
            
                <?php
                    $pr_options = array();
                    $pr_options['public']   = __( 'Public Group', 'bp_group_pfl');
                    $pr_options['private']  = __( 'Private Group', 'bp_group_pfl');
                    $pr_options['hidden']   = __( 'Hidden Group', 'bp_group_pfl');
                ?>
                <!-- <select name="_privacy_options" id="_privacy_options">
                    <?php
                    foreach ($pr_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (get_group_meta($post->id, '_privacy_options' ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select> -->
            </td>
            <th><label for="_group_invitations_check"><?php _e("Enable Group Invitations","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group_invitations_check" id="_group_invitations_check" <?php echo ( get_group_meta($post->id,"_group_invitations_check")  == "yes" ? "checked" : ""); ?>/>
                <?php
                    $gi_options = array();
                    $gi_options['members']  = __( 'All group members', 'bp_group_pfl');
                    $gi_options['mods']     = __( 'Group admins and mods only', 'bp_group_pfl');
                    $gi_options['admins']   = __( 'Group admins only', 'bp_group_pfl');
                ?>
                <!-- <select name="_group_invitations" id="_group_invitations">
                    <?php
                    foreach ($gi_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo ( get_group_meta($post->id, '_group_invitations' ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select> -->
            </td>
        </tr>
        <tr>
            <th><label for="_photo_allowed"><?php _e("Enable Photo","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_photo_allowed" id="_photo_allowed" <?php echo ( get_group_meta($post->id,"_photo_allowed")  == "yes" ? "checked" : ""); ?>/>
            </td>
            <th><label for="_cover_allowed"><?php _e("Enable Cover","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_cover_allowed" id="_cover_allowed"  <?php echo ( get_group_meta($post->id,"_cover_allowed")  == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
        <tr>
            <th><label for="_invite_allowed"><?php _e("Invite Allowed","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_invite_allowed" id="_invite_allowed"  <?php echo ( get_group_meta($post->id,"_invite_allowed")  == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
    </table>
    <?php

 
}

function bp_groups_admin_load2() {
    global $bp_groups_list_table;

    $doaction   = bp_admin_list_table_current_bulk_action();
    $min        = bp_core_get_minified_asset_suffix();

    if ( isset($_POST['_group_id']) && ! empty( $_POST['_group_id'] ) ) {
        
        $group_id = $_POST['_group_id'];

        $privacy_options_check = isset( $_POST['_privacy_options_check'] ) ? 'yes' : 'no';
        update_group_meta( $group_id, '_privacy_options_check', $privacy_options_check );

        if ( isset( $_POST['_privacy_options'] ) ) {
            update_group_meta($group_id,'_privacy_options',$_POST['_privacy_options']);
        }

        $group_invitations_check = isset( $_POST['_group_invitations_check'] ) ? 'yes' : 'no';
        update_group_meta( $group_id, '_group_invitations_check', $group_invitations_check );

        if ( isset( $_POST['_group_invitations'] ) ) {
            update_group_meta($group_id,'_group_invitations',$_POST['_group_invitations']);
        }
        
        $photo_allowed = isset( $_POST['_photo_allowed'] ) ? 'yes' : 'no';
        update_group_meta( $group_id, '_photo_allowed', $photo_allowed );
        
        $cover_allowed = isset( $_POST['_cover_allowed'] ) ? 'yes' : 'no';
        update_group_meta( $group_id, '_cover_allowed', $cover_allowed );

        $invite_allowed = isset( $_POST['_invite_allowed'] ) ? 'yes' : 'no';
        update_group_meta( $group_id, '_invite_allowed', $invite_allowed );

        //exit;
    }
  
    if ( 'edit' == $doaction && ! empty( $_GET['gid'] ) ) 
    {
        add_meta_box( 'bp_group_add_members2', _x( 'Manage Group Options', 'group admin edit screen22', 'buddypress' ), 'wporg_custom_box_html', get_current_screen()->id, 'normal', 'core' );
    } 

    
}
add_action( 'bp_groups_admin_load', 'bp_groups_admin_load2' );

function bbg_register_member_types_with_directory() {
    bp_register_member_type( 'student', array(
        'labels' => array(
            'name'          => 'Students',
            'singular_name' => 'Student',
        ),
        'has_directory' => 'custom-name'
    ) );
}



 function bp_groups_get_group_types_n($data){

       $data=array();
       return $data;
 }

 add_filter( 'bp_groups_get_group_types', 'bp_groups_get_group_types_n',12, 2 );



add_filter('the_content', 'remove_empty_tags_recursive', 20, 1);
function remove_empty_tags_recursive ($str, $repto = NULL) {
        $str = force_balance_tags($str);
        if (!is_string ($str) || trim ($str) == '')
        return $str;
        return preg_replace (
              '~\s?<p>(\s|&nbsp;)+</p>\s?~',
             !is_string ($repto) ? '' : $repto,
           $str
        );
}





?>