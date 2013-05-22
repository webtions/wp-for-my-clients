<?php
/*
 * Plugin Name: WordPress for my Clients
 * Plugin URI: http://www.dreamsonline.net/wordpress-plugins/wordpress-for-my-clients/
 * Description: Helps customize WordPress for your clients by hiding non essential wp-admin components.
 * Version: 1.0.0
 * Author: Harish Chouhan
 * Author URI: http://www.dreamsonline.net/wordpress-plugins/wordpress-for-my-clients/
 * Author Email: hello@dreamsmedia.in
 *
 * @package WordPress
 * @subpackage DOT_WPFMC
 * @author Harish
 * @since 1.0.0
 *
 * License:

  Copyright 2013 "WordPress for my Clients WordPress Plugin" (hello@dreamsmedia.in)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'DOT_WPFMC' ) ) {


	class DOT_WPFMC {

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		function __construct() {

			// Load text domain
			add_action( 'init', array( $this, 'load_localisation' ), 0 );

			// Adding Plugin Menu
			add_action( 'admin_menu', array( &$this, 'dot_wpfmc_menu' ) );

			// Register Settings
			add_action( 'admin_init', array( &$this, 'dot_wpfmc_settings' ) );

			// Hook onto the action 'admin_menu' for our function to remove menu items
			add_action( 'admin_menu', array( &$this, 'dot_wpfmc_remove_menus' ) );

			// Hook onto the action 'admin_menu' for our function to remove dashboard widgets
			add_action( 'admin_menu', array( &$this, 'dot_wpfmc_remove_dashboard_widgets' ) );

			// Change Login header URL
			add_filter( 'login_headerurl', array( &$this, 'dot_wpfmc_login_headerurl' ) );

			// Change Login header Title
			add_filter( 'login_headertitle', array( &$this, 'dot_wpfmc_login_headertitle' ) );


		} // end constructor

		/*--------------------------------------------*
		 * Localisation | Public | 1.0 | Return : void
		 *--------------------------------------------*/

		public function load_localisation ()
		{
			load_plugin_textdomain( 'dot_wpfmc', false, basename( dirname( __FILE__ ) ) . '/languages' );

		} // End load_localisation()

		/**
		 * Defines constants for the plugin.
		 */
		function constants() {
			define( 'DOT_WPFMC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		/*--------------------------------------------*
		 * Admin Menu
		 *--------------------------------------------*/

		function dot_wpfmc_menu()
		{
			$page_title = __('WordPress for my Clients', 'dot_wpfmc');
			$menu_title = __('WordPress for my Clients', 'dot_wpfmc');
			$capability = 'manage_options';
			$menu_slug = 'dot_wpfmc';
			$function =  array( &$this, 'dot_wpfmc_menu_contents');
			add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);

		}	//dot_wpfmc_menu

		/*--------------------------------------------*
		 * Settings & Settings Page
		 *--------------------------------------------*/

		public function dot_wpfmc_settings() {

			// Settings
			register_setting( 'dot_wpfmc_settings', 'dot_wpfmc_settings', array(&$this, 'settings_validate') );

			// General Settings
			add_settings_section( 'general', __( 'General Settings', 'dot_wpfmc' ), array( &$this, 'section_general' ), 'dot_wpfmc_settings' );

			add_settings_field( 'remove_menus', __( 'Remove Admin Menus', 'dot_wpfmc' ), array( &$this, 'section_remove_menus' ), 'dot_wpfmc_settings', 'general' );

			add_settings_field( 'show_widgets', __( 'Show Dashboard Widgets', 'dot_wpfmc' ), array( &$this, 'section_show_dashboard_widgets' ), 'dot_wpfmc_settings', 'general' );

			//add_settings_field( 'hide_settings', __( 'Hide Settings Menu', 'dot_wpfmc' ), array( &$this, 'section_hide_settings' ), 'dot_wpfmc_settings', 'general' );

			//add_settings_field( 'hide_tools', __( 'Hide Tools Menu', 'dot_wpfmc' ), array( &$this, 'section_hide_tools' ), 'dot_wpfmc_settings', 'general' );


		}	//dot_wpfmc_settings


		/*--------------------------------------------*
		 * Settings & Settings Page
		 * dot_wpfmc_menu_contents
		 *--------------------------------------------*/

		public function dot_wpfmc_menu_contents() {
		?>
			<div class="wrap">
				<!--<div id="icon-freshdesk-32" class="icon32"><br></div>-->
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e('WordPress for My Clients Settings', 'dot_wpfmc'); ?></h2>

				<form method="post" action="options.php">
					<?php //wp_nonce_field('update-options'); ?>
					<?php settings_fields('dot_wpfmc_settings'); ?>
					<?php do_settings_sections('dot_wpfmc_settings'); ?>
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'dot_wpfmc'); ?>" />
					</p>
				</form>
			</div>

		<?php
		}	//dot_wpfmc_menu_contents

		function section_general() 	{

			//_e( 'Choose which Admin menu to hide', 'dot_wpfmc' );
		}

		function section_remove_menus() {

			$options = get_option( 'dot_wpfmc_settings' );
			if( !isset($options['hide_post']) ) $options['hide_post'] = '0';
			if( !isset($options['hide_tools']) ) $options['hide_tools'] = '0';
			if( !isset($options['hide_comments']) ) $options['hide_comments'] = '0';
			if( !isset($options['hide_media']) ) $options['hide_media'] = '0';

			echo '<input type="hidden" name="dot_wpfmc_settings[hide_post]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[hide_post]" value="1"'. (($options['hide_post']) ? ' checked="checked"' : '') .' />
			 Remove Posts from Admin Menu</label><br />';

			echo '<input type="hidden" name="dot_wpfmc_settings[hide_tools]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[hide_tools]" value="1"'. (($options['hide_tools']) ? ' checked="checked"' : '') .' />
			 Remove Tools from Admin Menu</label><br />';

			echo '<input type="hidden" name="dot_wpfmc_settings[hide_comments]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[hide_comments]" value="1"'. (($options['hide_comments']) ? ' checked="checked"' : '') .' />
			 Remove Comments from Admin Menu</label><br />';

			echo '<input type="hidden" name="dot_wpfmc_settings[hide_media]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[hide_media]" value="1"'. (($options['hide_media']) ? ' checked="checked"' : '') .' />
			 Remove Media from Admin Menu</label>';

		}

		function section_show_dashboard_widgets() {

			$options = get_option( 'dot_wpfmc_settings' );
			if( !isset($options['show_quick_press']) ) $options['show_quick_press'] = '0';
			if( !isset($options['show_recent_drafts']) ) $options['show_recent_drafts'] = '0';

			echo '<input type="hidden" name="dot_wpfmc_settings[show_quick_press]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[show_quick_press]" value="1"'. (($options['show_quick_press']) ? ' checked="checked"' : '') .' />
			 Show Quick Press Dashboard Widget</label><br />';

			echo '<input type="hidden" name="dot_wpfmc_settings[show_recent_drafts]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[show_recent_drafts]" value="1"'. (($options['show_recent_drafts']) ? ' checked="checked"' : '') .' />
			 Show Recent Drafts Dashboard Widget</label>';

		}


		//function section_hide_tools()
		//{

		//}

		/*--------------------------------------------*
		 * Settings Validation
		 *--------------------------------------------*/

		function settings_validate($input) {

			return $input;
		}

		/*--------------------------------------------*
		 * Remove Admin Menus
		 *--------------------------------------------*/

		function dot_wpfmc_remove_menus() {

			$options = get_option('dot_wpfmc_settings');

			// Links page
			remove_menu_page( 'link-manager.php' );


			// Posts Menu
			if ( $options['hide_post'] == '1') {
			    remove_menu_page('edit.php');
			}

			// Tools Menu
			if ( $options['hide_tools'] == '1') {
			    remove_menu_page('tools.php');
			}

			// Comments Menu
			if ( $options['hide_comments'] == '1') {
			    remove_menu_page('edit-comments.php');
			}

			// Media Menu
			if ( $options['hide_media'] == '1') {
			    remove_menu_page('upload.php');
			}

		}

		/*--------------------------------------------*
		 * Remove Dashboard Widgets
		 *--------------------------------------------*/

		function dot_wpfmc_remove_dashboard_widgets() {

			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );

			$options = get_option('dot_wpfmc_settings');

			// Quick Press Widget
			if ( $options['show_quick_press'] == '0') {
			    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			}

			// Recent Drafts Widget
			if ( $options['show_recent_drafts'] == '0') {
			    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
			}

		}

		function dot_wpfmc_login_headertitle( $title ) {
			return get_bloginfo( 'name' );
		}

		function dot_wpfmc_login_headerurl( $url ) {
			return home_url();
		}


	} // End Class


	// Initiation call of plugin
	$dot_wpfmc = new DOT_WPFMC(__FILE__);

}



?>