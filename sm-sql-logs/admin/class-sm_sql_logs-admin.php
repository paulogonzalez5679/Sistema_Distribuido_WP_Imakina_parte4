<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/admin
 * @author     Mi7osz <esemwp@gmail.com>
 */
class Sm_sql_logs_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * WPDB connection
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $wpdb;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		global $wpdb;

		$this->wpdb = $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this, 'sm_sql_logs_setup_menu'));

		if ( is_admin() )
		{
			require_once plugin_dir_path( __FILE__ ) . 'partials/options_fnc.php';
			add_action( 'admin_init', 'sm_sql_logs_options_init' );
		}

		$this->options = get_option( 'sm_sql_logs_options' );
		$this->settings = get_option( 'sm_sql_logs_settings' );

	}

public function sm_sql_logs_setup_menu()
{
	add_menu_page(									'SM - SQL logs',						'SM - SQL logs',	'manage_options', 'sm_sql_logs',					array($this, 'page_admin_main'),	plugins_url('sm_sql_logs_admin_ico.png',__DIR__));
	add_submenu_page('sm_sql_logs',	'SM - SQL logs / Home',			'Home',		 				'manage_options', 'sm_sql_logs',					array($this, 'page_admin_main')			);
	add_submenu_page('sm_sql_logs',	'SM - SQL logs / Logs',			'Logs',						'manage_options', 'sm_sql_logs-logs',			array($this, 'page_admin_logs')			);
	add_submenu_page('sm_sql_logs',	'SM - SQL logs / Options',	'Options',				'manage_options', 'sm_sql_logs-options',	array($this, 'page_admin_settings')	);
}

public function page_admin_main()
{
	require_once plugin_dir_path( __FILE__ ) . 'partials/main.php';
}

public function page_admin_logs()
{
	$lid = FALSE;

	if(!empty($_GET['lid']))
	{
		$lid = (int)$_GET['lid'];
	}

	if($lid > 0)
	{
		require_once plugin_dir_path( __FILE__ ) . 'partials/log_details.php';
	}
	else
	{
		require_once plugin_dir_path( __FILE__ ) . 'partials/logs.php';
	}
}

public function page_admin_settings($att)
{
	require_once plugin_dir_path( __FILE__ ) . 'partials/options.php';
}

public function print_header_html($att)
{
	require_once plugin_dir_path( __FILE__ ) . 'partials/header_html.php';
}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sm_sql_logs-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sm_sql_logs-admin.js', array( 'jquery' ), $this->version, false );
	}

}
