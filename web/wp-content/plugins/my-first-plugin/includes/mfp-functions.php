<?php

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



function display111()
{
	$posts = new WP_Query('post_type=any&posts_per_page=-1&post_status=publish');
	$posts = $posts->posts;
	foreach ($posts as $post) {
		echo "<tr>";
		echo '<td><a href="/' . $post->post_name . '/" target="_blank">' . $post->post_title . '</a></td>';
		echo "<td>" . $post->post_type  . "</td>";
		echo "<td>" . $post->post_modified_gmt . "</td>";
		echo "<td> 2000411-04</td>";
		echo '<td><input type="checkbox"></td>';
		echo '<td>✓</td>';
		echo "</tr>";
	}
}


function getAllPages()
{

	global $url;

	function loadPage($url)
	{
		$arrContextOptions = array(
			"ssl" => array(
				"verify_peer" => false,
				"verify_peer_name" => false,
			),
		);
		return file_get_contents($url, true, stream_context_create($arrContextOptions));
	}


	global $allPages;
	$allPages = array();

	array_push($allPages, array(
		"folder" => "/404/"
	));

	$args = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	);

	$pages = get_pages($args);

	foreach ($pages as $page) {
		array_push($allPages, array(
			"folder" => "/" . $page->post_name . "/",
			"ID" => $page->ID,
			"post_date_gmt" => $page->post_date_gmt,
			"post_modified_gmt" => $page->post_modified_gmt,
			"post_name" => $page->post_name,
			"post_title" => $page->post_title,
			"post_parent" => $page->post_name,
			"post_type" => $page->post_type,
			"page_template" => get_post_meta($page->ID, '_wp_page_template', true)

		));
		// print_r($page);
		//get_field( 'xxxxxx',  'red' , $page->ID);
		// update_post_meta( $page->ID, 'xxxxxx', 'red' );
	}

	function cptPage($name, $slug)
	{
		global $allPages;
		$args = array(
			'post_type' => $name,
			'posts_per_page' => -1,
			'order' => 'ID',
			'orderby' => 'title',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
		);
		$queryArticles = new WP_Query($args);
		$inc = 0;
		if ($queryArticles->have_posts()) {
			while ($queryArticles->have_posts()) {
				$queryArticles->the_post();
				$rowId = get_the_ID();
				$inc++;
				array_push($allPages, array(
					"folder" => "/" . $slug . "/" . get_post_field('post_name', $rowId) . "/",
					"ID" => $rowId,
					"post_date_gmt" =>  get_post_field('post_date_gmt', $rowId),
					"post_modified_gmt" => get_post_field('post_modified_gmt', $rowId),
					"post_name" => get_post_field('post_name', $rowId),
					"post_title" => get_post_field('post_title', $rowId),
					"post_parent" => get_post_field('post_name', $rowId),
					"post_type" => get_post_field('post_type', $rowId),
					/*"page_template" => get_post_meta($page->ID, '_wp_page_template', true)*/
				));

				array_push($allPages, array(
					"folder" => "/" . $slug . "/page/" . $inc . "/",
					"ID" => $rowId,
					"post_date_gmt" =>  get_post_field('post_date_gmt', $rowId),
					"post_modified_gmt" => get_post_field('post_modified_gmt', $rowId),
					"post_title" => get_post_field('post_title', $rowId),
					"post_name" => get_post_field('post_name', $rowId),
					"post_parent" => get_post_field('post_name', $rowId),
					"post_type" => get_post_field('post_type', $rowId),
					"pagination" => true
				));
			}
			wp_reset_postdata();
		}
	}


	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args, $output, $operator);

	foreach ($post_types  as $post_type) {
		$post_type_object = get_post_type_object($post_type);
		$slug     = $post_type_object->rewrite['slug'];
		cptPage($post_type, $slug);
	}

	return $allPages;
}


// AJAX

// toggle static/not static
add_action('wp_ajax_static_change_status', 'static_change_status_callback');
add_action('wp_ajax_nopriv_static_change_status', 'static_change_status_callback');
function static_change_status_callback()
{
	checkNonce('test_nonce');

	echo $_POST['status'];

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

//
add_action('wp_ajax_test', 'test_callback');
add_action('wp_ajax_nopriv_test', 'test_callback');
function test_callback()
{
	global $url;

	checkNonce('test_nonce');

	$allPages = getAllPages();

	/*foreach ($allPages  as $page) {

		$html = loadPage($url . $page['folder'] . "?dynamic=true");
		if ($page['folder'] === "/home/") {
			file_put_contents(WP_CONTENT_DIR . '/static/index.html', $html);
		} else {
			mkdir(WP_CONTENT_DIR . '/static' . $page['folder'], 0755, true);
			file_put_contents(WP_CONTENT_DIR . '/static' . $page['folder'] . 'index.html', $html);
		}
	}	*/
	
	$posts = new WP_Query('post_type=any&posts_per_page=-1&post_status=publish');
	$posts = $posts->posts;
	wp_reset_postdata();

	foreach ($posts as $post) {
		$folder = "/" . $post->post_name . "/";
		$html = loadPage($url . $folder . "?dynamic=true");
		if ($folder === "/home/") {
			file_put_contents(WP_CONTENT_DIR . '/static/index.html', $html);
		} else {
			mkdir(WP_CONTENT_DIR . '/static' . $folder, 0755, true);
			file_put_contents(WP_CONTENT_DIR . '/static' . $folder . 'index.html', $html);
		}
	}

	$markup = "";
	foreach ($posts as $post) {
		$markup .= "<tr>";
		$markup .= '<td><a href="/' . $post->post_name . '/" target="_blank">' . $post->post_title . '</a></td>';
		$markup .= "<td>" . $post->post_type  . "</td>";
		$markup .= "<td>" . $post->post_modified_gmt . "</td>";
		$markup .= "<td> 2000411-04</td>";
		$markup .= '<td><input type="checkbox"></td>';
		$markup .= '<td>✓</td>';
		$markup .= "</tr>";
	}

	$response['markup'] = $markup;
	wp_send_json($response);
	wp_die();
}
