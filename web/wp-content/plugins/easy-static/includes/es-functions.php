<?php

// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
// Add a new top level menu link to the ACP
add_action('admin_menu', 'mfp_Add_My_Admin_Link');
function mfp_Add_My_Admin_Link()
{
    add_menu_page(
        'Easy static', // Title of the page
        'Easy static', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'easy-static/includes/es-index.php', // The 'slug' - file to display when clicking the link
        '',
        'dashicons-text-page',
    );
}


// helpers
class TinyHtmlMinifier
{
    private $options;
    private $output;
    private $build;
    private $skip;
    private $skipName;
    private $head;
    private $elements;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->output = '';
        $this->build = [];
        $this->skip = 0;
        $this->skipName = '';
        $this->head = false;
        $this->elements = [
            'skip' => [
                'code',
                'pre',
                'script',
                'textarea',
            ],
            'inline' => [
                'a',
                'abbr',
                'acronym',
                'b',
                'bdo',
                'big',
                'br',
                'cite',
                'code',
                'dfn',
                'em',
                'i',
                'img',
                'kbd',
                'map',
                'object',
                'samp',
                'small',
                'span',
                'strong',
                'sub',
                'sup',
                'tt',
                'var',
                'q',
            ],
            'hard' => [
                '!doctype',
                'body',
                'html',
            ]
        ];
    }

    // Run minifier
    public function minify(string $html): string
    {
        if (
            !isset($this->options['disable_comments']) ||
            !$this->options['disable_comments']
        ) {
            $html = $this->removeComments($html);
        }

        $rest = $html;

        while (!empty($rest)) {
            $parts = explode('<', $rest, 2);
            $this->walk($parts[0]);
            $rest = (isset($parts[1])) ? $parts[1] : '';
        }

        return $this->output;
    }

    // Walk trough html
    private function walk(&$part)
    {
        $tag_parts = explode('>', $part);
        $tag_content = $tag_parts[0];

        if (!empty($tag_content)) {
            $name = $this->findName($tag_content);
            $element = $this->toElement($tag_content, $part, $name);
            $type = $this->toType($element);

            if ($name == 'head') {
                $this->head = $type === 'open';
            }

            $this->build[] = [
                'name' => $name,
                'content' => $element,
                'type' => $type
            ];

            $this->setSkip($name, $type);

            if (!empty($tag_content)) {
                $content = (isset($tag_parts[1])) ? $tag_parts[1] : '';
                if ($content !== '') {
                    $this->build[] = [
                        'content' => $this->compact($content, $name, $element),
                        'type' => 'content'
                    ];
                }
            }

            $this->buildHtml();
        }
    }

    // Remove comments
    private function removeComments($content = '')
    {
        return preg_replace('/(?=<!--)([\s\S]*?)-->/', '', $content);
    }

    // Check if string contains string
    private function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }

    // Return type of element
    private function toType($element)
    {
        return (substr($element, 1, 1) == '/') ? 'close' : 'open';
    }

    // Create element
    private function toElement($element, $noll, $name)
    {
        $element = $this->stripWhitespace($element);
        $element = $this->addChevrons($element, $noll);
        $element = $this->removeSelfSlash($element);
        $element = $this->removeMeta($element, $name);
        return $element;
    }

    // Remove unneeded element meta
    private function removeMeta($element, $name)
    {
        if ($name == 'style') {
            $element = str_replace(
                [
                    ' type="text/css"',
                    "' type='text/css'"
                ],
                ['', ''],
                $element
            );
        } elseif ($name == 'script') {
            $element = str_replace(
                [
                    ' type="text/javascript"',
                    " type='text/javascript'"
                ],
                ['', ''],
                $element
            );
        }
        return $element;
    }

    // Strip whitespace from element
    private function stripWhitespace($element)
    {
        if ($this->skip == 0) {
            $element = preg_replace('/\s+/', ' ', $element);
        }
        return trim($element);
    }

    // Add chevrons around element
    private function addChevrons($element, $noll)
    {
        if (empty($element)) {
            return $element;
        }
        $char = ($this->contains('>', $noll)) ? '>' : '';
        $element = '<' . $element . $char;
        return $element;
    }

    // Remove unneeded self slash
    private function removeSelfSlash($element)
    {
        // commented because remove / in svg element
        /* if (substr($element, -3) == ' />') {
            $element = substr($element, 0, -3) . '>';
        }*/
        return $element;
    }

    // Compact content
    private function compact($content, $name, $element)
    {
        if ($this->skip != 0) {
            $name = $this->skipName;
        } else {
            $content = preg_replace('/\s+/', ' ', $content);
        }

        if (in_array($name, $this->elements['skip'])) {
            return $content;
        } elseif (
            in_array($name, $this->elements['hard']) ||
            $this->head
        ) {
            return $this->minifyHard($content);
        } else {
            return $this->minifyKeepSpaces($content);
        }
    }

    // Build html
    private function buildHtml()
    {
        foreach ($this->build as $build) {

            if (!empty($this->options['collapse_whitespace'])) {

                if (strlen(trim($build['content'])) == 0)
                    continue;

                elseif ($build['type'] != 'content' && !in_array($build['name'], $this->elements['inline']))
                    trim($build['content']);
            }

            $this->output .= $build['content'];
        }

        $this->build = [];
    }

    // Find name by part
    private function findName($part)
    {
        $name_cut = explode(" ", $part, 2)[0];
        $name_cut = explode(">", $name_cut, 2)[0];
        $name_cut = explode("\n", $name_cut, 2)[0];
        $name_cut = preg_replace('/\s+/', '', $name_cut);
        $name_cut = strtolower(str_replace('/', '', $name_cut));
        return $name_cut;
    }

    // Set skip if elements are blocked from minification
    private function setSkip($name, $type)
    {
        foreach ($this->elements['skip'] as $element) {
            if ($element == $name && $this->skip == 0) {
                $this->skipName = $name;
            }
        }
        if (in_array($name, $this->elements['skip'])) {
            if ($type == 'open') {
                $this->skip++;
            }
            if ($type == 'close') {
                $this->skip--;
            }
        }
    }

    // Minify all, even spaces between elements
    private function minifyHard($element)
    {
        $element = preg_replace('!\s+!', ' ', $element);
        $element = trim($element);
        return trim($element);
    }

    // Strip but keep one space
    private function minifyKeepSpaces($element)
    {
        return preg_replace('!\s+!', ' ', $element);
    }
}
class TinyMinify
{
    public static function html(string $html, array $options = []): string
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}
function rm_rf($path)
{
    if (@is_dir($path) && is_writable($path)) {
        $dp = opendir($path);
        while ($ent = readdir($dp)) {
            if ($ent == '.' || $ent == '..') {
                continue;
            }
            $file = $path . DIRECTORY_SEPARATOR . $ent;
            if (@is_dir($file)) {
                rm_rf($file);
            } elseif (is_writable($file)) {
                unlink($file);
            } else {
                echo $file . "is not writable and cannot be removed. Please fix the permission or select a new path.\n";
            }
        }
        closedir($dp);
        return rmdir($path);
    } else {
        return @unlink($path);
    }
}
function copyfolder($from, $to)
{
    // (A1) SOURCE FOLDER CHECK
    if (!is_dir($from)) {
        exit("$from does not exist");
    }

    // (A2) CREATE DESTINATION FOLDER
    if (!is_dir($to)) {
        if (!mkdir($to)) {
            exit("Failed to create $to");
        };
        echo "$to created\r\n";
    }

    // (A3) COPY FILES + RECURSIVE INTERNAL FOLDERS
    $dir = opendir($from);
    while (($ff = readdir($dir)) !== false) {
        if ($ff != "." && $ff != "..") {
            if (is_dir("$from$ff")) {
                copyfolder("$from$ff/", "$to$ff/");
            } else {
                if (!copy("$from$ff", "$to$ff")) {
                    exit("Error copying $from$ff to $to$ff");
                }
                echo "$from$ff copied to $to$ff\r\n";
            }
        }
    }
    closedir($dir);
}

