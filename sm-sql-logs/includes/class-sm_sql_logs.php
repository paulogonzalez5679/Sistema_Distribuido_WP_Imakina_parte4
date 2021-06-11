<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/mi7osz/
 * @since      1.0.0
 *
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sm_sql_logs
 * @subpackage Sm_sql_logs/includes
 * @author     Mi7osz <esemwp@gmail.com>
 */
class Sm_sql_logs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sm_sql_logs_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Data of curently logged in user
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $user;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if ( defined( 'SM_SQL_LOGS_VERSION' ) ) {
			$this->version = SM_SQL_LOGS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sm_sql_logs';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	 	global $wpdb;
		$this->wpdb = $wpdb;

		$this->options = get_option( 'sm_sql_logs_options' );
		$this->settings = get_option( 'sm_sql_logs_settings' );

		if($this->options['on_off'] == 1)
		{
			if (!defined('SAVEQUERIES')) define('SAVEQUERIES', TRUE);
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sm_sql_logs_Loader. Orchestrates the hooks of the plugin.
	 * - Sm_sql_logs_i18n. Defines internationalization functionality.
	 * - Sm_sql_logs_Admin. Defines all hooks for the admin area.
	 * - Sm_sql_logs_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sm_sql_logs-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sm_sql_logs-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sm_sql_logs-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sm_sql_logs-public.php';

		$this->loader = new Sm_sql_logs_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sm_sql_logs_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sm_sql_logs_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sm_sql_logs_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sm_sql_logs_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'shutdown', $this, 'sql_logs_collect', 9999, 1 );
		$this->loader->add_filter( 'plugins_loaded', $this, 'get_user_info', 9999, 1 );
	}



public function get_user_info()
{
	$current_user = wp_get_current_user(); 

	if ( !($current_user instanceof WP_User) ) 
	return; 

	$this->user = $current_user;
}



	/**
	 * Prepare and insert Logd into Database
	 *
	 * @since    1.0.0
	 */
public function delete_old_logs()
{
	if(!empty($this->settings['max_logs_set']))
	{
		$log = $this->wpdb->get_results( "
			SELECT id
			FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN.'
			ORDER BY id DESC
			LIMIT '.$this->settings['max_logs_set'].', 1
			', ARRAY_A
		);
	}


	if(!empty($log[0]['id']) && $log[0]['id'] > 0)
	{
		$liminal_id = $log[0]['id'];

		$del = $this->wpdb->get_results( "
			DELETE
			FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN.'
			WHERE id<="'.$liminal_id.'"
			', ARRAY_A
		);
	}
}



	/**
	 * Prepare and insert Logd into Database
	 *
	 * @since    1.0.0
	 */
public function sql_logs_collect()
{
	if
	(
		is_array($this->wpdb->queries)
		&&
		(
			$this->options['on_off'] == 1
			&&
			(
				$this->options['max_new_queries'] == ''
				||
				$this->options['max_new_queries'] > 0
			)
		)
	)
	{
		$max_new_queries = $this->options['max_new_queries'];

		$past_cycle_no = $this->wpdb->get_var( "SELECT MAX(cycle_no) FROM ".SM_SQL_LOGS_SQL_TABLE_MAIN );
		$cycle_no = $past_cycle_no + 1;

		foreach($this->wpdb->queries As $q)
		{
			if($max_new_queries != '' && $max_new_queries <= 0)
			{
				break;
			}

			$query = $q[0];
			$query_time = $q[1];
			$query_path = $q[2];

		// Avoid loop of logging self inserts
	  	if(!preg_match('/'.SM_SQL_LOGS_SQL_TABLE_MAIN.'/', $query))
	  	{

		  	// Set query type
	  		$type = FALSE;

	  		if(preg_match('/^SELECT/', trim($query)))
	  		{
	  			$type = 'sel';
	  		}
	  		else if(preg_match('/^INSERT/', trim($query)))
	  		{
	  			$type = 'ins';
	  		}
	  		else if(preg_match('/^DELETE/', trim($query)))
	  		{
	  			$type = 'del';
	  		}
	  		else if(preg_match('/^UPDATE/', trim($query)))
	  		{
	  			$type = 'upd';
	  		}
	  		else
	  		{
	  			$type = 'other';
	  		}

		  	// Log queries
	  		if(
					( $type == 'sel' && $this->options['qt_select'] == 1 )
					||
					( $type == 'upd' && $this->options['qt_update'] == 1 )
					||
					( $type == 'ins' && $this->options['qt_insert'] == 1 )
					||
					( $type == 'del' && $this->options['qt_delete'] == 1 )
					||
					( $type == 'other' && $this->options['qt_other'] == 1 )
				)
	  		{
	  		// User
	  			if(isset( $this->user->ID ))
	  			{
	  				$user_id = $this->user->ID;
	  			}
	  			else
	  			{
	  				$user_id = 0;
	  			}

					$vars = array(
						'type'       => $type,
						'query'      => $query,
						'wp_user_id' => $user_id,
						'file'       => $query_path,
						'query_time' => $query_time,
						'cycle_no'   => $cycle_no
					);

	  			$result = $this->wpdb->insert
					(
	  				SM_SQL_LOGS_SQL_TABLE_MAIN,
						$vars
	  			);

					if($result && $max_new_queries != '')
					{
						$max_new_queries--;
					}
	  		}
	  	}
		}

		if($this->options['max_new_queries'] > 0)
		{
			if($max_new_queries <= 0)
			{
				$this->options['max_new_queries'] = '';
				$this->options['on_off'] = 0;
			}
			else
			{
				$this->options['max_new_queries'] = $max_new_queries;
			}

			update_option('sm_sql_logs_options', $this->options);
		}

	}

	$this->delete_old_logs();
}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sm_sql_logs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
