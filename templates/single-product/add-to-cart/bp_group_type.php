<?php
/**
 * Simple custom product
 */
if ( ! defined( 'ABSPATH' ) ) { 
	exit;
}
global $product;
do_action( 'bp_group_before_add_to_cart_form' );  ?>

<form class="bp_group_cart" method="post" enctype='multipart/form-data'>	
	<?php
	    $id            =   get_the_ID(); 
	    $allowed_group =   (get_post_meta($id ,'_allowed_group',true) );
	
	?>
	<h4>
		<?php _e("No. Of Group Buy", "bp_group_pfl"); ?>
		<span style="color: red"><?php  echo ($allowed_group); ?></span>
	</h4>

	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php _e(esc_html($product->single_add_to_cart_text()), "bp_group_pfl"); ?></button>
</form>

<?php do_action( 'bp_group_after_add_to_cart_form' ); ?>