//
/*
function zipFolder($rootPath, $filefinal)
{

    $zip = new ZipArchive();
    $zip->open($filefinal, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
}
*/

//
function loadPage($file)
{
    global $host;
    global $authentification;

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
        /*'http' => array (
        	'header' => 'Authorization: Basic ' . base64_encode("groupama-ra-2022:aer3aech7Aequ6ae")
    	)*/
        /*  'header' =>
            "Accept-language: en"*/
    );

    if ($authentification['active'] === true) {
        $user_pass = $authentification["user"] . ':' . $authentification["password"];
        $arrContextOptions['http'] =  array(
            'header' => array(
                'Authorization: Basic ' . base64_encode($user_pass),
                //"Accept-language: fr"
            )
        );
   }

    $html = file_get_contents($file, false, stream_context_create($arrContextOptions));

    /*if ($host !== $_SERVER['SERVER_NAME']) {
        $html = str_replace($host, $_SERVER['SERVER_NAME'], $html);
    }*/

    if (ENV_LOCAL) {
        $html = str_replace($host, $_SERVER['SERVER_NAME'], $html);
    }
    
    return $html;

    //return file_get_contents($url, false, stream_context_create($arrContextOptions));
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

function locale()
{
    $locale = "";
    if (defined("ICL_LANGUAGE_CODE")) {
        $locale = ICL_LANGUAGE_CODE . "/";
    }
    return $locale;
}

