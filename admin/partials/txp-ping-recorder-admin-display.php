<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/admin/partials
 */

// Get the 5 most recently recorded pings.
global $wpdb;
$table_name = $wpdb->prefix . 'txp_ping_recorder';

$sql = "SELECT ip, created
		FROM {$table_name}
		ORDER BY created DESC
		LIMIT %d";

// @codingStandardsIgnoreStart
$pings = $wpdb->get_results( $wpdb->prepare( $sql, array( 5 ) ) );
// @codingStandardsIgnoreEnd
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- Main Content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<?php if ( false === is_ssl() ) : ?>
							<div class="notice notice-error">
								<h2><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Important Information', 'txp-ping-recorder' ); ?></h2>
								<p><?php esc_html_e( 'You are strongly encouraged to only use this plugin with sites that use SSL by default.', 'txp-ping-recorder' ); ?>
							</div>
						<?php endif ?>
						<form method="post" name="txp-ping-recorder" action="options.php">
							<?php settings_fields( 'txp-ping-recorder' ); ?>
							<?php $options = $this->validate( get_option( $this->plugin_name ) ); ?>
							<h2><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'General Settings', 'txp-ping-recorder' ); ?></h2>
							<div class="inside">
								<ul class="striped">
									<li>
										<!-- Secret Key -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Enter secret key.', 'txp-ping-recorder' ); ?></span></legend>
											<input type="text"
												 class="regular-text"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-secretkey"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[secretkey]"
												 value="<?php echo esc_html( $options['secretkey'] ); ?>"
												 spellcheck="false"
											/>
											<span><?php esc_html_e( 'Secret key used to secure pings.', 'txp-ping-recorder' ); ?></span>
											<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-secretkey-description">
												<?php esc_html_e( 'Empty this field to generate a new secret key.', 'txp-ping-recorder' ); ?>
											</p>
										</fieldset>
									</li>
									<li>
										<!-- SSH Username -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Enter SSH username.', 'txp-ping-recorder' ); ?></span></legend>
											<input type="text"
												 class="regular-text"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-username"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[username]"
												 value="<?php echo esc_html( $options['username'] ); ?>"
											/>
											<span><?php esc_html_e( 'SSH username.', 'txp-ping-recorder' ); ?></span>
											<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-username-description">
												<?php esc_html_e( 'The username used to access your home server via SSH.', 'txp-ping-recorder' ); ?>
											</p>
										</fieldset>
									</li>
									<li>
										<!-- SSH Port -->
										<fieldset>
											<legend class="screen-reader-text"><span><?php esc_html_e( 'Enter SSH port number.', 'txp-ping-recorder' ); ?></span></legend>
											<input type="text"
												 class="regular-text"
												 id="<?php echo esc_html( $this->plugin_name ); ?>-port"
												 name="<?php echo esc_html( $this->plugin_name ); ?>[port]"
												 value="<?php echo esc_html( $options['port'] ); ?>"
											/>
											<span><?php esc_html_e( 'SSH port number.', 'txp-ping-recorder' ); ?></span>
											<p class="description" id="<?php echo esc_html( $this->plugin_name ); ?>-port-description">
												<?php esc_html_e( 'The port number used to access your home server via SSH.', 'txp-ping-recorder' ); ?>
											</p>
										</fieldset>
									</li>
								</ul>
								<?php submit_button( 'Save all changes', 'primary','submit', true ); ?>
							</div>
						</form>
					</div>
					<div class="postbox">
						<h2><span class="dashicons dashicons-shield"></span> <?php esc_html_e( 'Most Recent Pings', 'txp-ping-recorder' ); ?></h2>
						<div class="inside">
							<table class="widefat striped">
								<thead>
									<tr>
										<th><?php esc_html_e( 'IP Address', 'txp-ping-recorder' ); ?></th>
										<th><?php esc_html_e( 'Recorded', 'txp-ping-recorder' ); ?></th>
										<th><?php esc_html_e( 'SSH Link', 'txp-ping-recorder' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $pings as $ping ) { ?>
										<tr>
											<td><?php echo esc_html( $ping->ip ) ?></td>
											<td><?php echo esc_html( mysql2date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ping->created ) ) ?></td>
											<?php $url = 'ssh://' . $options['username'] . '@' . $ping->ip . ':' . $options['port'] ?>
											<td><a href="<?php echo esc_url( $url, array( 'ssh' ) ); ?>"><?php echo esc_url( $url, array( 'ssh' ) ); ?></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="postbox">
						<h2><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e( 'Instructions', 'txp-ping-recorder' ); ?></h2>
						<div class="inside">
							<p>
								<?php esc_html_e( 'Call the URL below from your home server to record a ping which will be displayed above.', 'txp-ping-recorder' ); ?>
							</p>
							<p>
								<?php
									$url = get_site_url( null, '?' . $this->plugin_name . '=' . $options['secretkey'] );
								?>
								<a href="<?php echo esc_html( $url ) ?>"><?php echo esc_html( $url ) ?></a>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- Sidebar -->
		   <div id="postbox-container-1" class="postbox-container">
			   <div class="metabox-sortables">
				   <div class="postbox">
					   <h2><span class="dashicons dashicons-info"></span> <?php esc_html_e( 'More information' ); ?></h2>
					   <div class="inside">
						   <p><?php esc_html_e( 'The purpose of this plugin is to keeps a list of \'pings\' from a home server to make it easy to remotely access it.', 'txp-ping-recorder' ); ?></p>
						   <p><?php esc_html_e( 'More information on this plugin is available from the links below.', 'txp-ping-recorder' ); ?></p>
						   <ul class="striped">
							   <li><span class="dashicons dashicons-admin-plugins"></span> <a href="https://techxplorer.com/projects/techxplorers-ping-recorder/"><?php esc_html_e( 'Plugin homepage.', 'txp-ping-recorder' ); ?></a></li>
							   <li><span class="dashicons dashicons-twitter"></span> <a href="https://twitter.com/techxplorer"><?php esc_html_e( 'My Twitter profile.', 'txp-ping-recorder' ); ?></a></li>
							   <li><span class="dashicons dashicons-admin-home"></span> <a href="https://techxplorer.com/"><?php esc_html_e( 'My website.', 'txp-ping-recorder' ); ?></a></li>
						   </ul>
				   </div>
			   </div>
		   </div>
		   <br class="clear">
	   </div>
	</div>
</div>
