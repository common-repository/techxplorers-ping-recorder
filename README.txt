=== Plugin Name ===
Contributors: techxplorer
Tags: admin, ssh, ping, server
Requires at least: 4.7
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin keeps a list of 'pings' from a home server to make it easy to remotely access it.

== Description ==

The purpose of this plugin is to keep a log of recent 'pings' from a home server.

A script on the home server calls a specific 'ping' URL. In response the IP address of the request is recorded.
The list of pings is then used to build a list of links to click on in order to access the server via SSH.

**Important Support Notice**
Development of this plugin has ceased, and it is now officially unsupported.
If you are still using this plugin you are strongly encouraged to find an alternative.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/techxplorers-ping-recorder` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Configure the plugin using the 'Ping Recorder' settings page.
1. Regularly call the ping URL from your server to record its IP address.

== Frequently Asked Questions ==

= Are pings secure? =

Yes, the ping URL includes a secret key as a parameter. If the secret key does not match the ping is not recorded.

= Are custom SSH ports supported? =

Yes, you can configure the plugin to use a custom SSH port.

== Screenshots ==

* The settings page for the plugin. Showing how the plugin can be configured and how the pings are displayed.

== Changelog ==

= 1.1.1 =
* Confirm compatibility with WordPress 4.8
* Fix code style errors using latest WordPress code style

= 1.1.0 =
* Released to the WordPress plugin directory.

= 1.0.0 =
* Initial release.
