<?php
// create custom plugin settings menu
add_action('admin_menu', 'square_for_atum_create_menu');

function square_for_atum_create_menu() {

	//create new top-level menu
	add_menu_page('Square For Atum Settings', 'Square For Atum Settings', 'administrator', __FILE__, 'square_for_atum_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_square_for_atum_settings' );
}


function register_square_for_atum_settings() {
	//register our settings
	register_setting( 'square-for-atum-settings-group', 'api_key' );
	register_setting( 'square-for-atum-settings-group', 'unsynced_inventory_name' );
	register_setting( 'square-for-atum-settings-group', 'synced_inventory_name' );
}

function square_for_atum_settings_page() {
?>
<div class="wrap">
<!-- <h1>Square For ATUM</h1> -->

<form method="post" action="options.php">
    <?php settings_fields( 'square-for-atum-settings-group' ); ?>
    <?php do_settings_sections( 'square-for-atum-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API Key</th>
        <td>
          <p>Your Square developer application Access token. (Found by going to the Square developer dashboard > your application > credentials.)</p>
          <input type="text" name="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Unsynchronized Inventory Name</th>
        <td>
          <p>This should be the exact name of the inventory that needs to be exhausted before the Square inventory stock is reduced. (For example 'Warehouse').</p>
          <input type="text" name="unsynced_inventory_name" value="<?php echo esc_attr( get_option('unsynced_inventory_name') ); ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Square Synchonized Inventory Name</th>
        <td>
          <p>This should be the exact name of the inventory you will be creating with ATUM that you would like to sync with your Square stock.</p>
          <input type="text" name="synced_inventory_name" value="<?php echo esc_attr( get_option('synced_inventory_name') ); ?>" />
        </td>
        </tr>

    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
