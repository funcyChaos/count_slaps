<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       funcychaos.github.io
 * @since      1.0.0
 *
 * @package    Count_Slaps
 * @subpackage Count_Slaps/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Count_Slaps
 * @subpackage Count_Slaps/public
 * @author     funcyChaos <funcychaos@funcychaos.com>
 */
class Count_Slaps_Public {

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

	private $public_nonce = 'count_slaps_nonce';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	
	// Slap a team!
	public function slap(){
		
		if(!wp_verify_nonce($_REQUEST['nonce'], $this->public_nonce)){

			exit('{"response": "GTFOH!"}');
		}
		
		if(get_option('toggle_counting')){
			
			if($_REQUEST['var1'] == 'slap1'){
				
				$result['slap1'] = get_option('slap1', 0);
				update_option('slap1', ++$result['slap1']);
			}else{
				
				if($_REQUEST['var2'] == 'true'){
		
					$result['slap2'] = get_option('slap2', 0);	
					update_option('slap2', $result['slap2'] + 6);
				}else{
		
					$result['slap2'] = get_option('slap2', 0);
					update_option('slap2', ++$result['slap2']);
				}
			}
		}else{

			$result['slap1'] = get_option('slap1', 0);
			$result['slap2'] = get_option('slap2', 0);
		}
			
		echo json_encode($result);
		
		die();
	}

	public function return_slaps(){
		
		$return['slap1'] = get_option('slap1', 0);
		$return['slap2'] = get_option('slap2', 0);
		
		echo json_encode($return);
		
		die();
	}

	// Count Slaps Shortcodes!
	public function slap_btn_1(){
		
		?>
		<button onclick="slap('slap1')" class="slap-button">
			SLAP!
		</button>
		<?php
	}

	public function slap_btn_2(){
		
		?>
		<button onclick="slap('slap2')" class="slap-button">
			SLAP!
		</button>
		<?php
	}

	public function nonce_div(){

		$nonce = wp_create_nonce($this->public_nonce);
		?>
			<div
				id="nonce-div"
				data-nonce="<?php echo $nonce;?>"
			><?php echo $this->public_nonce;?></div>
		<?php
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/count-slaps-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__).'js/count-slaps-public.js', array(), $this->version, false);

		wp_localize_script($this->plugin_name, 'ajax', array('ajaxurl' => admin_url('admin-ajax.php'))
		);
	}
}