function tr($posts, $post_types)
{
    global  $home_folder;
    $markup = "";
    foreach ($posts as $post) {
        //  $origin = date_create($post->post_modified);
        //   $target = date_create($post->static_generate);
        //  $upToDate = $origin < $target ? true : false;
        $slug = $post->post_name;

        if (in_array($post->post_type, $post_types)) {
            $post_type_object = get_post_type_object($post->post_type);
            $slug = $post_type_object->rewrite['slug'] . "/" . $post->post_name;
        }
        if ($post->post_parent) {
            $parent_slug = get_post_field('post_name', $post->post_parent);
            $slug = $parent_slug . "/" . $post->post_name;
        }
        /* if ($slug === 'homepage') {
            $exist  = (file_exists(WP_CONTENT_DIR . '/easy-static/static/' . locale() . '/index.html'))  ? true : false;
        } else {
            $exist  = (file_exists(WP_CONTENT_DIR . '/easy-static/static/' . locale() . $slug . '/index.html'))  ? true : false;
        }*/
        $markup .= '<tr>';
        if ($slug === $home_folder) {
            $markup .= '<td><a href="/" target="_blank">' . $post->post_title . '</a></td>';
            $markup .= "<td>/</td>";
        } else {
            $markup .= '<td><a href="/' . locale() . $slug . '/" target="_blank">' . $post->post_title . '</a></td>';
            $markup .= "<td>" . locale() . $slug  . "</td>";
        }



        $markup .= "<td>" . $post->post_type  . "</td>";
        $markup .= '<td><input data-slug="' . $slug . '" type="checkbox" ' . ($post->static_active ? "checked" : "") . ' name="page-' . $post->ID . '" value="' . $post->static_active  . '" class="checkbox-static_active" id="' . $post->ID . '" ></td>';
        // $markup .= (($upToDate && $exist) ?  '<td class="info-update"></td>' : '<td class="info-update error"></td>');
        $markup .= "</tr>";
    }
    return $markup;
}

function postTypes()
{

    $args = array(
        'public'   => true,
        '_builtin' => false,
    );
    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    return get_post_types($args, $output, $operator);
}

function display()
{
    $post_types = postTypes();
    $posts = queryPosts();
    return tr($posts, $post_types);
}

