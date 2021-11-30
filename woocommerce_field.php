<?php

add_action( 'plugins_loaded', 'wcpt_register_bp_group' );

function wcpt_register_bp_group () {

	class WC_Product_bp_group extends WC_Product {

		public function __construct( $product ) {
			$this->product_type = 'bp_group'; // name of your custom product type
			parent::__construct( $product );
			// add additional functions here
		}
    }
}

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
		'class'  => ('show_if_bp_group'),
	);
	return $tabs;
}



add_action( 'woocommerce_product_data_panels', 'wcpt_bp_group_options_product_tab_content' );

function wcpt_bp_group_options_product_tab_content() {
	
	

	?><div id='bp_group_options' class='panel woocommerce_options_panel'><?php
		?>
		<div class='options_group'>

		<?php
		
 
			woocommerce_wp_checkbox( array(
				'id' 	=> '_enable_bp_type',
				'label' => __( 'Enable As BP Group', 'bp_group_pfl' ),
			) );

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

			$gi_options['members'] 	= __( 'All group members', 'bp_group_pfl');
			$gi_options['mods'] 	= __( 'Group admins and mods only', 'bp_group_pfl');
			$gi_options['admins'] 	= __( 'Group admins only', 'bp_group_pfl');

			woocommerce_wp_select( array(
			    'id'      => '_group_invitations',
			    'label'   => __( 'Default Group Invitation', 'bp_group_pfl' ),
				'description' => __( 'Select Group Invitation', 'bp_group_pfl' ),
				'desc_tip'    => true,
			    'options' => $gi_options,
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_photo_allowed',
				'label' => __( 'Enable Photo', 'bp_group_pfl' ),
			) );

			woocommerce_wp_checkbox( array(
				'id' 	=> '_cover_allowed',
				'label' => __( 'Enable Cover', 'bp_group_pfl' ),
			) );

			echo '</div>';
			echo '<div class="options_group">';

			woocommerce_wp_checkbox( array(
				'id' 	=> '_invite_allowed',
				'label' => __( 'Invite Allowed', 'bp_group_pfl' ),
			) );

			echo '</div>';
		?></div>
	</div><?php
}




add_action( 'woocommerce_process_product_meta', 'save_bp_group_options_field' );

function save_bp_group_options_field( $post_id ) {

	$enable_bp_type = isset( $_POST['_enable_bp_type'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_bp_type', $enable_bp_type );

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
	
	$photo_allowed = isset( $_POST['_photo_allowed'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_photo_allowed', $photo_allowed );
	
	$cover_allowed = isset( $_POST['_cover_allowed'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_cover_allowed', $cover_allowed );

	$invite_allowed = isset( $_POST['_invite_allowed'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_invite_allowed', $invite_allowed );

}


function lesson__add_to_content( $content ) {

	global $post;

    if( is_single() && 'sfwd-lessons' === $post->post_type  ) {
    	$my_id = get_the_ID();

		$product_ids   = get_post_meta($my_id, "product_ids", true);
		$product_ids   = unserialize($product_ids);

		if(is_user_logged_in())
		{
		
			$user_id=get_current_user_id();
		}
    	
    }
    //$content= '====';
   return $content;
}
add_filter('the_content', 'lesson__add_to_content');

?>