<?php

/**
 * - Template Name: Page Contact
 */
ob_start();

?>

<main id="main" role="main">
    <section>
        <header>
            <h1>Contact</h1>
        </header>
        <?php views("contact", array());?>
    </section>
</main>

<?php
class test {
    function display(){
        echo "popo";
    }
}
$test = new test;
$test->display();

include("inc/tpl.php");
