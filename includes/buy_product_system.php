<?php 
add_action("woocommerce_single_product_summary", "bp_group_type_template", 60);

function bp_group_type_template()
{
    global $product;
    if ("bp_group" == $product->get_type()) {
        $template_path = plugin_dir_path(__FILE__) . "templates/";
        wc_get_template(
            "single-product/add-to-cart/bp_group_type.php",
            "",
            "",
            trailingslashit($template_path)
        );
    }
}

add_filter("woocommerce_add_cart_item_data","bp_group_add_on_cart_item_data",10,2);
function bp_group_add_on_cart_item_data($cart_item, $product_id)
{
    $allowed_group = get_post_meta($product_id, "_allowed_group", true);
    $cart_item["allowed_group"] = $allowed_group;
    return $cart_item;
}

add_filter("woocommerce_get_item_data","bp_group_add_on_display_cart",10,2);

function bp_group_add_on_display_cart($data, $cart_item)
{
    if (isset($cart_item["allowed_group"])) {
        $allowed_group = sanitize_text_field($cart_item["allowed_group"]);  
        $content = $allowed_group;
        $data[]  = [
            "name" => "No. of group buy",
            "value" => $content,
        ];
    }
    return $data;
}

add_action("woocommerce_add_order_item_meta","bp_group_add_on_order_item_meta",10,2);

function bp_group_add_on_order_item_meta($item_id, $values)
{
    if (!empty($values["allowed_group"])) {
        wc_add_order_item_meta(
            $item_id,
            "No. of group buy",
            $values["allowed_group"],
            true
        );
    }
}


add_action("woocommerce_order_status_changed","woocommerce_order_status_changed_fn",10,3);
function woocommerce_order_status_changed_fn($order_id, $old_status, $new_status)
{
    $order = wc_get_order($order_id);
    //$temp_user_id = $order->get_meta('temp_user_id') ;


    if ($new_status == "completed") 
    {
    
        $user_id      = $order->user_id;

        if($user_id>0) 
        {
            foreach ($order->get_items() as $item) 
            {
                $product_id = $item["product_id"];
                set_user_meta_fields($product_id, $user_id);
            }
        }
      
    }
}

function set_user_meta_fields($product_id, $user_id) {
    $allowed_group           = get_post_meta($product_id,"_allowed_group",true);
    $member_type             = get_post_meta($product_id,"_member_type",true);
    $privacy_options_check   = get_post_meta($product_id,"_privacy_options_check",true);
    $privacy_options         = get_post_meta($product_id,"_privacy_options",true);
    $group_invitations_check = get_post_meta($product_id,"_group_invitations_check",true);
    $group_invitations       = get_post_meta($product_id,"_group_invitations",true);
    $group_post_form_check   = get_post_meta($product_id,"_group-post-form_check",true);
    $group_post_form         = get_post_meta($product_id,"_group-post-form",true);
    $group_media_check       = get_post_meta($product_id,"_group-media_check",true);
    $group_media             = get_post_meta($product_id,"_group-media",true);
    $group_albums_check      = get_post_meta($product_id,"_group-albums_check",true);
    $group_albums            = get_post_meta($product_id,"_group-albums",true);
    $group_document_check    = get_post_meta($product_id,"_group-document_check",true);
    $group_document          = get_post_meta($product_id,"_group-document",true);
    $group_messages_check    = get_post_meta($product_id,"_group-messages_check",true);
    $group_messages          = get_post_meta($product_id,"_group-messages",true);
    $forum_allowed           = get_post_meta($product_id,"_forum_allowed",true);
    $photo_allowed           = get_post_meta($product_id,"_photo_allowed",true);
    $cover_allowed           = get_post_meta($product_id,"_cover_allowed",true);
    $invite_allowed          = get_post_meta($product_id,"_invite_allowed",true);

    $user_meta                             = array();
    $user_meta['product_id']               = $product_id;
    $user_meta['_allowed_group']           = $allowed_group;
    $user_meta['_member_type']             = $member_type;
    $user_meta['_privacy_options_check']   = $privacy_options_check;
    $user_meta['_privacy_options']         = $privacy_options;
    $user_meta['_group_invitations_check'] = $group_invitations_check;
    $user_meta['_group_invitations']       = $group_invitations;
    $user_meta['_group-post-form_check']   = $group_post_form_check;
    $user_meta['_group-post-form']         = $group_post_form;
    $user_meta['_group-media_check']       = $group_media_check;
    $user_meta['_group-media']             = $group_media;
    $user_meta['_group-albums_check']      = $group_albums_check;
    $user_meta['_group-albums']            = $group_albums;
    $user_meta['_group-document_check']    = $group_document_check;
    $user_meta['_group-document']          = $group_document;
    $user_meta['_group-messages_check']    = $group_messages_check;
    $user_meta['_group-messages']          = $group_messages;
    $user_meta['_forum_allowed']           = $forum_allowed;
    $user_meta['_photo_allowed']           = $photo_allowed;
    $user_meta['_cover_allowed']           = $cover_allowed;
    $user_meta['_invite_allowed']          = $invite_allowed;

    $previous_package_count =  get_user_meta($user_id,"_package_count",true);
    $previous_package_count = (!empty($previous_package_count) ? $previous_package_count : 0);
    $new_package_count = (int) $previous_package_count + 1;
    update_user_meta($user_id,"_package_count",$new_package_count);
    update_user_meta($user_id,"_group_package_".$new_package_count,$user_meta);
}


