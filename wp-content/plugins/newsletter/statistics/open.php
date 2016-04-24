<?php
global $wpdb;

if (!defined('ABSPATH')) {
    require_once '../../../../wp-load.php';
}
list($email_id, $user_id) = explode(';', base64_decode($_GET['r']), 2);
$ip = preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] );
$wpdb->insert(NEWSLETTER_STATS_TABLE, array(
    'email_id' => $email_id,
    'user_id' => $user_id,
    'ip' => $ip
        )
);

$wpdb->query($wpdb->prepare("update " . NEWSLETTER_SENT_TABLE . " set open=1, ip=%s where email_id=%d and user_id=%d and open=0 limit 1", $ip, $email_id, $user_id));

header('Content-Type: image/gif');
echo base64_decode('_R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
die();

