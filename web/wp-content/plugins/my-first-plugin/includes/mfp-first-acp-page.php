<?php

global $wpdb;
$nonce = wp_create_nonce('test_nonce');
$table_options = $wpdb->prefix . 'static_options';
$charset_collate = $wpdb->get_charset_collate();

// create tables if no exist
if ($wpdb->get_var("SHOW TABLES LIKE '$table_options'") != $table_options) {
	$sql = "CREATE TABLE $table_options (
    options_id mediumint(9) NOT NULL AUTO_INCREMENT,
    static_active BOOLEAN DEFAULT 0,
    PRIMARY KEY  (options_id)
  ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$table_pages = $wpdb->prefix . 'static_pages';

	$sql = "CREATE TABLE $table_pages (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    post_title varchar(45) DEFAULT NULL,
    folder varchar(45) DEFAULT NULL,
    post_date_gmt varchar(45) DEFAULT NULL,
    post_modified_gmt varchar(45) DEFAULT NULL,
    is_static BOOLEAN DEFAULT 1,
    last_generate BOOLEAN DEFAULT 1,
    time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$wpdb->insert(
		$table_options,
		array(
			'static_active' => true,
		)
	);
} else {
	$table_name = $wpdb->prefix . 'static_options';
	$result = $wpdb->get_results("SELECT static_active FROM  " . $table_name . "  WHERE  options_id = 1 ");
	$isStatic = $result[0]->static_active;
}

// create column in wp_posts
$table_posts = $wpdb->prefix . 'posts';
$table_posts_rows = $wpdb->get_row("SELECT * FROM " . $table_posts);
if (!isset($table_posts_rows->static_active)) {
	$wpdb->query("ALTER TABLE wp_posts ADD static_active  BOOLEAN DEFAULT 0 ");
}
if (!isset($table_posts_rows->static_generate)) {
	$wpdb->query("ALTER TABLE wp_posts ADD static_generate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL ");
}

?>


<div class="wrap">
	<h1>Static website</h1>
	<div>
		<input type="checkbox" id="plug-static-toggle-status" <?php if ($isStatic) echo 'checked' ?>><label for="plug-static-toggle-status">Mode static active</label>
		&nbsp;&nbsp;
		<button class="plug-static-btn-generate">Generate all pages</button>
	</div>
	<br>
	<section>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Slug</th>
					<th>has archive</th>
					<th>New post</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>news</td>
					<td>actualites</td>
					<td>true</td>
					<td>3</td>
					<td>Status</td>
				</tr>
			</tbody>
			
		</table>
	</section>
	<br>
	<section>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th>Title</th>
					<th>Type</th>
					<th>Last save</th>
					<th>Last generate</th>
					<th>static mode</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody id="plug-static-pages">
				<?php display(); ?>
			</tbody>
		</table>
	</section>

</div>



<script>
	const pages_result = document.getElementById('plug-static-pages');
	const btn_generate = document.querySelector('.plug-static-btn-generate');
	const toogle_status = document.getElementById("plug-static-toggle-status");
	toogle_status.checked = Boolean(<?= $isStatic ? true : false; ?>);

	btn_generate.onclick = () => {
		btn_generate.disabled = true;
		const data = new FormData();
		data.append('action', "test");
		data.append('nonce', '<?= $nonce ?>');
		data.append('status', toogle_status.checked);
		const xhr = new XMLHttpRequest();
		xhr.open("post", '<?= AJAX_URL ?>', true);
		xhr.send(data);
		xhr.onload = () => {
			btn_generate.disabled = false;
			const response = JSON.parse(xhr.responseText);
			pages_result.innerHTML = response.markup;
		}
	}

	// status
	toogle_status.onchange = () => {
		const data = new FormData();
		data.append('action', "static_change_status");
		data.append('nonce', '<?= $nonce ?>');
		data.append('status', toogle_status.checked);
		const xhr = new XMLHttpRequest();
		xhr.open("post", '<?= AJAX_URL ?>', true);
		xhr.send(data);
		xhr.onload = () => {}
	}
</script>