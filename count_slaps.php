<?php

/*
	Plugin Name: count_slaps
	Description: Plugin to keep track of chili"s slaps
	Version: 1.2
	Author: funcyChaos
	Author URI: https://funcychaos.github.io
*/

add_action("rest_api_init", function(){
	register_rest_route("count-slaps", "/slaps/(?P<team>\d+)", array(
		array(
			"methods"	=> "GET",
			"callback"	=> function (){
				global $wpdb;
				$current = $wpdb->get_results(
					"SELECT count FROM `{$wpdb->base_prefix}count_slaps` WHERE id in (1,2)
					", ARRAY_N);
				$res["team1"] = $current[0][0];
				$res["team2"] = $current[1][0];
				update_option("test", $current[0]);
				return $res;
			}
		),
		array(
			"methods"	=> "POST",
			"callback"	=> function ($req){
				if(!get_option("toggle_counting")){
					return array("response"=>"Slaps are closed!","team1"=>"idk","team2"=>"idk");
				}
				global $wpdb;
				$wpdb->query("BEGIN TRAN");
				if($req["team"] == 1){
					$query = $wpdb->prepare(
						"UPDATE `{$wpdb->base_prefix}count_slaps`
						SET count = count + 1
						WHERE id = 1
					");
					$wpdb->query($query);
					$wpdb->query("COMMIT");
					return array("Slap"=>"Successful");
				}elseif($req["team"] == 2){
					$query = $wpdb->prepare(
						"UPDATE `{$wpdb->base_prefix}count_slaps`
						SET count = count + 1
						WHERE id = 2
					");
					$wpdb->query($query);
					$wpdb->query("COMMIT");
					return array("Slap"=>"Successful");
				}else{
					return array("Hi"=>"Idk what you want");
				}
			}
		)
	));
});

register_activation_hook(__FILE__, function(){
	global $wpdb;
	$charset_collate	= $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}count_slaps` (
		id int,
		count	int
	) $charset_collate; INSERT INTO `{$wpdb->base_prefix}count_slaps` (id, count) VALUES (1,0),(2,0)";
	require_once ABSPATH . "wp-admin/includes/upgrade.php";
	dbDelta($sql);
	$res["error"]		= empty($wpdb->last_error);
});

function return_slaps(){
	global $wpdb;
	$current = $wpdb->get_results(
		"SELECT count FROM `{$wpdb->base_prefix}count_slaps` WHERE id in (1,2)
	", ARRAY_N);
	$res["team1"] = $current[0][0];
	$res["team2"] = $current[1][0];
	echo json_encode($res);	
	die();
}
add_action("wp_ajax_return_slaps", "return_slaps");	
add_action("wp_ajax_nopriv_return_slaps", "return_slaps");

// Turn slap counting on and off
function toggle_slaps(){
	if(!wp_verify_nonce($_REQUEST["nonce"], "fota_secret_password")){
		exit('{"response": "GTFOH!"}');
	}
	// Get Slaps
	$counting = get_option("toggle_counting", false);
	update_option("toggle_counting", !$counting);
	$return["state"] = !$counting;
	echo json_encode($return);
	die();
}
add_action("wp_ajax_toggle_slaps", "toggle_slaps");
add_action("wp_ajax_nopriv_toggle_slaps", function(){die();});

// Set all slaps back to 0
function reset_slaps(){
	if(!wp_verify_nonce($_REQUEST["nonce"], "fota_secret_password")){
		exit('{"response": "GTFOH!"}');
	}

	global $wpdb;
	$wpdb->query(
		"UPDATE `{$wpdb->base_prefix}count_slaps`
		SET count = 0
		WHERE id IN(1, 2)
	");
	$res["error"]	= empty($wpdb->last_error);
	echo json_encode($res);
	die();
}
add_action("wp_ajax_reset_slaps", "reset_slaps");
add_action("wp_ajax_nopriv_reset_slaps", function(){die();});



// Register admin slap menu
function slap_menu(){
	add_menu_page(
		"Slap Menu",
		"Slap Menu",
		"edit_posts",
		"manage_slaps",
		"render_slap_menu",
		"dashicons-thumbs-up",
		3
	);
}
// What the admin slap menu actually looks like
function render_slap_menu(){
	?>
	<h1>Slap Counter Settings</h1>
	<h3>Slap 1:</h3>
	<p id="team1"><?php echo get_option("team1", 0);?></p>
	<h3>Slap 2:</h3>
	<p id="team2"><?php echo get_option("team2", 0);?></p>
	<button onclick="returnSlaps()">Refresh Slaps</button>
	<button id="count-toggle" onclick="toggleCounting()"><?php echo get_option("toggle_counting") ? "Stop Counting" : "Start Counting";?></button>
	<button onclick="adminReset()">Reset Slaps</button>
	<?php
	$nonce = wp_create_nonce("fota_secret_password");
	?>
	<div
		id="nonce-div"
		data-nonce="<?php echo $nonce;?>"
	></div>
	<?php
}
add_action("admin_menu", "slap_menu");

// Count Slaps Shortcodes!

/*
 * dev_render
 * General Counter Layout
 * You can use these ID"s on any respective element you want.
 */

function dev_render(){
	?>
		<h1>Slap 1:</h1>
		<div id="xml_count_1">
			<div>
				<h2>
					<?php echo get_option("team1", 0);?>
				</h2>
			</div>
		</div>
		<h1 id="team1_bonus" class="bonus-styles">X6 BONUS!</h1>
		<!-- Some kind of timer? -->
		<button id="slap_btn_1" class="slap-button">
		SLAP!
		</button>
		
		<h1>Slap 2:</h1>
		<div id="xml_count_2">
			<div>
				<h2>
					<?php echo get_option("team2", 0);?>
				</h2>
			</div>
		</div>
		<h1 id="team2_bonus" class="bonus-styles">X6 BONUS!</h1>
		<!-- Some kind of timer? -->
		<button id="slap_btn_2" class="slap-button">
		SLAP!
		</button>
	<?php
}
function nonce_div(){
	?>
		<div
			id="nonce-div"
			data-nonce="<?php echo wp_create_nonce("count_slaps_nonce");?>"
		></div>
		<script>const url = "<?php echo get_site_url();?>"</script>
	<?php
}
add_shortcode("dev_render" , "dev_render");
add_shortcode("nonce_div", "nonce_div");
	

// Get all the javascript on the page and with proper variables :P
function public_script(){
	wp_register_script("count_slaps_public", plugin_dir_url(__FILE__)."	public.js");
	wp_localize_script("count_slaps_public", "ajax", array("ajaxurl" => admin_url("admin-ajax.php"))
	);
	wp_enqueue_script("count_slaps_public","",array(),false,true);
}
add_action("wp_enqueue_scripts", "public_script");

function admin_script(){	
	wp_register_script("count_slaps_admin", plugin_dir_url(__FILE__)."	admin.js");
	wp_localize_script("count_slaps_admin", "ajax", array("ajaxurl" => admin_url("admin-ajax.php"))
	);
	wp_enqueue_script("count_slaps_admin");
}
add_action("admin_enqueue_scripts", "admin_script");

// Count slaps styles :P
function count_slaps_styles(){wp_enqueue_style("styles", plugin_dir_url(__FILE__)."/count_slaps.css");}
add_action("wp_enqueue_scripts", "count_slaps_styles");