<?php
/**
 * Represents the view for the administration dashboard.
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package    WistiaVideoEmbedder
 * @author     Morgan Estes <morgan.estes@gmail.com>
 * @license    GPL-2.0+
 * @link       TODO
 * @since      1.0.0
 */
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="">
		<?php settings_fields( 'wistia-settings-api-key' ); ?>
		<?php do_settings_sections( 'wistia-settings-api-key' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="wistia_api_key">API Key</label></th>
				<td>
					<input type="text" class="regular-text code" name="wistia_api_key" value="<?php _e( get_option( 'wistia_api_key' ) ); ?>" />
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>

	<div id="wistia-account">
		<h3>Account Info</h3>
		<?php $account = json_decode( get_option( 'wistia_account' ), true ); ?>
		<ul>
			<li title="ID">ID: <?php _e( $account['id'] ); ?></li>
			<li title="Name">Name: <?php _e( $account['name'] ); ?></li>
			<li title="URL">URL: <a href="<?php _e( $account['url'] ); ?>"><?php _e( $account['url'] ); ?></a></li>
		</ul>
	</div>
</div>


