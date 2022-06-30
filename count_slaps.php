<?

/*
	Plugin Name: count_slaps
	Description: A truly amazing plugin.
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
		
		$result['slap2'] = get_option('slap2', 0);
		update_option('slap2', ++$result['slap2']);
	}
	
	echo json_encode($result);
	
	die();
}

// This guy is hooking count_slaps into the admin-ajax file
add_action("wp_ajax_count_slaps", "count_slaps");

// function my_must_login(){
	
	// 	$result['type'] = "must login";
	// 	$result = json_encode($result);
	// 	echo $result;
	// 	die();
	// }
	
add_action("wp_ajax_nopriv_count_slaps", "count_slaps");

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

function render_frontend(){

	$nonce = wp_create_nonce("count_slaps_nonce");
	?>

	<div id="nonce-div" data-nonce="<?php echo $nonce;?>">

		<button onclick="count_slaps('slap1')">
			Slap 1
		</button>
		
		<h4 id="slap1">
			<?php echo get_option('slap1', 0);?>
		</h4>

		<button onclick="count_slaps('slap2')">
			Slap 2
		</button>
		
		<h4 id="slap2">
			<?php echo get_option('slap2', 0);?>
		</h4>

	</div>
	<?php
}

add_shortcode('count_slaps' , 'render_frontend');