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

function set_user_meta_fields($product_id, $user_id)
{
    $allowed_group  = get_post_meta($product_id,"_allowed_group",true);
    update_user_meta( $user_id, "_allowed_group", $allowed_group);

    // update member type
    $member_type  = get_post_meta($product_id,"_member_type",true);
    update_user_meta( $user_id, "_member_type", $member_type);

    // update privacy options check
    $privacy_options_check  = get_post_meta($product_id,"_privacy_options_check",true);
    update_user_meta( $user_id, "_privacy_options_check", $privacy_options_check);
    // update privacy options
    $privacy_options  = get_post_meta($product_id,"_privacy_options",true);
    update_user_meta( $user_id, "_privacy_options", $privacy_options);

    // update group invitations check
    $group_invitations_check  = get_post_meta($product_id,"_group_invitations_check",true);
    update_user_meta( $user_id, "_group_invitations_check", $group_invitations_check);
    // update group invitations
    $group_invitations  = get_post_meta($product_id,"_group_invitations",true);
    update_user_meta( $user_id, "_group_invitations", $group_invitations);

    // update group post form check
    $group_post_form_check  = get_post_meta($product_id,"_group-post-form_check",true);
    update_user_meta( $user_id, "_group-post-form_check", $group_post_form_check);
    // update group post form
    $group_post_form  = get_post_meta($product_id,"_group-post-form",true);
    update_user_meta( $user_id, "_group-post-form", $group_post_form);

    // update group media check
    $group_media_check  = get_post_meta($product_id,"_group-media_check",true);
    update_user_meta( $user_id, "_group-media_check", $group_media_check);
    // update group media
    $group_media  = get_post_meta($product_id,"_group-media",true);
    update_user_meta( $user_id, "_group-media", $group_media);

    // update group albums check
    $group_albums_check  = get_post_meta($product_id,"_group-albums_check",true);
    update_user_meta( $user_id, "_group-albums_check", $group_albums_check);
    // update group albums
    $group_albums  = get_post_meta($product_id,"_group-albums",true);
    update_user_meta( $user_id, "_group-albums", $group_albums);

    // update group document check
    $group_document_check  = get_post_meta($product_id,"_group-document_check",true);
    update_user_meta( $user_id, "_group-document_check", $group_document_check);
    // update group document
    $group_document  = get_post_meta($product_id,"_group-document",true);
    update_user_meta( $user_id, "_group-document", $group_document);

    // update group document check
    $group_messages_check  = get_post_meta($product_id,"_group-messages_check",true);
    update_user_meta( $user_id, "_group-messages_check", $group_messages_check);
    // update group document
    $group_messages  = get_post_meta($product_id,"_group-messages",true);
    update_user_meta( $user_id, "_group-messages", $group_messages);

    // Forum allowed
    $forum_allowed  = get_post_meta($product_id,"_forum_allowed",true);
    update_user_meta( $user_id, "_forum_allowed", $forum_allowed);

    // update photo allowed
    $photo_allowed  = get_post_meta($product_id,"_photo_allowed",true);
    update_user_meta( $user_id, "_photo_allowed", $photo_allowed);

    // update cover allowed
    $cover_allowed  = get_post_meta($product_id,"_cover_allowed",true);
    update_user_meta( $user_id, "_cover_allowed", $cover_allowed);

    // update invite allowed
    $invite_allowed  = get_post_meta($product_id,"_invite_allowed",true);
    update_user_meta( $user_id, "_invite_allowed", $invite_allowed);
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
    $member_options = array();
    $member_options['members']  = __( 'All group members', 'bp_group_pfl');
    $member_options['mods']     = __( 'Group admins and mods only', 'bp_group_pfl');
    $member_options['admins']   = __( 'Group admins only', 'bp_group_pfl');
    ?>
    <h3><?php _e("BuddyPress Allowed Group", "bp_group_pfl"); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="_allowed_group"><?php _e("Allowed Groups","bp_group_pfl"); ?></label></th>
            <td>
                <input type="text" name="_allowed_group" id="_allowed_group" value="<?php echo esc_attr( get_the_author_meta( '_allowed_group', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="_privacy_options_check"><?php _e("Enable Privacy Options","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_privacy_options_check" id="_privacy_options_check" <?php echo (esc_attr( get_the_author_meta( '_privacy_options_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <?php
                    $pr_options = array();
                    $pr_options['public']   = __( 'Public Group', 'bp_group_pfl');
                    $pr_options['private']  = __( 'Private Group', 'bp_group_pfl');
                    $pr_options['hidden']   = __( 'Hidden Group', 'bp_group_pfl');
                ?>
                <select name="_privacy_options" id="_privacy_options">
                    <?php
                    foreach ($pr_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_privacy_options', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group_invitations_check"><?php _e("Enable Group Invitations","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group_invitations_check" id="_group_invitations_check" <?php echo (esc_attr( get_the_author_meta( '_group_invitations_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group_invitations" id="_group_invitations">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group_invitations', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group-post-form_check"><?php _e("Enable Activity Feeds","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group-post-form_check" id="_group-post-form_check" <?php echo (esc_attr( get_the_author_meta( '_group-post-form_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group-post-form" id="_group-post-form">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group-post-form', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group-media_check"><?php _e("Enable Upload Photos","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group-media_check" id="_group-media_check" <?php echo (esc_attr( get_the_author_meta( '_group-media_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group-media" id="_group-media">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group-media', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group-albums_check"><?php _e("Enable Albums Creation","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group-albums_check" id="_group-albums_check" <?php echo (esc_attr( get_the_author_meta( '_group-albums_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group-albums" id="_group-albums">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group-albums', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group-document_check"><?php _e("Enable Documents Upload","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group-document_check" id="_group-document_check" <?php echo (esc_attr( get_the_author_meta( '_group-document_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group-document" id="_group-document">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group-document', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_group-messages_check"><?php _e("Enable Group Invitations","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_group-messages_check" id="_group-messages_check" <?php echo (esc_attr( get_the_author_meta( '_group-messages_check', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
                <select name="_group-messages" id="_group-messages">
                    <?php
                    foreach ($member_options as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo (esc_attr( get_the_author_meta( '_group-messages', $user->ID ) ) == $key ? "selected" : ""); ?>>
                            <?php echo $value; ?>
                        </option>
                        <?php                        
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="_forum_allowed"><?php _e("Enable Forum","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_forum_allowed" id="_forum_allowed" <?php echo (esc_attr( get_the_author_meta( '_forum_allowed', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
        <tr>
            <th><label for="_photo_allowed"><?php _e("Enable Photo","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_photo_allowed" id="_photo_allowed" <?php echo (esc_attr( get_the_author_meta( '_photo_allowed', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
        <tr>
            <th><label for="_cover_allowed"><?php _e("Enable Cover","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_cover_allowed" id="_cover_allowed" <?php echo (esc_attr( get_the_author_meta( '_cover_allowed', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
        <tr>
            <th><label for="_invite_allowed"><?php _e("Invite Allowed at group creation","bp_group_pfl"); ?></label></th>
            <td>
                <input type="checkbox" name="_invite_allowed" id="_invite_allowed" <?php echo (esc_attr( get_the_author_meta( '_invite_allowed', $user->ID ) ) == "yes" ? "checked" : ""); ?>/>
            </td>
        </tr>
    </table>
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

    if (isset($_POST['_allowed_group'])) {
        update_user_meta( $user_id, '_allowed_group', $_POST['_allowed_group'] );
    }

    $privacy_options_check = isset( $_POST['_privacy_options_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_privacy_options_check', $privacy_options_check );
    if (isset($_POST['_privacy_options'])) {
        update_user_meta( $user_id, '_privacy_options', $_POST['_privacy_options'] );
    }

    $group_invitations_check = isset( $_POST['_group_invitations_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group_invitations_check', $group_invitations_check );
    if (isset($_POST['_group_invitations'])) {
        update_user_meta( $user_id, '_group_invitations', $_POST['_group_invitations'] );
    }

    $group_post_form_check = isset( $_POST['_group-post-form_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group-post-form_check', $group_post_form_check );
    if (isset($_POST['_group-post-form'])) {
        update_user_meta( $user_id, '_group-post-form', $_POST['_group-post-form'] );
    }

    $group_media_check = isset( $_POST['_group-media_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group-media_check', $group_media_check );
    if (isset($_POST['_group-media'])) {
        update_user_meta( $user_id, '_group-media', $_POST['_group-media'] );
    }

    $group_albums_check = isset( $_POST['_group-albums_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group-albums_check', $group_albums_check );
    if (isset($_POST['_group-albums'])) {
        update_user_meta( $user_id, '_group-albums', $_POST['_group-albums'] );
    }

    $group_document_check = isset( $_POST['_group-document_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group-document_check', $group_document_check );
    if (isset($_POST['_group-document'])) {
        update_user_meta( $user_id, '_group-document', $_POST['_group-document'] );
    }

    $group_messages_check = isset( $_POST['_group-messages_check'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_group-messages_check', $group_messages_check );
    if (isset($_POST['_group-messages'])) {
        update_user_meta( $user_id, '_group-messages', $_POST['_group-messages'] );
    }

    $forum_allowed = isset( $_POST['_forum_allowed'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_forum_allowed', $forum_allowed );

    $photo_allowed = isset( $_POST['_photo_allowed'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_photo_allowed', $photo_allowed );

    $cover_allowed = isset( $_POST['_cover_allowed'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_cover_allowed', $cover_allowed );

    $invite_allowed = isset( $_POST['_invite_allowed'] ) ? 'yes' : 'no';
    update_user_meta( $user_id, '_invite_allowed', $invite_allowed );
}

function update_group_meta_fn($group_id) {
    global $wpdb; 
    $user_ID                    = get_current_user_id();
    $privacy_options_check      = get_user_meta($user_ID, "_privacy_options_check", true);
    $privacy_options            = get_user_meta($user_ID, "_privacy_options", true);
    $group_invitations_check    = get_user_meta($user_ID, "_group_invitations_check", true);
    $group_invitations          = get_user_meta($user_ID, "_group_invitations", true);
    $group_post_form_check      = get_user_meta($user_ID, "_group-post-form_check", true);
    $group_post_form            = get_user_meta($user_ID, "_group-post-form", true);
    $group_media_check          = get_user_meta($user_ID, "_group-media_check", true);
    $group_media                = get_user_meta($user_ID, "_group-media", true);
    $group_albums_check         = get_user_meta($user_ID, "_group-albums_check", true);
    $group_albums               = get_user_meta($user_ID, "_group-albums", true);
    $group_document_check       = get_user_meta($user_ID, "_group-document_check", true);
    $group_document             = get_user_meta($user_ID, "_group-document", true);
    $group_messages_check       = get_user_meta($user_ID, "_group-messages_check", true);
    $group_messages             = get_user_meta($user_ID, "_group-messages", true);
    $forum_allowed              = get_user_meta($user_ID, "_forum_allowed", true);
    $photo_allowed              = get_user_meta($user_ID, "_photo_allowed", true);
    $cover_allowed              = get_user_meta($user_ID, "_cover_allowed", true);
    $invite_allowed             = get_user_meta($user_ID, "_invite_allowed", true);

    $delete_q  = " DELETE FROM ".$wpdb->prefix."bp_groups_groupmeta 
                WHERE 
                    `group_id` = '".$group_id."' AND
                    (
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

/*------------Before add to cart validation-------------*/

function add_the_product_validation( $passed ) { 
    $passed = check_user_allowed_groups();
    if ($passed == false) {
        wc_add_notice( __( 'You already have credits to create group. Please contact admin for buying this product', 'bp_group_pfl' ), 'error' );
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'add_the_product_validation', 10, 5 ); 

add_action( 'woocommerce_after_checkout_validation', 'misha_validate_fname_lname', 10, 2);
 
function misha_validate_fname_lname( $fields, $errors ){
    $passed = check_user_allowed_groups();
    if ($passed == false) {
        $errors->add( 'validation', 'You already have credits to create group. Please contact admin for buying this product', 'bp_group_pfl' );
    }
}

function check_user_allowed_groups(){
    $passed = true;
    if ( is_user_logged_in() ) {
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

        if($allowed_group > $group_cnt) {
            $passed = false;
        }
    }
    return $passed;
}

function tested_fn()
{
    if (@$_GET['type'] == "IamDev") {
        
        update_group_meta_fn(1);
        exit();
    }
}
add_action( 'init', 'tested_fn' );
?>