add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
    if (!is_user_logged_in()) {
        $order = wc_get_order( $order_id );
        $temp_user = get_session();
        $order->update_meta_data( 'temp_user_id',$temp_user );
        $order->save();
    }
} , 10, 2);

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { 
    $member_options = array(
                        'members' => __( 'All group members', 'bp_group_pfl'),
                        'mods'    => __( 'Group admins and mods only', 'bp_group_pfl'),
                        'admins'  => __( 'Group admins only', 'bp_group_pfl')
                    );
    $package_count =  (int) get_user_meta($user->ID,"_package_count",true);
    ?>
    <h3><?php _e("BuddyPress Purchased Group Packages", "bp_group_pfl"); ?></h3>
    <?php 
    if(!$package_count > 0){
        ?>
        <h4>No Package is Purchased</h4>
        <?php
    }
    for ($i = 1; $i <= $package_count; $i++) { 
        $up_meta = array();
        $up_meta =  get_user_meta($user->ID,"_group_package_".$i,true);
        ?>
        <h3><?php _e("Purchased Packages ".$i, "bp_group_pfl"); ?></h3>
        <table class="widefat fixed">
            <tr>
                <td>
                    <label for="_allowed_group_<?php echo $i; ?>"><?php _e("Allowed Groups","bp_group_pfl"); ?></label>
                    <input type="text" name="_allowed_group[<?php echo $i; ?>]" id="_allowed_group_<?php echo $i; ?>" value="<?php echo esc_attr($up_meta['_allowed_group']); ?>" class="" />
                </td>
                <td>
                    <input type="checkbox" name="_privacy_options_check[<?php echo $i; ?>]" id="_privacy_options_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_privacy_options_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_privacy_options_check_<?php echo $i; ?>"><?php _e("Enable Privacy Options","bp_group_pfl"); ?></label>
                    <br>
                    <?php
                        $pr_options = array(
                                            'public'  => __( 'Public Group', 'bp_group_pfl'),
                                            'private' => __( 'Private Group', 'bp_group_pfl'),
                                            'hidden'  => __( 'Hidden Group', 'bp_group_pfl')
                                        );
                    ?>
                    <select name="_privacy_options[<?php echo $i; ?>]" id="_privacy_options_<?php echo $i; ?>">
                        <?php
                        foreach ($pr_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_privacy_options']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_group_invitations_check[<?php echo $i; ?>]" id="_group_invitations_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group_invitations_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group_invitations_check_<?php echo $i; ?>"><?php _e("Enable Group Invitations","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group_invitations[<?php echo $i; ?>]" id="_group_invitations_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group_invitations']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_group-post-form_check[<?php echo $i; ?>]" id="_group-post-form_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group-post-form_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group-post-form_check_<?php echo $i; ?>"><?php _e("Enable Activity Feeds","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group-post-form[<?php echo $i; ?>]" id="_group-post-form_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group-post-form']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_group-media_check[<?php echo $i; ?>]" id="_group-media_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group-media_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group-media_check_<?php echo $i; ?>"><?php _e("Enable Upload Photos","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group-media[<?php echo $i; ?>]" id="_group-media_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group-media']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" name="_group-albums_check[<?php echo $i; ?>]" id="_group-albums_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group-albums_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group-albums_check_<?php echo $i; ?>"><?php _e("Enable Albums Creation","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group-albums[<?php echo $i; ?>]" id="_group-albums_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group-albums']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_group-document_check[<?php echo $i; ?>]" id="_group-document_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group-document_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group-document_check_<?php echo $i; ?>"><?php _e("Enable Documents Upload","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group-document[<?php echo $i; ?>]" id="_group-document_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group-document']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_group-messages_check[<?php echo $i; ?>]" id="_group-messages_check_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_group-messages_check']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_group-messages_check_<?php echo $i; ?>"><?php _e("Enable Group Messages","bp_group_pfl"); ?></label>
                    <br>
                    <select name="_group-messages[<?php echo $i; ?>]" id="_group-messages_<?php echo $i; ?>">
                        <?php
                        foreach ($member_options as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php echo (esc_attr($up_meta['_group-messages']) == $key ? "selected" : ""); ?>>
                                <?php echo $value; ?>
                            </option>
                            <?php                        
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="checkbox" name="_forum_allowed[<?php echo $i; ?>]" id="_forum_allowed_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_forum_allowed']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_forum_allowed_<?php echo $i; ?>"><?php _e("Enable Forum","bp_group_pfl"); ?></label>
                    <br>
                    <input type="checkbox" name="_photo_allowed[<?php echo $i; ?>]" id="_photo_allowed_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_photo_allowed']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_photo_allowed_<?php echo $i; ?>"><?php _e("Enable Photo","bp_group_pfl"); ?></label>
                </td>
                <td>
                    <input type="checkbox" name="_cover_allowed[<?php echo $i; ?>]" id="_cover_allowed_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_cover_allowed']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_cover_allowed_<?php echo $i; ?>"><?php _e("Enable Cover","bp_group_pfl"); ?></label>
                    <br>
                    <input type="checkbox" name="_invite_allowed[<?php echo $i; ?>]" id="_invite_allowed_<?php echo $i; ?>" <?php echo (esc_attr($up_meta['_invite_allowed']) == "yes" ? "checked" : ""); ?>/>
                    <label for="_invite_allowed_<?php echo $i; ?>"><?php _e("Invite Allowed","bp_group_pfl"); ?></label>
                </td>
            </tr>
        </table>
        <?php
    } ?>
    <?php 
}

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    $package_count =  (int) get_user_meta($user_id,"_package_count",true);
    for ($i = 1; $i <= $package_count; $i++) { 
        $up_meta = array();

        if (isset($_POST['_allowed_group'][$i])) {
            $up_meta['_allowed_group'] = $_POST['_allowed_group'][$i];
        }

        $privacy_options_check = isset( $_POST['_privacy_options_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_privacy_options_check'] = $privacy_options_check;
        if (isset($_POST['_privacy_options'][$i])) {
            $up_meta['_privacy_options'] = $_POST['_privacy_options'][$i];
        }

        $group_invitations_check = isset( $_POST['_group_invitations_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group_invitations_check'] = $group_invitations_check;
        if (isset($_POST['_group_invitations'][$i])) {
            $up_meta['_group_invitations'] = $_POST['_group_invitations'][$i];
        }

        $group_post_form_check = isset( $_POST['_group-post-form_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group-post-form_check'] = $group_post_form_check;
        if (isset($_POST['_group-post-form'][$i])) {
            $up_meta['_group-post-form'] = $_POST['_group-post-form'][$i];
        }

        $group_media_check = isset( $_POST['_group-media_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group-media_check'] = $group_media_check;
        if (isset($_POST['_group-media'][$i])) {
            $up_meta['_group-media'] = $_POST['_group-media'][$i];
        }

        $group_albums_check = isset( $_POST['_group-albums_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group-albums_check'] = $group_albums_check;
        if (isset($_POST['_group-albums'][$i])) {
            $up_meta['_group-albums'] = $_POST['_group-albums'][$i];
        }

        $group_document_check = isset( $_POST['_group-document_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group-document_check'] = $group_document_check;
        if (isset($_POST['_group-document'][$i])) {
            $up_meta['_group-document'] = $_POST['_group-document'][$i];
        }

        $group_messages_check = isset( $_POST['_group-messages_check'][$i] ) ? 'yes' : 'no';
        $up_meta['_group-messages_check'] = $group_messages_check;
        if (isset($_POST['_group-messages'][$i])) {
            $up_meta['_group-messages'] = $_POST['_group-messages'][$i];
        }

        $forum_allowed = isset( $_POST['_forum_allowed'][$i] ) ? 'yes' : 'no';
        $up_meta['_forum_allowed'] = $forum_allowed;

        $photo_allowed = isset( $_POST['_photo_allowed'][$i] ) ? 'yes' : 'no';
        $up_meta['_photo_allowed'] = $photo_allowed;

        $cover_allowed = isset( $_POST['_cover_allowed'][$i] ) ? 'yes' : 'no';
        $up_meta['_cover_allowed'] = $cover_allowed;

        $invite_allowed = isset( $_POST['_invite_allowed'][$i] ) ? 'yes' : 'no';
        $up_meta['_invite_allowed'] = $invite_allowed;

        update_user_meta($user_id,"_group_package_".$i,$up_meta);
    }

}

function update_group_meta_fn($group_id) {
    global $wpdb; 
    $user_ID                    = get_current_user_id();
    if(session_id() && isset($_SESSION['package_id']) && !empty($_SESSION['package_id']) ) {
        $package_id = $_SESSION['package_id'];
        $up_meta =  get_user_meta($user_ID,"_group_package_".$package_id,true);
        
        $package_product_id      = isset($up_meta['product_id']) ? $up_meta['product_id'] : '';
        $privacy_options_check      = isset($up_meta['_privacy_options_check']) ? $up_meta['_privacy_options_check'] : '';
        $privacy_options            = isset($up_meta['_privacy_options']) ? $up_meta['_privacy_options'] : '';
        $group_invitations_check    = isset($up_meta['_group_invitations_check']) ? $up_meta['_group_invitations_check'] : '';
        $group_invitations          = isset($up_meta['_group_invitations']) ? $up_meta['_group_invitations'] : '';
        $group_post_form_check      = isset($up_meta['_group-post-form_check']) ? $up_meta['_group-post-form_check'] : '';
        $group_post_form            = isset($up_meta['_group-post-form']) ? $up_meta['_group-post-form'] : '';
        $group_media_check          = isset($up_meta['_group-media_check']) ? $up_meta['_group-media_check'] : '';
        $group_media                = isset($up_meta['_group-media']) ? $up_meta['_group-media'] : '';
        $group_albums_check         = isset($up_meta['_group-albums_check']) ? $up_meta['_group-albums_check'] : '';
        $group_albums               = isset($up_meta['_group-albums']) ? $up_meta['_group-albums'] : '';
        $group_document_check       = isset($up_meta['_group-document_check']) ? $up_meta['_group-document_check'] : '';
        $group_document             = isset($up_meta['_group-document']) ? $up_meta['_group-document'] : '';
        $group_messages_check       = isset($up_meta['_group-messages_check']) ? $up_meta['_group-messages_check'] : '';
        $group_messages             = isset($up_meta['_group-messages']) ? $up_meta['_group-messages'] : '';
        $forum_allowed              = isset($up_meta['_forum_allowed']) ? $up_meta['_forum_allowed'] : '';
        $photo_allowed              = isset($up_meta['_photo_allowed']) ? $up_meta['_photo_allowed'] : '';
        $cover_allowed              = isset($up_meta['_cover_allowed']) ? $up_meta['_cover_allowed'] : '';
        $invite_allowed             = isset($up_meta['_invite_allowed']) ? $up_meta['_invite_allowed'] : '';

        $delete_q  = " DELETE FROM ".$wpdb->prefix."bp_groups_groupmeta 
                WHERE 
                    `group_id` = '".$group_id."' AND
                    (
                        `meta_key` = '_package_product_id' OR
                        `meta_key` = 'group_complete' OR
                        `meta_key` = '_privacy_options_check' OR
                        `meta_key` = '_privacy_options' OR
                        `meta_key` = '_group_invitations_check' OR
                        `meta_key` = '_group_invitations' OR
                        `meta_key` = '_group-post-form_check' OR
                        `meta_key` = '_group-post-form' OR
                        `meta_key` = '_group-media_check' OR
                        `meta_key` = '_group-media' OR
                        `meta_key` = '_group-albums_check' OR
                        `meta_key` = '_group-albums' OR
                        `meta_key` = '_group-document_check' OR
                        `meta_key` = '_group-document' OR
                        `meta_key` = '_group-messages_check' OR
                        `meta_key` = '_group-messages' OR
                        `meta_key` = '_forum_allowed' OR
                        `meta_key` = '_photo_allowed' OR
                        `meta_key` = '_cover_allowed' OR
                        `meta_key` = '_invite_allowed'
                    ) ";
        $wpdb->query($delete_q);

        $insert_q  = " INSERT INTO ".$wpdb->prefix."bp_groups_groupmeta  
                    (group_id, meta_key , meta_value)                     
                   VALUES
                    ('".$group_id."', 'group_complete', '1'),
                    ('".$group_id."', '_package_product_id', '".$package_product_id."'),
                    ('".$group_id."', '_privacy_options_check', '".$privacy_options_check."'),
                    ('".$group_id."', '_privacy_options' , '".$privacy_options."'),
                    ('".$group_id."', '_group_invitations_check' , '".$group_invitations_check."'),
                    ('".$group_id."', '_group_invitations' , '".$group_invitations."'),
                    ('".$group_id."', '_group-post-form_check' , '".$group_post_form_check."'),
                    ('".$group_id."', '_group-post-form' , '".$group_post_form."'),
                    ('".$group_id."', '_group-media_check' , '".$group_media_check."'),
                    ('".$group_id."', '_group-media' , '".$group_media."'),
                    ('".$group_id."', '_group-albums_check' , '".$group_albums_check."'),
                    ('".$group_id."', '_group-albums' , '".$group_albums."'),
                    ('".$group_id."', '_group-document_check' , '".$group_document_check."'),
                    ('".$group_id."', '_group-document' , '".$group_document."'),
                    ('".$group_id."', '_group-messages_check' , '".$group_messages_check."'),
                    ('".$group_id."', '_group-messages' , '".$group_messages."'),
                    ('".$group_id."', '_forum_allowed' , '".$forum_allowed."'),
                    ('".$group_id."', '_photo_allowed' , '".$photo_allowed."'),
                    ('".$group_id."', '_cover_allowed' , '".$cover_allowed."'),
                    ('".$group_id."', '_invite_allowed' , '".$invite_allowed."') 
                ";
        $wpdb->query($insert_q);

        if ($privacy_options_check == "no" && $privacy_options != "") {
            $wpdb->update($wpdb->prefix."bp_groups",array('status'=>$privacy_options),array('id'=>$group_id));
        }
        if ($group_invitations_check == "no" && $group_invitations != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_invitations),array('group_id'=>$group_id,'meta_key'=>'invite_status'));
        }

        if ($group_post_form_check == "no" && $group_post_form != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_post_form),array('group_id'=>$group_id,'meta_key'=>'activity_feed_status'));
        }

        if ($group_media_check == "no" && $group_media != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_media),array('group_id'=>$group_id,'meta_key'=>'media_status'));
        }

        if ($group_albums_check == "no" && $group_albums != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_albums),array('group_id'=>$group_id,'meta_key'=>'album_status'));
        }

        if ($group_document_check == "no" && $group_document != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_document),array('group_id'=>$group_id,'meta_key'=>'document_status'));
        }

        if ($group_messages_check == "no" && $group_messages != "") {
            $wpdb->update($wpdb->prefix."bp_groups_groupmeta",array('meta_value'=>$group_messages),array('group_id'=>$group_id,'meta_key'=>'message_status'));
        }
        if (isset($up_meta['_allowed_group']) && !empty($up_meta['_allowed_group']) && $up_meta['_allowed_group'] > 0) {
            $up_meta['_allowed_group'] = $up_meta['_allowed_group'] - 1;
            update_user_meta($user_ID,"_group_package_".$package_id,$up_meta);
        }
    }
}
add_action('groups_group_create_complete', 'update_group_meta_fn');

