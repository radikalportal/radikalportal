<?php

require_once NEWSLETTER_INCLUDES_DIR . '/module.php';

class NewsletterStatistics extends NewsletterModule {

    static $instance;

    /**
     * @return NewsletterStatistics
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new NewsletterStatistics();
        }
        return self::$instance;
    }

    function __construct() {
        global $wpdb;

        parent::__construct('statistics', '1.1.6');

        add_action('wp_loaded', array($this, 'hook_wp_loaded'));
    }

    function hook_wp_loaded() {
        global $wpdb;

        // Newsletter Link Tracking
        if (isset($_GET['nltr'])) {
            $_GET['r'] = $_GET['nltr'];
            include dirname(__FILE__) . '/link.php';
            die();
        }

        // Newsletter Open Traking Image
        if (isset($_GET['noti'])) {
            list($email_id, $user_id) = explode(';', base64_decode($_GET['noti']), 2);

            $wpdb->insert(NEWSLETTER_STATS_TABLE, array(
                'email_id' => (int)$email_id,
                'user_id' => (int)$user_id,
                'ip' => $_SERVER['REMOTE_ADDR'])
            );

            header('Content-Type: image/gif');
            echo base64_decode('_R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            die();
        }

    }

    function upgrade() {
        global $wpdb, $charset_collate;

        parent::upgrade();

        // This before table creation or update for compatibility
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats change column newsletter_id user_id int not null default 0");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats change column newsletter_id user_id int not null default 0");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats change column date created timestamp not null default current_timestamp");

        // Just for test since it will be part of statistics module
        // This table stores clicks and email opens. An open is registered with a empty url.
        $this->upgrade_query("create table if not exists {$wpdb->prefix}newsletter_stats (id int auto_increment, primary key (id)) $charset_collate");

        // References
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column user_id int not null default 0");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column email_id int not null default 0");
        // Future... see the links table
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column link_id int not null default 0");

        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column created timestamp not null default current_timestamp");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column url varchar(255) not null default ''");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column anchor varchar(200) not null default ''");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column ip varchar(20) not null default ''");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_stats add column country varchar(4) not null default ''");

        $this->upgrade_query("ALTER TABLE `{$wpdb->prefix}newsletter_stats` ADD INDEX `email_id` (`email_id`)");
        $this->upgrade_query("ALTER TABLE `{$wpdb->prefix}newsletter_stats` ADD INDEX `user_id` (`user_id`)");

        if (empty($this->options['key'])) {
            $this->options['key'] = md5($_SERVER['REMOTE_ADDR'] . rand(100000, 999999) . time());
            $this->save_options($this->options);
        }

        $this->upgrade_query("ALTER TABLE `{$wpdb->prefix}newsletter_emails` ADD COLUMN `open_count` int UNSIGNED NOT NULL DEFAULT 0");
        $this->upgrade_query("ALTER TABLE `{$wpdb->prefix}newsletter_emails` ADD COLUMN `click_count`  int UNSIGNED NOT NULL DEFAULT 0");
        $this->upgrade_query("alter table {$wpdb->prefix}newsletter_emails change column read_count open_count int not null default 0");

    }

    function admin_menu() {
        $this->add_admin_page('index', 'Statistics');
        $this->add_admin_page('view', 'Statistics');
        $this->add_admin_page('newsletters', 'Statistics');
        $this->add_admin_page('settings', 'Statistics');
        $this->add_admin_page('view_retarget', 'Statistics');
        $this->add_admin_page('view_urls', 'Statistics');
        $this->add_admin_page('view_users', 'Statistics');
    }

    function relink($text, $email_id, $user_id) {
        $this->relink_email_id = $email_id;
        $this->relink_user_id = $user_id;
        $text = preg_replace_callback('/(<[aA][^>]+href=["\'])([^>"\']+)(["\'][^>]*>)(.*?)(<\/[Aa]>)/', array($this, 'relink_callback'), $text);

        $text = str_replace('</body>', '<img width="1" height="1" alt="" src="' . home_url('/') . '?noti=' . urlencode(base64_encode($email_id . ';' . $user_id)) . '"/></body>', $text);
        return $text;
    }

    function relink_callback($matches) {
        $href = str_replace('&amp;', '&', $matches[2]);

        // Do not replace the tracking or subscription/unsubscription links.
        if (strpos($href, '/newsletter/') !== false) {
            return $matches[0];
        }

        if (strpos($href, '?na=') !== false) {
            return $matches[0];
        }

        // Do not relink anchors
        if (substr($href, 0, 1) == '#') {
            return $matches[0];
        }
        // Do not relink mailto:
        if (substr($href, 0, 7) == 'mailto:') {
            return $matches[0];
        }

        // This is the link text which is added to the tracking data
        $anchor = '';
//        if ($this->options['anchor'] == 1) {
//            $anchor = trim(str_replace(';', ' ', $matches[4]));
//            // Keep images but not other tags
//            $anchor = strip_tags($anchor, '<img>');
//
//            // Truncate if needed to avoid to much long URLs
//            if (stripos($anchor, '<img') === false && strlen($anchor) > 100) {
//                $anchor = substr($anchor, 0, 100);
//            }
//        }
        $r = $this->relink_email_id . ';' . $this->relink_user_id . ';' . $href . ';' . $anchor;
        $r = $r . ';' . md5($r . $this->options['key']);
        $r = base64_encode($r);
        $r = urlencode($r);

        $url = home_url('/') . '?nltr=' . $r;

        return $matches[1] . $url . $matches[3] . $matches[4] . $matches[5];
    }

    function get_statistics_url($email_id) {
        $page = apply_filters('newsletter_statistics_view', 'newsletter_statistics_view');
        return 'admin.php?page=' . $page . '&amp;id=' . $email_id;
    }

}

NewsletterStatistics::instance();

