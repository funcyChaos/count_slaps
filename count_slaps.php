<?php

/*
	Plugin Name: count_slaps
	Description: Plugin to keep track of chili's slaps
	Version: 1.2
	Author: funcyChaos
	Author URI: https://funcychaos.github.io
*/

// Slap a team!
function tally_slaps(){
	
	if(!wp_verify_nonce($_REQUEST['nonce'], 'count_slaps_nonce')){

		exit('{"response": "GTFOH!"}');
	}
	
	if(get_option('toggle_counting')){

		$team1 = get_option('team1', 0)+$_REQUEST['team1'];
		$team2 = get_option('team2', 0)+$_REQUEST['team2'];

		update_option('team1', $team1);
		update_option('team2', $team2);
		$result['team1'] = $team1;
		$result['team2'] = $team2;
	}else{

		$result['team1'] = get_option('team1', 0);
		$result['team2'] = get_option('team2', 0);
	}
	
	echo json_encode($result);
	
	die();
}

add_action("wp_ajax_tally_slaps", "tally_slaps");	
add_action("wp_ajax_nopriv_tally_slaps", "tally_slaps");

// I guess get slaps would have made more sense
function return_slaps(){
	
	$return['team1'] = get_option('team1', 0);
	$return['team2'] = get_option('team2', 0);
	
	echo json_encode($return);
	
	die();
}

add_action("wp_ajax_return_slaps", "return_slaps");	
add_action("wp_ajax_nopriv_return_slaps", "return_slaps");

// Set all slaps back to 0
function reset_slaps(){

	if(!wp_verify_nonce($_REQUEST['nonce'], 'fota_secret_password')){

		exit('{"response": "GTFOH!"}');
	}

	delete_option('team1');
	delete_option('team2');

	echo '{"result": "success"}';

	die();
}

add_action('wp_ajax_reset_slaps', 'reset_slaps');
add_action('wp_ajax_nopriv_reset_slaps', function(){die();});

// Turn slap counting on and off
function toggle_slaps(){

	if(!wp_verify_nonce($_REQUEST['nonce'], 'fota_secret_password')){

		exit('{"response": "GTFOH!"}');
	}
	
	$return['team1'] = get_option('team1', 0);
	$return['team2'] = get_option('team2', 0);
	
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
	<p id="team1"><?php echo get_option('team1', 0);?></p>
	<h3>Slap 2:</h3>
	<p id="team2"><?php echo get_option('team2', 0);?></p>
	<button onclick="adminReset()">Reset Slaps</button>
	<button onclick="returnSlaps()">Refresh Slaps</button>
	<button id="count-toggle" onclick="toggleCounting()"><?php echo get_option('toggle_counting', 'Stop Counting') ? 'Stop Counting' : 'Start Counting';?></button>
	<?php
	$nonce = wp_create_nonce('fota_secret_password');
	?>
	<div
		id="nonce-div"
		data-nonce="<?php echo $nonce;?>"
	></div>
	<?php
}

// Count Slaps Shortcodes!

/*
 * dev_render
 * General Counter Layout
 * You can use these ID's on any respective element you want.
 */

function dev_render(){

	?>
		<h1>Slap 1:</h1>
		<h2 id="xml_count_1"><?php echo get_option('team1', 0);?></h2>
		<h1 id="team1_bonus" class="bonus-styles">X2 BONUS!</h1>
		<!-- Some kind of timer? -->
		<button id="slap_btn_1" class="slap-button">
		SLAP!
		</button>
		
		<h1>Slap 2:</h1>
		<h2 id="xml_count_2"><?php echo get_option('team2', 0);?></h2>
		<h1 id="team2_bonus" class="bonus-styles">X6 BONUS!</h1>
		<!-- Some kind of timer? -->
		<button id="slap_btn_2" class="slap-button">
		SLAP!
		</button>
	<?php
}

function nonce_div(){

	$nonce = wp_create_nonce('count_slaps_nonce');

	?>
		<div
			id="nonce-div"
			data-nonce="<?php echo $nonce;?>"
		></div>
	<?php
}

add_shortcode('dev_render' , 'dev_render');
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