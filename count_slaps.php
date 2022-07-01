<?php

/*
	Plugin Name: count_slaps
	Description: Plugin to keep track of chili's slaps
	Version: 1.0
	Author: funcyChaos
	Author URI: https://funcychaos.github.io
*/

function count_slaps(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
	}
	
	if($_REQUEST['slap'] == 'slap1'){
		
		$result['slap1'] = get_option('slap1', 0);
		update_option('slap1', ++$result['slap1']);
	}else{
		
		if($_REQUEST['bonus'] == 'true'){

			$result['slap2'] = get_option('slap2', 0);	
			update_option('slap2', $result['slap2'] + 6);
		}else{

			$result['slap2'] = get_option('slap2', 0);
			update_option('slap2', ++$result['slap2']);
		}
	}
	
	$itsOver['final'] = 'fuck off its over';
	
	// echo json_encode($result);
	echo json_encode($itsOver);
	
	die();
}

// This guy is hooking count_slaps into the admin-ajax file
add_action("wp_ajax_count_slaps", "count_slaps");	
add_action("wp_ajax_nopriv_count_slaps", "count_slaps");

function return_slaps(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
	}
	
	$return['slap1'] = get_option('slap1', 0);
	$return['slap2'] = get_option('slap2', 0);
	
	echo json_encode($return);
	
	die();
}

// This guy is hooking count_slaps into the admin-ajax file
add_action("wp_ajax_return_slaps", "return_slaps");	
add_action("wp_ajax_nopriv_return_slaps", "return_slaps");

function slap_menu(){

	add_menu_page(

		'Slap Menu',
		'Slap Menu',
		'edit_posts',
		'manage_slaps',
		'render_slap_menu',
		'dashicons-thumbs-up'
	);
}

add_action('admin_menu', 'slap_menu');

function render_slap_menu(){

	?><h1>WordPress Extra Post Info</h1>
	<form method="post" action="options.php">
	<?php settings_fields( 'extra-post-info-settings' ); ?>
	<?php do_settings_sections( 'extra-post-info-settings' ); ?>
	<table class="form-table"><tr valign="top"><th scope="row">Extra post info:</th>
	<td><input type="text" name="extra_post_info" value="<?php echo get_option( 'extra_post_info' ); ?>"/></td></tr></table>
	<?php submit_button(); ?>
	</form>
	<?php
}

function save_slap_settings(){


}

add_action( 'save_post_sponsor_ads', 'save_slap_settings', 10, 0);


function update_extra_post_info(){register_setting( 'extra-post-info-settings', 'extra_post_info' );}
add_action('admin_init', 'update_extra_post_info');


function my_script_enqueuer(){
	
	wp_register_script("count_slaps_aj", WP_PLUGIN_URL.'/count_slaps/count_slaps_aj.js');
	
	// This seems like the key to the communication between ajax and jquery
	wp_localize_script('count_slaps_aj', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php'))
	);

	wp_enqueue_script('count_slaps_aj');
}

// Okay This is adding the javascript in as... an alternative? takes away reloads
add_action('init', 'my_script_enqueuer');

function count_slaps_styles(){
	wp_enqueue_style('styles', plugin_dir_url(__FILE__)."/count_slaps.css");
}

add_action('wp_enqueue_scripts', 'count_slaps_styles');

function slap_btn_1(){
	
	?>
	<button onclick="count_slaps('slap1')" class="slap-button">
		SLAP!
	</button>
	<?php
}

function slap_btn_2(){
	
	?>
	<button onclick="count_slaps('slap2')" class="slap-button">
		SLAP!
	</button>
	<?php
}

function nonce_div(){

	$nonce = wp_create_nonce("count_slaps_nonce");
	?>
		<div
			id="nonce-div"
			data-nonce="<?php echo $nonce;?>"
		></div>
	<?php
}

add_shortcode('slap_btn_1' , 'slap_btn_1');
add_shortcode('slap_btn_2' , 'slap_btn_2');
add_shortcode('nonce_div', 'nonce_div');