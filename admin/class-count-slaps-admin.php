<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       funcychaos.github.io
 * @since      1.0.0
 *
 * @package    Count_Slaps
 * @subpackage Count_Slaps/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Count_Slaps
 * @subpackage Count_Slaps/admin
 * @author     funcyChaos <funcychaos@funcychaos.com>
 */
class Count_Slaps_Admin {

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

	private $admin_nonce = 'fota_secret_password';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	public function reset_slaps(){
		
		if(!wp_verify_nonce($_REQUEST['nonce'], $this->admin_nonce)){
	
			exit('{"response": "GTFOH!"}');
		}
	
		delete_option('slap1');
		delete_option('slap2');
		
		echo '{"result": "success"}';
		
		die();
	}

	public function toggle_slaps(){

		if(!wp_verify_nonce($_REQUEST['nonce'], $this->admin_nonce)){
			
			exit('{"response": "GTFOH!"}');
		}
		
		$return['slap1'] = get_option('slap1', 0);
		$return['slap2'] = get_option('slap2', 0);
		
		$counting = get_option('toggle_counting', false);
		update_option('toggle_counting', !$counting);
		$return['state'] = !$counting;
		
		echo json_encode($return);
		
		die();
	}

	public function no_admin(){die();}

	public function slap_menu(){

		add_menu_page(
	
			'Slap Menu',
			'Slap Menu',
			'edit_posts',
			'manage_slaps',
			array($this, 'render_slap_menu'),
			'dashicons-thumbs-up',
			3
		);
	}

	public function render_slap_menu(){

		?>
		<h1>Slap Counter Settings</h1>
	
		<h3>Slap 1:</h3>
		<p id="slap1"><?php //echo get_option('slap1', 0);?></p>
		<h3>Slap 2:</h3>
		<p id="slap2"><?php //echo get_option('slap2', 0);?></p>
		<button onclick="adminReset()">Reset Slaps</button>
		<button onclick="returnSlaps()">Refresh Slaps</button>
		<button id="count-toggle" onclick="toggleCounting()"><?php echo get_option('toggle_counting', 'Stop Counting') ? 'Stop Counting' : 'Start Counting';?></button>
		<?php
		$nonce = wp_create_nonce($this->admin_nonce);
		?>
		<div
			id="nonce-div"
			data-nonce="<?php echo $nonce;?>"
		></div>
		<?php
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Count_Slaps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Count_Slaps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/count-slaps-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Count_Slaps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Count_Slaps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/count-slaps-admin.js', array(), $this->version, false);

		wp_localize_script($this->plugin_name, 'ajax', array('ajaxurl' => admin_url('admin-ajax.php'))
		);
	}
}
