<?php
/*
 * Plugin Name: WordPress for my Clients
 * Plugin URI: http://www.dreamsonline.net/wordpress-plugins/wordpress-for-my-clients/
 * Description: Helps customize WordPress for your clients by hiding non essential wp-admin components and by adding support for custom login logo and favicon for website and admin pages.
 * Version: 3.1.0
 * Author: Dreams Online Themes
 * Author URI: http://www.dreamsonline.net/wordpress-themes/
 * Author Email: hello@dreamsmedia.in
 *
 * @package WordPress
 * @subpackage DOT_WPFMC
 * @author Harish
 * @since 1.0
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

			add_action( 'init', array( &$this, 'be_initialize_cmb_meta_boxes' ), 9999 );
			add_action( 'init', array( &$this, 'dot_create_gallery' ) );
			add_action( 'init', array( &$this, 'themeist_customizer_library' ) );

			// Adding Plugin Menu
			add_action( 'admin_menu', array( &$this, 'dot_wpfmc_menu' ) );

			 // Load our custom assets.
			add_action( 'admin_enqueue_scripts', array( &$this, 'dot_wpfmc_assets' ) );

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

			// Change the default Login page Logo
			add_action( 'login_head', array( &$this, 'dot_wpfmc_login_logo' ) );

			// Add Favicon to website frontend
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_favicon_frontend' ) );

			// Add Meta
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_facebook_admin_id' ), 1 ); //Facebook Insights Admin
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_yahoo_verify' ), 1 ); //Yahoo Site Verification
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_google_verify' ), 1 ); //Google Site Verification
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_ms_verify' ), 1 ); //Microsoft Site Verification
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_pinterest_verify' ), 1 ); //Pinterest Site Verification
			add_action( 'wp_head', array( &$this, 'dot_wpfmc_alexa_verify' ), 1 ); //Alexa Site Verification

			// Add Favicon to website backend
			add_action( 'admin_head', array( &$this, 'dot_wpfmc_favicon_backend' ) );
			add_action( 'login_head', array( &$this, 'dot_wpfmc_favicon_backend' ) );

			add_action('widgets_init', array( &$this, 'dot_widgets_init' ) );


			// WooCommerce Branding
			/**
			 * Check if WooCommerce is active
			 **/
			// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			// 	add_filter( 'gettext', array( &$this, 'dot_wpfmc_woocommerce_menu_title' ) );
			// 	add_filter( 'ngettext', array( &$this, 'dot_wpfmc_woocommerce_menu_title' ) );

			// 	// Add WooCommerce Icon to website backend
			// 	add_action( 'admin_head', array( &$this, 'dot_wpfmc_woocommerce_icon' ) );
			// }


		} // end constructor


		// -------------- Initialize Metabox Class --------------
		function themeist_customizer_library() {
			if ( !class_exists( 'Customizer_Library' ) && ( current_theme_supports('themeist_customizer_library_support') ) ) {
				require_once( 'includes/customizer-library/customizer-library.php' );
			}
		}

		// -------------- Initialize Metabox Class --------------
		function be_initialize_cmb_meta_boxes() {
			if ( !class_exists( 'cmb_Meta_Box' ) && ( current_theme_supports('dot_metabox_support') ) ) {
				require_once( 'includes/metabox/init.php' );
			}
		}

		// -------------- Initialize Custom Image Gallery --------------
		function dot_create_gallery() {
			if ( !function_exists( 'gallery_metabox_enqueue' ) && ( current_theme_supports('dot_gallery_support') ) ) {
				require_once ('includes/gallery-metabox/gallery.php');
			}
		}

		// -------------- Widgets --------------
		function dot_widgets_init() {

			// Contact Card Widget
			require_once('includes/widgets/dot-contact.php');
			register_widget('widget_contact');


			// Facebook Widget
			require_once('includes/widgets/dot-facebook.php');
			register_widget('widget_facebook');

			// Flickr Widget
			require_once('includes/widgets/dot-flickr.php');
			register_widget('widget_flickr');

			// Embed Widget
			require_once('includes/widgets/dot-embed.php');
			register_widget('widget_embed');
		}

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
			$menu_title = __('WP for my Clients', 'dot_wpfmc');
			$capability = 'manage_options';
			$menu_slug = 'dot_wpfmc';
			$function =  array( &$this, 'dot_wpfmc_menu_contents');
			add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);

		}	//dot_wpfmc_menu

		/*--------------------------------------------*
		 * Load Necessary JavaScript Files
		 *--------------------------------------------*/

		function dot_wpfmc_assets() {
			if (isset($_GET['page']) && $_GET['page'] == 'dot_wpfmc') {

				wp_enqueue_style( 'thickbox' ); // Stylesheet used by Thickbox
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'media-upload' );

				wp_register_script('dot_wpfmc_admin', plugins_url( '/js/dot_wpfmc_admin.js' , __FILE__ ), array( 'thickbox', 'media-upload' ));
				wp_enqueue_script('dot_wpfmc_admin');
			}
		} //dot_wpfmc_assets

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
			add_settings_field( 'facebook_admin_id', __( 'Facebook Admin', 'dot_wpfmc' ), array( &$this, 'section_facebook_admin_id' ), 'dot_wpfmc_settings', 'general' );
			add_settings_field( 'google_verify', __( 'Google Webmaster Tools', 'dot_wpfmc' ), array( &$this, 'section_google_verify' ), 'dot_wpfmc_settings', 'general' );
			add_settings_field( 'yahoo_verify', __( 'Yahoo Verfication Key', 'dot_wpfmc' ), array( &$this, 'section_yahoo_verify' ), 'dot_wpfmc_settings', 'general' );
			add_settings_field( 'alexa_verify', __( 'Alexa Verification ID', 'dot_wpfmc' ), array( &$this, 'section_alexa_verify' ), 'dot_wpfmc_settings', 'general' );
			add_settings_field( 'pinterest_verify', __( 'Pinterest', 'dot_wpfmc' ), array( &$this, 'section_pinterest_verify' ), 'dot_wpfmc_settings', 'general' );
			add_settings_field( 'ms_verify', __( 'Bing Webmaster Tools', 'dot_wpfmc' ), array( &$this, 'section_ms_verify' ), 'dot_wpfmc_settings', 'general' );


			// Feed Settings
			//add_settings_section( 'feedburner_configuration', __( 'Feedburner Settings', 'dot_wpfmc' ), array( &$this, 'section_feedburner_configuration' ), 'dot_wpfmc_settings' );
			//add_settings_field( 'feedburner_url', __( 'Feedburner URL', 'dot_wpfmc' ), array( &$this, 'section_feedburner_url' ), 'dot_wpfmc_settings', 'feedburner_configuration' );

			// Logo Settings
			add_settings_section( 'login_logo', __( 'Login Logo Settings', 'dot_wpfmc' ), array( &$this, 'section_login_logo' ), 'dot_wpfmc_settings' );
			add_settings_field( 'login_logo_url', __( 'Upload Login Logo', 'dot_wpfmc' ), array( &$this, 'section_login_logo_url' ), 'dot_wpfmc_settings', 'login_logo' );
			add_settings_field( 'login_logo_height', __( 'Set Logo Height', 'dot_wpfmc' ), array( &$this, 'section_login_logo_height' ), 'dot_wpfmc_settings', 'login_logo' );

			// Custom Favicon
			add_settings_section( 'favicon', __( 'Custom Favicon & Apple touch icon', 'dot_wpfmc' ), array( &$this, 'section_favicon' ), 'dot_wpfmc_settings' );
			add_settings_field( 'favicon_frontend_url', __( 'Favicon for Website', 'dot_wpfmc' ), array( &$this, 'section_favicon_frontend_url' ), 'dot_wpfmc_settings', 'favicon' );
			add_settings_field( 'favicon_backend_url', __( 'Favicon for Admin', 'dot_wpfmc' ), array( &$this, 'section_favicon_backend_url' ), 'dot_wpfmc_settings', 'favicon' );
			add_settings_field( 'apple_icon_frontend_url', __( 'Apple Touch Icon for Website', 'dot_wpfmc' ), array( &$this, 'section_apple_icon_frontend_url' ), 'dot_wpfmc_settings', 'favicon' );
			add_settings_field( 'apple_icon_backend_url', __( 'Apple Touch Icon for Admin', 'dot_wpfmc' ), array( &$this, 'section_apple_icon_backend_url' ), 'dot_wpfmc_settings', 'favicon' );
			add_settings_field( 'apple_icon_style', __( 'Basic Apple Touch Icon', 'dot_wpfmc' ), array( &$this, 'section_apple_icon_style' ), 'dot_wpfmc_settings', 'favicon' );

			/**
			 * Check if WooCommerce is active
			 **/
			// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			// 	// WooCommerce Branding
			// 	add_settings_section( 'woocommerce_branding', __( 'WooCommerce Branding', 'dot_wpfmc' ), array( &$this, 'section_woocommerce_branding' ), 'dot_wpfmc_settings' );
			// 	add_settings_field( 'woocommerce_branding_name', __( 'Name', 'dot_wpfmc' ), array( &$this, 'section_woocommerce_branding_name' ), 'dot_wpfmc_settings', 'woocommerce_branding' );
			// 	add_settings_field( 'woocommerce_branding_icon', __( 'Icon URL', 'dot_wpfmc' ), array( &$this, 'section_woocommerce_branding_icon' ), 'dot_wpfmc_settings', 'woocommerce_branding' );

			// }

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
						<input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes', 'dot_wpfmc'); ?>" />
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

		function section_facebook_admin_id() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[facebook_admin_id]' class='text' name='dot_wpfmc_settings[facebook_admin_id]' value='<?php echo sanitize_text_field($options["facebook_admin_id"]); ?>'/>
			<?php
		}

		function section_google_verify() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[google_verify]' class='text' name='dot_wpfmc_settings[google_verify]' value='<?php echo sanitize_text_field($options["google_verify"]); ?>'/>
			<?php
		}

		function section_yahoo_verify() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[yahoo_verify]' class='text' name='dot_wpfmc_settings[yahoo_verify]' value='<?php echo sanitize_text_field($options["yahoo_verify"]); ?>'/>
			<?php
		}

		function section_alexa_verify() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[alexa_verify]' class='text' name='dot_wpfmc_settings[alexa_verify]' value='<?php echo sanitize_text_field($options["alexa_verify"]); ?>'/>
			<?php
		}

		function section_pinterest_verify() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[pinterest_verify]' class='text' name='dot_wpfmc_settings[pinterest_verify]' value='<?php echo sanitize_text_field($options["pinterest_verify"]); ?>'/>
			<?php
		}

		function section_ms_verify() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[ms_verify]' class='text' name='dot_wpfmc_settings[ms_verify]' value='<?php echo sanitize_text_field($options["ms_verify"]); ?>'/>
			<?php
		}

		function section_feedburner_configuration()  {}

		function section_feedburner_url() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
				<input type='text' id='dot_wpfmc_settings[feedburner_url]' class='text' name='dot_wpfmc_settings[feedburner_url]' value='<?php echo sanitize_text_field($options["feedburner_url"]); ?>'/>
			<?php
		}

		function section_login_logo()  {}

		function section_login_logo_url() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[login_logo_url]' class='regular-text text-upload' name='dot_wpfmc_settings[login_logo_url]' value='<?php echo esc_url( $options["login_logo_url"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an image'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["login_logo_url"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		function section_login_logo_height() 	{
			$options = get_option( 'dot_wpfmc_settings' );

			?>
				<input type='text' id='dot_wpfmc_settings[login_logo_height]' class='text' name='dot_wpfmc_settings[login_logo_height]' value='<?php echo sanitize_text_field($options["login_logo_height"]); ?>'/> px
			<?php
		}


		function section_favicon() {}

		function section_favicon_frontend_url() {
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[favicon_frontend_url]' class='regular-text text-upload' name='dot_wpfmc_settings[favicon_frontend_url]' value='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an image'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		function section_favicon_backend_url() {
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[favicon_backend_url]' class='regular-text text-upload' name='dot_wpfmc_settings[favicon_backend_url]' value='<?php echo esc_url( $options["favicon_backend_url"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an image'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_backend_url"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		function section_apple_icon_frontend_url() {
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[apple_icon_frontend_url]' class='regular-text text-upload' name='dot_wpfmc_settings[apple_icon_frontend_url]' value='<?php echo esc_url( $options["apple_icon_frontend_url"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an image'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["apple_icon_frontend_url"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		function section_apple_icon_backend_url() {
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[apple_icon_backend_url]' class='regular-text text-upload' name='dot_wpfmc_settings[apple_icon_backend_url]' value='<?php echo esc_url( $options["apple_icon_backend_url"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an image'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["apple_icon_backend_url"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		function section_apple_icon_style() {

			$options = get_option( 'dot_wpfmc_settings' );
			if( !isset($options['apple_icon_style']) ) $options['apple_icon_style'] = '0';

			echo '<input type="hidden" name="dot_wpfmc_settings[apple_icon_style]" value="0" />
			<label><input type="checkbox" name="dot_wpfmc_settings[apple_icon_style]" value="1"'. (($options['apple_icon_style']) ? ' checked="checked"' : '') .' />
			 Disable Curved Border & reflective shine for Apple touch icon</label><br />';
		}


		function section_woocommerce_branding() 	{

			_e( 'Replace WooCommerce branding with your own', 'dot_wpfmc' );

		}

		function section_woocommerce_branding_name() 	{
			$options = get_option( 'dot_wpfmc_settings' );

			?>
				<input type='text' id='dot_wpfmc_settings[woocommerce_branding_name]' class='regular-text' name='dot_wpfmc_settings[woocommerce_branding_name]' value='<?php echo sanitize_text_field($options["woocommerce_branding_name"]); ?>'/>
			<?php
		}

		function section_woocommerce_branding_icon() 	{
			$options = get_option( 'dot_wpfmc_settings' );
			?>
			<span class='upload'>
				<input type='text' id='dot_wpfmc_settings[woocommerce_branding_icon]' class='regular-text text-upload' name='dot_wpfmc_settings[woocommerce_branding_icon]' value='<?php echo esc_url( $options["woocommerce_branding_icon"] ); ?>'/>
				<input type='button' class='button button-upload' value='Upload an Icon'/></br>
				<img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["woocommerce_branding_icon"] ); ?>' class='preview-upload' />
			</span>
			<?php
		}

		/*--------------------------------------------*
		 * Settings Validation
		 *--------------------------------------------*/

		function settings_validate($input) {

			return $input;
		}

		/*--------------------------------------------*
		 * OutPut
		 *--------------------------------------------*/

		// Add Favicon to website frontend
		function dot_wpfmc_facebook_admin_id() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['facebook_admin_id']) && $options['facebook_admin_id'] != "" ) {
				echo '<meta property="fb:admins" content="'.  sanitize_text_field( $options["facebook_admin_id"] )  .'"/>'."\n";
			}
		}

		// Add Google verification code to website frontend
		function dot_wpfmc_google_verify() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['google_verify']) && $options['google_verify'] != "" ) {
				echo '<meta name="google-site-verification" content="'.  sanitize_text_field( $options["google_verify"] )  .'"/>'."\n";
			}
		}

		// Add Yahoo verification code to website frontend
		function dot_wpfmc_yahoo_verify() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['yahoo_verify']) && $options['yahoo_verify'] != "" ) {
				echo '<meta name="y_key" content="'.  sanitize_text_field( $options["yahoo_verify"] )  .'"/>'."\n";
			}
		}

		// Add Bing verification code to website frontend
		function dot_wpfmc_ms_verify() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['ms_verify']) && $options['ms_verify'] != "" ) {
				echo '<meta name="msvalidate.01" content="'.  sanitize_text_field( $options["ms_verify"] )  .'"/>'."\n";
			}
		}

		// Add Pinterest verification code to website frontend
		function dot_wpfmc_pinterest_verify() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['pinterest_verify']) && $options['pinterest_verify'] != "" ) {
				echo '<meta name="p:domain_verify" content="'.  sanitize_text_field( $options["pinterest_verify"] )  .'"/>'."\n";
			}
		}

		// Add Alexa verification code to website frontend
		function dot_wpfmc_alexa_verify() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['alexa_verify']) && $options['alexa_verify'] != "" ) {
				echo '<meta name="alexaVerifyID" content="'.  sanitize_text_field( $options["alexa_verify"] )  .'"/>'."\n";
			}
		}


		function dot_wpfmc_login_logo() {

			$options = get_option( 'dot_wpfmc_settings' );
			//if( !isset($options['login_logo_url']) ) $options['login_logo_url'] = '0';
			//if( !isset($options['login_logo_url_height']) ) $options['login_logo_url_height'] = 'auto';

			if( isset($options['login_logo_url']) && $options['login_logo_url'] != "" ) {
				echo '<style type="text/css">
				h1 a { background-image:url('.esc_url( $options["login_logo_url"] ).') !important; 	height:'.sanitize_text_field( $options["login_logo_height"] ).'px !important; background-size: auto auto !important; width: auto !important;}
					</style>';
			}
		}

		function dot_wpfmc_login_headertitle( $title ) {
			return get_bloginfo( 'name' );
		}

		function dot_wpfmc_login_headerurl( $url ) {
			return home_url();
		}

		// Add Favicon to website frontend
		function dot_wpfmc_favicon_frontend() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['favicon_frontend_url']) && $options['favicon_frontend_url'] != "" ) {
				echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_frontend_url"] )  .'"/>'."\n";
			}

			if( isset($options['apple_icon_frontend_url']) && $options['apple_icon_frontend_url'] != "" ) {

				if ( $options['apple_icon_style'] == '0') {

					echo '<link rel="apple-touch-icon" href="'.  esc_url( $options["apple_icon_frontend_url"] )  .'"/>'."\n";

				}
				else {

					echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $options["apple_icon_frontend_url"] )  .'"/>'."\n";

				}
			}
		}

		// Add Favicon to website backend
		function dot_wpfmc_favicon_backend() {
			$options =  get_option('dot_wpfmc_settings');

			if( isset($options['facebook_admin_id']) && $options['favicon_backend_url'] != "" ) {
				echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_backend_url"] )  .'"/>'."\n";
			}

			if( isset($options['apple_icon_backend_url']) && $options['apple_icon_backend_url'] != "" ) {

				if ( $options['apple_icon_style'] == '0') {

					echo '<link rel="apple-touch-icon" href="'.  esc_url( $options["apple_icon_backend_url"] )  .'"/>'."\n";

				}
				else {

					echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $options["apple_icon_backend_url"] )  .'"/>'."\n";

				}
			}
		}

		/*--------------------------------------------*
		 * Remove Admin Menus
		 *--------------------------------------------*/

		function dot_wpfmc_remove_menus() {

			$options = get_option('dot_wpfmc_settings');

			// Links page
			remove_menu_page( 'link-manager.php' );


			// Posts Menu
			if ( isset($options['hide_post']) && $options['hide_post'] == '1') {
				remove_menu_page('edit.php');
			}

			// Tools Menu
			if ( isset($options['hide_tools']) && $options['hide_tools'] == '1') {
				remove_menu_page('tools.php');
			}

			// Comments Menu
			if ( isset($options['hide_comments']) && $options['hide_comments'] == '1') {
				remove_menu_page('edit-comments.php');
			}

			// Media Menu
			if ( isset($options['hide_media']) && $options['hide_media'] == '1') {
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
			if ( isset($options['show_quick_press']) && $options['show_quick_press'] == '0') {
				remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			}

			// Recent Drafts Widget
			if ( isset($options['show_recent_drafts']) && $options['show_recent_drafts'] == '0') {
				remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
			}

		}

		function dot_wpfmc_woocommerce_menu_title( $translated )
		{
			$options = get_option('dot_wpfmc_settings');
			if( !isset($options['woocommerce_branding_name']) ) $options['woocommerce_branding_name'] = '';

			//
			if ( isset($options['woocommerce_branding_name']) && $options['woocommerce_branding_name'] == '') {
				return $translated;

			} else {
				$translated = str_replace( 'WooCommerce', sanitize_text_field( $options["woocommerce_branding_name"]), $translated );
				$translated = str_replace( 'WooCommerce', sanitize_text_field( $options["woocommerce_branding_name"]), $translated );
				return $translated;
			}
		}

		function dot_wpfmc_woocommerce_icon() {

			$options = get_option( 'dot_wpfmc_settings' );

			if( isset($options['woocommerce_branding_icon']) && $options['woocommerce_branding_icon'] != "" ) {
				echo '<style type="text/css">
					#adminmenu #toplevel_page_woocommerce div.wp-menu-image {
						background-image: url('.esc_url( $options["woocommerce_branding_icon"] ).');
						background-size: auto;
						background-position: 0 0;

					}
					</style>';
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* SEO Plugins Check
		/* Check if there any third party SEO plugins active
		/* @return bool True is other plugin is detected
		/*-----------------------------------------------------------------------------------*/

		function dot_using_native_seo() {
			return ! ( defined( 'WPSEO_PATH' ) || class_exists( 'All_in_One_SEO_Pack' ) || class_exists( 'Platinum_SEO_Pack' ) );
		} // end dot_using_native_seo


	} // End Class


	// Initiation call of plugin
	$dot_wpfmc = new DOT_WPFMC(__FILE__);

}

?>