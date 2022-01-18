<?php

add_filter( 'product_type_selector', 'wcpt_add_bp_group_type' );

function wcpt_add_bp_group_type ( $type ) {

	$type[ 'bp_group' ] = __( 'BP Group', 'bp_group_pfl' );
	return $type;
}

add_filter( 'woocommerce_product_data_tabs', 'bp_group_tab' );

function bp_group_tab( $tabs) {
	
	$tabs['bp_group'] = array(
		'label'	 => __( 'BP Group', 'bp_group' ),
		'target' => 'bp_group_options',
		'class'  => array('show_if_bp_group','active'),
	);
	return $tabs;
}



add_action( 'woocommerce_product_data_panels', 'wcpt_bp_group_options_product_tab_content' );

function wcpt_bp_group_options_product_tab_content() {
	
	

	?>
	<div id='bp_group_options' class='panel woocommerce_options_panel'><?php
		?>
		<div class='options_group'>

		<?php

			woocommerce_wp_text_input( array(
	       		'id'          => '_regular_price',
	       		'label'       => __( 'Regular price (₨)', 'bp_group_pfl' ),
	       		'placeholder' => '',
	       		'desc_tip'    => 'true',
	       		'description' => __( 'Enter Regular Price.', 'bp_group_pfl' ),
	        ));
	        woocommerce_wp_text_input( array(
	       		'id'          => '_sale_price',
	       		'label'       => __( 'Sale price (₨)', 'bp_group_pfl' ),
	       		'placeholder' => '',
	       		'desc_tip'    => 'true',
	       		'description' => __( 'Enter Sale Price.', 'bp_group_pfl' ),
	        ));

	        $get_terms = get_terms("bp_member_type", array('hide_empty' => false));

			$m_options[''] = __( 'Select Member Type', 'bp_group_pfl' );
			if(is_array($get_terms) && count($get_terms) > 0) {
				foreach ($get_terms as $value) {
			       $id  			=	$value->term_id; 
			       $m_options[$id] 	= __($value->name, 'bp_group_pfl' );
				}
			}

			echo '<div class="options_group">';
		    
		    woocommerce_wp_select( array(
		        'id'          => '_member_type',
		        'label'       => __( 'Member Type', 'bp_group_pfl' ),
		        'description' => __( 'Attach Member Type.', 'bp_group_pfl' ),
		        'desc_tip'    => true,
		        'options'     =>  $m_options
		    ));

			echo '</div>';

			$options['0'] = __( 'Allow Group', 'bp_group_pfl'); // default value
			$options[1] = "1";
			$options[2] = "2";
			$options[3] = "3";
			$options[4] = "4"; 
			$options[5] = "5"; 

			echo '<div class="options_group">';

			woocommerce_wp_select( array(
			    'id'      => '_allowed_group',
			    'label'   => __( 'Allowed Group', 'bp_group_pfl' ),
			    'options' =>  $options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_privacy_options_check',
				'label' => __( 'Enable Privacy Options', 'bp_group_pfl' ),
			) );

			$pr_options['public'] 	= __( 'Public Group', 'bp_group_pfl');
			$pr_options['private'] 	= __( 'Private Group', 'bp_group_pfl');
			$pr_options['hidden'] 	= __( 'Hidden Group', 'bp_group_pfl');

			woocommerce_wp_select( array(
			    'id'      => '_privacy_options',
			    'label'   => __( 'Default Privacy Option', 'bp_group_pfl' ),
				'description' => __( 'Select Privacy Option', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $pr_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group_invitations_check',
				'label' => __( 'Enable Group Invitations', 'bp_group_pfl' ),
			) );

			$member_options['members'] 	= __( 'All group members', 'bp_group_pfl');
			$member_options['mods'] 	= __( 'Group admins and mods only', 'bp_group_pfl');
			$member_options['admins'] 	= __( 'Group admins only', 'bp_group_pfl');

			woocommerce_wp_select( array(
			    'id'      => '_group_invitations',
			    'label'   => __( 'Which members of the group are allowed to invite others?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group-post-form_check',
				'label' => __( 'Enable Activity Feeds', 'bp_group_pfl' ),
			) );
			woocommerce_wp_select( array(
			    'id'      => '_group-post-form',
			    'label'   => __( 'Which members of the group are allowed to post into the activity feed?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group-media_check',
				'label' => __( 'Enable Upload Photos', 'bp_group_pfl' ),
			) );
			woocommerce_wp_select( array(
			    'id'      => '_group-media',
			    'label'   => __( 'Which members of the group are allowed to upload photos?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group-albums_check',
				'label' => __( 'Enable Albums Creation', 'bp_group_pfl' ),
			) );
			woocommerce_wp_select( array(
			    'id'      => '_group-albums',
			    'label'   => __( 'Which members of the group are allowed to create albums?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group-document_check',
				'label' => __( 'Enable Documents Upload', 'bp_group_pfl' ),
			) );
			woocommerce_wp_select( array(
			    'id'      => '_group-document',
			    'label'   => __( 'Which members of the group are allowed to upload documents?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_group-messages_check',
				'label' => __( 'Enable Messages Sending', 'bp_group_pfl' ),
			) );
			woocommerce_wp_select( array(
			    'id'      => '_group-messages',
			    'label'   => __( 'Which members of the group are allowed to send group messages?', 'bp_group_pfl' ),
				'description' => __( 'Select member', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $member_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_forum_allowed',
				'label' => __( 'Enable Forum', 'bp_group_pfl' ),
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_photo_allowed',
				'label' => __( 'Enable Photo', 'bp_group_pfl' ),
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_cover_allowed',
				'label' => __( 'Enable Cover', 'bp_group_pfl' ),
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_invite_allowed',
				'label' => __( 'Invite Allowed at group creation', 'bp_group_pfl' ),
				'description' => __( 'Allow Invitations at Group Creation', 'bp_group_pfl' ),
				'desc_tip'    => true,
			) );

			echo '</div>';
		?>

		</div>
	</div>
	<script type="text/javascript">
		jQuery(window).on('load',function () {
			if (jQuery("#product-type").val() == "bp_group") {
				jQuery(".bp_group_tab a").click();
			}
			jQuery("#product-type").on("change",function () {
				change_name();
				if (jQuery("#product-type").val() == "bp_group") {
					jQuery(".bp_group_tab a").click();
				}
			});
			function change_name() {
				if(jQuery("#product-type").val() == "bp_group") {
					jQuery("#bp_group_options #_regular_price").attr("name","_regular_price");
					jQuery("#bp_group_options #_sale_price").attr("name","_sale_price");
				}
				else {
					jQuery("#bp_group_options #_regular_price").attr("name","_regular_price_bp");
					jQuery("#bp_group_options #_sale_price").attr("name","_sale_price_bp");
				}
			}

			
		});
	</script>
	<?php
}




add_action( 'woocommerce_process_product_meta', 'save_bp_group_options_field' );

function save_bp_group_options_field( $post_id ) {

	if (isset($_POST['product-type']) && $_POST['product-type'] == "bp_group" ) {
		
		update_post_meta( $post_id, '_enable_bp_type', 'yes' );
		
		if ( isset( $_POST['_regular_price'] ) ) :
			update_post_meta( $post_id, '_regular_price', sanitize_text_field( $_POST['_regular_price'] ) );
		endif;

		if ( isset( $_POST['_sale_price'] ) ) :
			update_post_meta($post_id,'_sale_price',sanitize_text_field($_POST['_sale_price']));
		endif;


		if ( isset( $_POST['_allowed_group'] ) ) :
			update_post_meta($post_id,'_allowed_group',maybe_serialize($_POST['_allowed_group']));
		endif;

		if ( isset( $_POST['_member_type'] ) ) :
			update_post_meta($post_id,'_member_type',maybe_serialize($_POST['_member_type']));
		endif;

		$privacy_options_check = isset( $_POST['_privacy_options_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_privacy_options_check', $privacy_options_check );
		
		if ( isset( $_POST['_privacy_options'] ) ) :
			update_post_meta($post_id,'_privacy_options',maybe_serialize($_POST['_privacy_options']));
		endif;

		$group_invitations_check = isset( $_POST['_group_invitations_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group_invitations_check', $group_invitations_check );

		if ( isset( $_POST['_group_invitations'] ) ) :
			update_post_meta($post_id,'_group_invitations',maybe_serialize($_POST['_group_invitations']));
		endif;

		$group_post_form_check = isset( $_POST['_group-post-form_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group-post-form_check', $group_post_form_check);

		if ( isset( $_POST['_group-post-form'] ) ) :
			update_post_meta($post_id,'_group-post-form',maybe_serialize($_POST['_group-post-form']));
		endif;

		$group_media_check = isset( $_POST['_group-media_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group-media_check', $group_media_check );

		if ( isset( $_POST['_group-media'] ) ) :
			update_post_meta($post_id,'_group-media',maybe_serialize($_POST['_group-media']));
		endif;

		$group_albums_check = isset( $_POST['_group-albums_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group-albums_check', $group_albums_check );

		if ( isset( $_POST['_group-albums'] ) ) :
			update_post_meta($post_id,'_group-albums',maybe_serialize($_POST['_group-albums']));
		endif;

		$group_document_check = isset( $_POST['_group-document_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group-document_check', $group_document_check );

		if ( isset( $_POST['_group-document'] ) ) :
			update_post_meta($post_id,'_group-document',maybe_serialize($_POST['_group-document']));
		endif;

		$group_messages_check = isset( $_POST['_group-messages_check'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_group-messages_check', $group_messages_check );

		if ( isset( $_POST['_group-messages'] ) ) :
			update_post_meta($post_id,'_group-messages',maybe_serialize($_POST['_group-messages']));
		endif;

		$forum_allowed = isset( $_POST['_forum_allowed'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_forum_allowed', $forum_allowed );
		
		$photo_allowed = isset( $_POST['_photo_allowed'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_photo_allowed', $photo_allowed );
		
		$cover_allowed = isset( $_POST['_cover_allowed'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_cover_allowed', $cover_allowed );

		$invite_allowed = isset( $_POST['_invite_allowed'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_invite_allowed', $invite_allowed );

	}

}



?>