function get_group_meta($group_id,$meta_key="")
{
    global $wpdb;
    $group_meta = array();
    $result =  $wpdb->get_results(" SELECT * FROM `".$wpdb->prefix."bp_groups_groupmeta` WHERE `group_id` IN ( SELECT `id` FROM `".$wpdb->prefix."bp_groups` WHERE (`slug` = '".$group_id."' OR `id` = '".$group_id."') ) ",ARRAY_A);
    foreach ($result as $value) {
        $group_meta[$value['meta_key']] = $value['meta_value'];
    }
    if($meta_key != "" && isset($group_meta[$meta_key])) {
        return $group_meta[$meta_key];
    }else {
        return $group_meta;
    }
}

function update_group_meta($group_id,$meta_key,$meta_value)
{
    global $wpdb;
    $delete_q  = " DELETE FROM ".$wpdb->prefix."bp_groups_groupmeta 
                WHERE 
                    `group_id` = '".$group_id."' AND `meta_key` = '".$meta_key."' ";
    $wpdb->query($delete_q);

    $insert_q  = " INSERT INTO ".$wpdb->prefix."bp_groups_groupmeta 
                    (group_id, meta_key , meta_value) 
                   VALUES
                    ('".$group_id."', '".$meta_key."', '".$meta_value."') ";
    $wpdb->query($insert_q);
}
?>