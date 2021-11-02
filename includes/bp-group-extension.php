<?php

/**
 * The bp_is_active( 'groups' ) check is recommended, to prevent problems
 * during upgrade or when the Groups component is disabled
 */


if ( bp_is_active( 'groups' ) ) :
echo 'snack22';
class BP_Purchase_Group_Creation extends BP_Group_Extension {
    /**
     * Here you can see more customization of the config options
     */
    function __construct() {
        $args = array(
            'slug' => 'bp-purchase-group-creation',
            'name' => 'BP Purchase Group Creation',
            'nav_item_position' => 105,
            'screens' => array(
                'edit' => array(
                    'name' => 'Purchase a group',
                    // Changes the text of the Submit button
                    // on the Edit page
                    'submit_text' => 'Purchase',
                ),
                'create' => array(
                    'position' => 100,
                ),
            ),
        );
        parent::init( $args );
    }

    function display( $group_id = NULL ) {
        $group_id = bp_get_group_id();
        echo 'Stay tuned. New Features will be added from trello soon :-)';
    }

    function settings_screen( $group_id = NULL ) {
        $setting = groups_get_groupmeta( $group_id, 'bp-purchase-group-creation-settings' );

        ?>
        Save your plugin setting here: <input type="text" name="bp-purchase-group-creation-settings" value="<?php echo esc_attr( $setting ) ?>" />
        <?php
    }

    function settings_screen_save( $group_id = NULL ) {
        $setting = isset( $_POST['bp-purchase-group-creation-settings'] ) ? $_POST['bp-purchase-group-creation-settings'] : '';
        groups_update_groupmeta( $group_id, 'bp-purchase-group-creation-settings', $setting );
    }

    /**
     * create_screen() is an optional method that, when present, will
     * be used instead of settings_screen() in the context of group
     * creation.
     *
     * Similar overrides exist via the following methods:
     *   * create_screen_save()
     *   * edit_screen()
     *   * edit_screen_save()
     *   * admin_screen()
     *   * admin_screen_save()
     */
    function create_screen( $group_id = NULL ) {
        $setting = groups_get_groupmeta( $group_id, 'bp-purchase-group-creation-settings' );

        ?>
        Welcome to your new group!
        Save your plugin setting here: <input type="text" name="bp-purchase-group-creation-settings" value="<?php echo esc_attr( $setting ) ?>" />
        <?php
    }

}
bp_register_group_extension( 'BP_Purchase_Group_Creation' );

endif;
