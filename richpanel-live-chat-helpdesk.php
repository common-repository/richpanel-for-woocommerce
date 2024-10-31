<?php
/**
 * Plugin Name: Richpanel for WooCommerce
 * Plugin URI: https://richpanel.com/woocommerce-helpdesk-software
 * Description: Richpanel Helpdesk and Live chat for woocommerce.
 * Version: 2.5.1
 * Author: Richpanel
 * Author URI: https://richpanel.com/woocommerce-helpdesk-software/
 * 
 * Woo: 4714891:d35e87fc34a932d1652d813088684cd9
 * WC requires at least: 2.2
 * WC tested up to: 8.3.1
 * 
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// HPOS Compatibility changes
add_action('before_woocommerce_init', 'before_woocommerce_hpos');

function before_woocommerce_hpos() { 
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) { 
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true); 
    }
}

if ( ! class_exists( 'Richpanel_Woo_Analytics' ) ) :

	class Richpanel_Woo_Analytics {


		public function __construct() {
			add_action('plugins_loaded', array($this, 'init'));
			add_filter('query_vars', array($this, 'add_clear_query_var'), 10, 1);
			add_filter('query_vars', array($this, 'add_endpoint_query_vars'), 10, 1);
			$basename = plugin_basename( __FILE__ );
			$prefix = is_network_admin() ? 'network_admin_' : '';

			add_filter( 
			"{$prefix}plugin_action_links_$basename",
			array( $this, 'add_action_links' ), 10, 4 );
		}

		public function init() {
			// Checks if WooCommerce is installed and activated.
			if ( class_exists( 'WC_Integration' ) ) {
				// Include our integration class.
				include_once plugin_dir_path(__FILE__) . 'includes/integration.php';

				// Register the integration.
				add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
			} else {
				// throw an admin error if you like
				if ( current_user_can( 'activate_plugins' )) {
					add_action('admin_notices', array( $this, 'show_woo_warning' ));
				}
			}
		}

		public function show_woo_warning() {
			?>
			<div class="notice notice-error is-dismissible">
				<p style="display: flex; align-items: center;">
					<img src="<?php esc_url(plugins_url('/assets/High-Res-Logo-Icon-Blue.png', __FILE__)); ?>" style="width: 37px;" />
					<b>
					<?php esc_html_e('Richpanel Helpdesk is inactive.', 'richpanel'); ?>
					</b> &nbsp;
					<?php esc_html_e( 'This plugin requires ', 'richpanel' ); ?>
					&nbsp;
					<a href="<?php esc_html_e( 'https://wordpress.org/plugins/woocommerce/', 'richpanel'); ?>" target="_blank">WooCommerce</a>
					&nbsp;
					<?php esc_html_e( ' plugin to be active.', 'richpanel' ); ?>

					<!-- The <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce plugin</a> must be active for Richpanel Helpdesk to work!', 'richpanel'); ?> -->
				</p>
			</div>
			<?php
		}

		public function add_action_links ( $actions, $plugin_file, $plugin_data, $context ) {
			static $plugin;

			if (!isset($plugin)) {
				$plugin = plugin_basename(__FILE__);
			}
			if ($plugin == $plugin_file) {
				$settings = array('settings' => '<a href="' . esc_url( get_admin_url(null, 'admin.php?page=richpanel-admin' )) . '"> Settings </a>');
				$dashboard = array('dashboard' => '<a href="https://app.richpanel.com" target="_blank">Dashboard</a>');

				$actions = array_merge($settings, $actions);
				$actions = array_merge($dashboard, $actions);
			}

			return $actions;
		}

		public function add_clear_query_var( $vars) {
			$vars[] = 'richpanel_clear';
			return $vars;
		}

		public function add_endpoint_query_vars( $vars) {
			$vars[] = 'richpanel_endpoint';
			$vars[] = 'req_id';
			$vars[] = 'recent_orders_sync_days';
			$vars[] = 'richpanel_order_ids';
			return $vars;
		}

		public function add_integration( $integrations) {
			$integrations[] = 'Richpanel_Woo_Analytics_Integration';
			return $integrations;
		}

	}

	$RichpanelWooAnalytics = new Richpanel_Woo_Analytics(__FILE__);


endif;
