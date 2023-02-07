<?php
/**
 * Custom admin css field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$custom_css = isset( $settings['custom_admin_css'] ) ? $settings['custom_admin_css'] : false;

	?>

	<textarea id="udb-custom-admin-css" class="widefat textarea udb-custom-css" name="udb_settings[custom_admin_css]"><?php echo $custom_css; ?></textarea>

	<?php

};
