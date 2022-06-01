<?php
ob_start();
echo get_template_part('pages/page', 'homepage');
include("inc/tpl.php");
