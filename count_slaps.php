<?php

/*
	Plugin Name: count_slaps
	Description: Plugin to keep track of chili's slaps
	Version: 1.0
	Author: funcyChaos
	Author URI: https://funcychaos.github.io
*/

// Slap a team!
function slap(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
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

add_action("wp_ajax_slap", "slap");	
add_action("wp_ajax_nopriv_slap", "slap");

// I guess get slaps would have made more sense
function return_slaps(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
	}
	
	$return['slap1'] = get_option('slap1', 0);
	$return['slap2'] = get_option('slap2', 0);
	
	echo json_encode($return);
	
	die();
}

add_action("wp_ajax_return_slaps", "return_slaps");	
add_action("wp_ajax_nopriv_return_slaps", "return_slaps");

// Set all slaps back to 0
function reset_slaps(){

	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
	}

	delete_option('slap1');
	delete_option('slap2');

	echo '{"result": "success"}';

	die();
}

add_action('wp_ajax_reset_slaps', 'reset_slaps');
add_action('wp_ajax_nopriv_reset_slaps', function(){die();});

// Turn slap counting on and off
function toggle_slaps(){

	if(!wp_verify_nonce($_REQUEST['nonce'], "count_slaps_nonce")){

		exit("No naughty business please");
	}
	
	$return['slap1'] = get_option('slap1', 0);
	$return['slap2'] = get_option('slap2', 0);
	
	$counting = get_option('toggle_counting', false);
	update_option('toggle_counting', !$counting);
	$return['state'] = !$counting;
	
	echo json_encode($return);

	die();
}

add_action('wp_ajax_toggle_slaps', 'toggle_slaps');
add_action('wp_ajax_nopriv_toggle_slaps', function(){die();});

// Register admin slap menu
function slap_menu(){

	add_menu_page(

		'Slap Menu',
		'Slap Menu',
		'edit_posts',
		'manage_slaps',
		'render_slap_menu',
		'dashicons-thumbs-up',
		3
	);
}

add_action('admin_menu', 'slap_menu');

// What the admin slap menu actually looks like
function render_slap_menu(){

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
	$nonce = wp_create_nonce("count_slaps_nonce");
	?>
	<div
		id="nonce-div"
		data-nonce="<?php echo $nonce;?>"
	></div>
	<?php
}

// Count Slaps Shortcodes!
function slap_btn_1(){
	
	?>
	<button onclick="slap('slap1')" class="slap-button">
		SLAP!
	</button>
	<?php
}

function slap_btn_2(){
	
	?>
	<button onclick="slap('slap2')" class="slap-button">
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
	

// Get all the javascript on the page and with proper variables :P
function public_script(){
	
	wp_register_script('count_slaps_public', plugin_dir_url(__FILE__).'	public.js');

	wp_localize_script('count_slaps_public', 'ajax', array('ajaxurl' => admin_url('admin-ajax.php'))
	);

	wp_enqueue_script('count_slaps_public');
}

add_action('wp_enqueue_scripts', 'public_script');

function admin_script(){
	
	wp_register_script('count_slaps_admin', plugin_dir_url(__FILE__).'	admin.js');

	wp_localize_script('count_slaps_admin', 'ajax', array('ajaxurl' => admin_url('admin-ajax.php'))
	);

	wp_enqueue_script('count_slaps_admin');
}

add_action('admin_enqueue_scripts', 'admin_script');

// Count slaps styles :P
function count_slaps_styles(){

	wp_enqueue_style('styles', plugin_dir_url(__FILE__)."/count_slaps.css");
}

add_action('wp_enqueue_scripts', 'count_slaps_styles');