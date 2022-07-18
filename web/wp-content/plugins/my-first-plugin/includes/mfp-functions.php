<?php
global $url;
//$url = 'https://172.19.0.3';
//$url = 'https://starterkit.code';
//$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
$url = "https://newsletter.preprod.lonsdale.fr/";

// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
// Add a new top level menu link to the ACP
add_action('admin_menu', 'mfp_Add_My_Admin_Link');
function mfp_Add_My_Admin_Link()
{
	add_menu_page(
		'My First Page', // Title of the page
		'Mon plugin', // Text to show on the menu link
		'manage_options', // Capability requirement to see the link
		'my-first-plugin/includes/mfp-first-acp-page.php' // The 'slug' - file to display when clicking the link
	);
}

// helpers
function loadPage($url)
{
	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer" => false,
			"verify_peer_name" => false,
		),
	);
	return file_get_contents($url, false, stream_context_create($arrContextOptions));
}

function queryPosts()
{
	$args = array(
		'post_type' => "any",
		'posts_per_page' => -1,
		'order' => 'DESC',
		'orderby' => 'modified',
		'post_status' => 'publish'
	);
	$posts = new WP_Query($args);
	wp_reset_postdata();
	return $posts->posts;
}
function display()
{
	// list of cpts
	$args = array(
		'public'   => true,
		'_builtin' => false,
	);
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args, $output, $operator);

	//
	$posts = queryPosts();
	foreach ($posts as $post) {
		$origin = date_create($post->post_modified_gmt);
		$target = date_create($post->static_generate);
		$interval = date_diff($origin, $target);
		$slug = $post->post_name;

		if (in_array($post->post_type, $post_types)) {
			$post_type_object = get_post_type_object($post->post_type);
			$slug = $post_type_object->rewrite['slug'] . "/" . $post->post_name;
		}
		echo "<tr>";
		echo '<td><a href="/' . $slug . '/" target="_blank">' . $post->post_title . '</a></td>';
		echo "<td>" . $post->post_type  . "</td>";
		echo "<td>" . $post->post_modified_gmt . "</td>";
		echo "<td>" . $post->static_generate . "</td>";
		echo '<td><input type="checkbox" ' . ($post->static_active ? "checked" : "") . '></td>';
		echo '<td>✓' . $interval->format('%R%a days') . '</td>';
		echo "</tr>";
	}
}


// AJAX

// toggle static/not static
add_action('wp_ajax_static_change_status', 'static_change_status_callback');
add_action('wp_ajax_nopriv_static_change_status', 'static_change_status_callback');
function static_change_status_callback()
{
	checkNonce('test_nonce');

	if ($_POST['status'] == "true") {
		rename(ABSPATH . "wp-content/static_inactive/", ABSPATH . "wp-content/static/");
	} else {
		rename(ABSPATH . "wp-content/static/", ABSPATH . "wp-content/static_inactive/");
	}

	$link = mysqli_connect(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));
	// Check connection
	if ($link === false) {
		// die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$sql = "UPDATE wp_static_options SET static_active = " . $_POST['status'] . " WHERE options_id = 1";
	mysqli_query($link, $sql);
	mysqli_close($link);
}

function ctpPages($value)
{
	$args = array(
		'post_type' => $value,
		'posts_per_page' => -1,
		'order' => 'ID',
		'orderby' => 'title',
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
	);
	$queryArticles = new WP_Query($args);
	$posts_per_page = get_option('posts_per_page');
	$totalPages = ceil($queryArticles->post_count / $posts_per_page);
	for($i = 1; $i <= $totalPages; $i++){
		//echo "page/".$i;

	}
}
//
add_action('wp_ajax_test', 'test_callback');
add_action('wp_ajax_nopriv_test', 'test_callback');
function test_callback()
{
	global $url;

	checkNonce('test_nonce');


	// list of cpts
	$args = array(
		'public'   => true,
		'_builtin' => false,
	);
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args, $output, $operator);

	foreach ($post_types as $post_type) {
		ctpPages($post_type);
	}

	$posts = queryPosts();

	$static_folder = ($_POST['status'] == "true") ? "static" : "static_inactive";
	$markup = "";
	foreach ($posts as $post) {
		$folder = "/" . $post->post_name . "/";

		if (in_array($post->post_type, $post_types)) {
			$post_type_object = get_post_type_object($post->post_type);
			$folder = "/" . $post_type_object->rewrite['slug'] . "/" . $post->post_name . "/";
		}


		$html = loadPage($url . $folder . "?dynamic=true");
		if ($folder === "/home/") {
			file_put_contents(WP_CONTENT_DIR . '/' . $static_folder . '/index.html', $html);
		} else {
			mkdir(WP_CONTENT_DIR . '/' . $static_folder . $folder, 0755, true);
			file_put_contents(WP_CONTENT_DIR . '/' . $static_folder . $folder . 'index.html', $html);
		}

		$markup .= "<tr>";
		$markup .= '<td><a href="/' . $post->post_name . '/" target="_blank">' . $post->post_title . '</a></td>';
		$markup .= "<td>" . $post->post_type  . "</td>";
		$markup .= "<td>" . $post->post_modified_gmt . "</td>";
		$markup .= "<td> 20s00411-04</td>";
		$markup .= '<td><input type="checkbox"></td>';
		$markup .= '<td>✓</td>';
		$markup .= "</tr>";
	}

	$response['markup'] = $markup;

	wp_send_json($response);
	wp_die();
}
