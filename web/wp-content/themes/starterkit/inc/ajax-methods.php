<?php
add_action( 'wp_ajax_contact', 'contact_callback' );
add_action( 'wp_ajax_nopriv_contact', 'contact_callback' );
function contact_callback()
{
    // Security
    checkNonce('contactNonce');
   
    $status = 200;
    $message = "";

    function field($field, $name, $regex, $filter){
        $field = isset($field) ? filter_var($field, FILTER_SANITIZE_STRING) : '';
        $return = "";
        if(empty($field)){
            $return .= "Le Champ $name est obligatoire.";
        } 
        elseif($regex && !preg_match($regex, $field) || !filter_var($field, $filter)){
            $return .= "Le format du champ $name n'est pas valide.";
        }
        return empty($return) ? "" : $return . "<br>";
    }

    $message .= field($_POST['name'], "Nom", "/^[a-zA-Z-' ]*$/", FILTER_SANITIZE_STRING);
    $message .= field($_POST['email'], "Email", NULL, FILTER_VALIDATE_EMAIL);
    $message .= field($_POST['message'], "Message", "/^[a-zA-Z-' ]*$/", FILTER_SANITIZE_STRING);

    if(empty($message)){
        $message = "Votre message à été envoyé";
/*

  $to = get_field('params_contact_application_email', 'option');

        $subject = 'Nouveau message de : ' . $lastname . ' ' . $firstname;
        $msg = 'Nouveau message <br>';
        $headers = 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: Atream <site@atream.fr>' . "\r\n";

        $msg .= 'Nom : ' . $lastname . ' <br>';
        $msg .= 'Prénom : ' . $firstname . ' <br>';
        $msg .= 'Email : ' . $email . ' <br>';
        $msg .= 'Tél : ' . $phone . ' <br>';
        $msg .= 'je suis... : ' . $iam . ' <br>';
        $msg .= 'je souhaite... : ' . $iwant . ' <br>';
        $msg .= 'Message : ' . $message . ' <br>';

        if( wp_mail($to, $subject, $msg, $headers) ){
            $status = 200;
            $response['msg'] = __('Nous vous répondrons dans les plus brefs délais. ', 'lsd_lang');
        }else{
            $response['msg'] = __('Erreur lors de l\'envoi du mail.', 'lsd_lang');
        }

        */
        
    }else{
        $status = 300;
    }
 
    $response['status'] = $status;
    $response['message'] = $message;

    wp_send_json( $response );
}

add_action( 'wp_ajax_rgpd', 'rgpd_callback' );
add_action( 'wp_ajax_nopriv_rgpd', 'rgpd_callback' );
function rgpd_callback()
{
    // Security
    checkNonce('securite_nonce_rgpd');

    /**
     * Si inexistante, on créée la table SQL "commissions" après l'activation du thème
     */
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $rgpd_table_name = $wpdb->prefix . 'rgpd';

    $create_table_rgpd_sql = "CREATE TABLE IF NOT EXISTS $rgpd_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(45) DEFAULT NULL,
        time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_rgpd_sql);

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $wpdb->get_results("INSERT INTO $rgpd_table_name (ip, time) VALUES ('$ip', (SELECT TIMESTAMP(\"YYYY-MM-DD\", \"HH:MM\")));");

    $response['status'] = 200;
    $response['message'] = "OK";

    wp_send_json( $response );
}