function upToDate($posts)
{
    global $table_prefix;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));

    foreach ($posts as $post) {
        $sql = "UPDATE " . $table_prefix . "posts SET static_generate = CURRENT_TIMESTAMP WHERE ID = " . $post->ID;
        mysqli_query($link, $sql);
    }
    mysqli_close($link);
}

function setupToDate($id)
{
    global $table_prefix;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table_prefix . "posts SET static_generate = CURRENT_TIMESTAMP WHERE ID = " . $id;
    mysqli_query($link, $sql);
    mysqli_close($link);
}

// cpts pages pagination generate
function ctpPages($post_type)
{
    global $host;

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'order' => 'ID',
        'orderby' => 'title',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
    );
    $queryArticles = new WP_Query($args);
    $posts_per_page = get_option('posts_per_page');
    $totalPages = ceil($queryArticles->post_count / $posts_per_page);

    $post_type_object = get_post_type_object($post_type);
    $slug = $post_type_object->rewrite['slug'];
    $has_pagination = $post_type_object->has_pagination;

    if ($has_pagination) {
        for ($i = 1; $i <= $totalPages; $i++) {
            $pp = locale() . $slug . "/page/" . $i . "/";
            $html = loadPage("https://" . $host . "/" . $pp . "?generate=true");
            mkdir(WP_CONTENT_DIR . '/easy-static/static/' .  $pp, 0755, true);
            file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . $pp . 'index.html', TinyMinify::html($html));
        }
    }
}

/**
 * Save post or page 
 */
function save($post)
{
    global $host;
    global $home_folder;
    //TODO regenere parent et/ou page avec pager
    if ($post->static_active) {

        $folder = $post->post_name . "/";
        /*if (in_array($post->post_type, $post_types)) {
            $post_type_object = get_post_type_object($post->post_type);
            $folder =  $post_type_object->rewrite['slug'] . "/" . $post->post_name . "/";
        }*/

        /*if ($post->post_parent) {
            $parent_slug = get_post_field('post_name', $post->post_parent);
            $folder =  $parent_slug . "/" . $post->post_name . "/";
        }*/

        if ($folder === $home_folder . "/") {
            $html = loadPage("https://" . $host . "/" . locale() . "?generate=true");
            file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', TinyMinify::html($html));
        } else {
            $html = loadPage("https://" . $host . "/" . locale() . $folder . "?generate=true");
            if (!is_dir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder)) {
                mkdir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder, 0755, true);
            }
            file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', TinyMinify::html($html));
        }
    }
}

/**
 * 
 */
function create($posts, $post_types)
{
    global $host;
    global $home_folder;

    rm_rf(WP_CONTENT_DIR . '/easy-static/static/' . locale());
    mkdir(WP_CONTENT_DIR . '/easy-static/static/' . locale(), 0755, true);

    // create pages pagination
    foreach ($post_types as $post_type) {
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object->has_pagination) {
            ctpPages($post_type);
        }
    }

    // create folders and files
    foreach ($posts as $post) {
        if ($post->static_active) {

            $folder = $post->post_name . "/";
            if (in_array($post->post_type, $post_types)) {
                $post_type_object = get_post_type_object($post->post_type);
                $folder =  $post_type_object->rewrite['slug'] . "/" . $post->post_name . "/";
            }

            if ($post->post_parent) {
                $parent_slug = get_post_field('post_name', $post->post_parent);
                $folder =  $parent_slug . "/" . $post->post_name . "/";
            }

            if ($folder === $home_folder . "/") {
                $html = loadPage("https://" . $host . "/" . locale() . "?generate=true");
                file_put_contents(WP_CONTENT_DIR . '/easy-static/static/' . locale() . 'index.html', TinyMinify::html($html));
            } else {
                $html = loadPage("https://" . $host . "/" . locale() . $folder . "?generate=true");
                mkdir(WP_CONTENT_DIR . "/easy-static/static/" . locale() . $folder, 0755, true);
                file_put_contents(WP_CONTENT_DIR . "/easy-static/static/" .  locale() . $folder . 'index.html', TinyMinify::html($html));
            }
        }
    }
}
