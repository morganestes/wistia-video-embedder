<?php
/**
 * Represents the view for the administration dashboard.
 * This is a subpage of the main admin page for the projects.
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
	<?php if ( isset( $_POST['updated'] ) ) { ?>
		<div id="message" class="updated">
			<p><strong><?php _e( 'Settings saved.' ) ?></strong></p>
		</div>
	<?php } ?>
	<form method="post" action="">
		<?php settings_fields( 'wistia-settings-projects' ); ?>
		<?php do_settings_sections( 'wistia-settings-projects' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="wistia_projects_update">Last Update</label></th>
				<td>
					<input type="text" readonly="" class="text" name="wistia_projects_update" value="<?php _e( get_option( 'wistia_projects_update' ) ); ?>" />
				</td>
			</tr>
		</table>
		<?php submit_button( 'Update List' ); ?>
	</form>

	<div id="wistia-projects">
		<h2>Projects List</h2>
		<?php WistiaVideoEmbedder::display_projects_list(); ?>
	</div>
</div>
