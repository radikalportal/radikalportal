<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$module = Newsletter::instance();
$controls = new NewsletterControls();

if ($controls->is_action('save')) {
    update_option('newsletter_log_level', $controls->data['log_level']);
    update_option('newsletter_diagnostic', $controls->data);
    $controls->messages = 'Loggin levels saved.';
}

if ($controls->is_action('reset_cron_calls')) {
    update_option($module->prefix . '_cron_calls', false);
    $controls->messages = 'Reset.';
}
if ($controls->is_action('check-versions')) {
    $newsletter->hook_newsletter_extension_versions(true);
    $controls->messages = 'Extensions data updated. Go to the plugins panel to see if there are updates available.';
}

if ($controls->is_action('trigger')) {
    $newsletter->hook_newsletter();
    $controls->messages = 'Delivery engine triggered.';
}

if ($controls->is_action('undismiss')) {
    update_option('newsletter_dismissed', array());
    $controls->messages = 'Notices restored.';
}

if ($controls->is_action('trigger_followup')) {
    NewsletterFollowup::instance()->send();
    $controls->messages = 'Follow up delivery engine triggered.';
}

if ($controls->is_action('engine_on')) {
    wp_clear_scheduled_hook('newsletter');
    wp_schedule_event(time() + 30, 'newsletter', 'newsletter');
    $controls->messages = 'Delivery engine reactivated.';
}

if ($controls->is_action('upgrade')) {
    // TODO: Compact them in a call to Newsletter which should be able to manage the installed modules
    Newsletter::instance()->upgrade();
    NewsletterUsers::instance()->upgrade();
    NewsletterSubscription::instance()->upgrade();
    NewsletterEmails::instance()->upgrade();
    NewsletterStatistics::instance()->upgrade();
    if (method_exists('NewsletterFollowup', 'upgrade')) {
        NewsletterFollowup::instance()->upgrade();
    }
    $controls->messages = 'Upgrade forced!';
}

if ($controls->is_action('upgrade_old')) {
    $row = $wpdb->get_row("select * from " . NEWSLETTER_USERS_TABLE . " limit 1");
    if (!isset($row->id)) {
        $row = $wpdb->query("alter table " . NEWSLETTER_USERS_TABLE . " drop primary key");
        $row = $wpdb->query("alter table " . NEWSLETTER_USERS_TABLE . " add column id int not null auto_increment primary key");
        $row = $wpdb->query("alter table " . NEWSLETTER_USERS_TABLE . " add unique email (email)");
    }
    $controls->messages = 'Done.';
}

if ($controls->is_action('delete_transient')) {
    delete_transient($_POST['btn']);
    // Found blogs where timeout has been lost and the transient never deleted
    delete_option('_transient_newsletter_main_engine');
    delete_option('_transient_timeout_newsletter_main_engine');
    $controls->messages = 'Deleted.';
}

if ($controls->is_action('test')) {

    if (!NewsletterModule::is_email($controls->data['test_email'])) {
        $controls->errors = 'The test email address is not set or is not correct.';
    }

    if (empty($controls->errors)) {

        if ($controls->data['test_email'] == $module->options['sender_email']) {
            $controls->messages .= '<strong>Warning:</strong> you are using as test email the same address configured as sender in main configuration. Test can fail because of that.<br>';
        }

        // Direct WordPress email
        $text = 'This is a simple test email sent directly with the WordPress mailing functionality' . "\r\n" .
                'in the same way WordPress sends notifications of new comment or registered users.' . "\r\n\r\n" .
                'This email is in pure text format and the sender should be wordpress@youdomain.tld' . "\r\n" .
                '(but it can be forced to be different with specific plugins.';

        $r = wp_mail($controls->data['test_email'], 'WordPress test email at ' . date(DATE_ISO8601), $text);
        $controls->messages .= 'Email sent with WordPress: ';
        if ($r) {
            $controls->messages .= '<strong>SUCCESS</strong><br>';
        } else {
            global $phpmailer;
            $controls->messages .= '<strong>FAILED</strong> (' . $phpmailer->ErrorInfo . ')<br>';
        }

        // Newsletter mail 
        $text = array();
        $text['html'] = '<p>This is an <b>HTML</b> test email sent using the sender data set on Newsletter main setting. <a href="http://www.thenewsletterplugin.com">This is a link to an external site</a>.</p>';
        $text['text'] = 'This is a textual test email part sent using the sender data set on Newsletter main setting.';
        $r = $module->mail($controls->data['test_email'], 'Newsletter test email at ' . date(DATE_ISO8601), $text);

        $controls->messages .= 'Email sent with Newsletter';
        if ($module->mail_method) {
            $controls->messages .= ' (with a mail delivery extension)';
        } else {
            $smtp_options = $module->get_smtp_options();

            if (!empty($smtp_options['enabled'])) {
                $controls->messages .= ' (with an SMTP)';
            }
        }
        $controls->messages .= ': ';

        if ($r) {
            $controls->messages .= '<strong>SUCCESS</strong><br>';
        } else {
            $controls->messages .= '<strong>FAILED</strong> (' . $module->mail_last_error . ')<br>';

            if ($module->mail_method) {
                $controls->messages .= '- You are using a mail delivery extension. Check and test its configuration.<br>';
            } else {
                $smtp_options = $module->get_smtp_options();
                if (!empty($smtp_options['enabled'])) {
                    $controls->messages .= '- You are using an SMTP (' . $smtp_options['host'] . '). Check its configuration on main configuration or on SMTP Newsletter extensions if used.<br>';
                }
            }

            if (!empty($module->options['return_path'])) {
                $controls->messages .= '- Try to remove the return path on main settings.<br>';
            }

            $parts = explode('@', $module->options['sender_email']);
            $sitename = strtolower($_SERVER['SERVER_NAME']);
            if (substr($sitename, 0, 4) == 'www.') {
                $sitename = substr($sitename, 4);
            }
            if (strtolower($sitename) != strtolower($parts[1])) {
                $controls->messages .= '- Try to set on main setting a sender address with the same domain of your blog: ' . $sitename . ' (you are using ' . $module->options['sender_email'] . ')<br>';
            }
        }
    }
}

if (empty($controls->data)) {
    $controls->data = get_option('newsletter_diagnostic');
}

$calls = get_option('newsletter_diagnostic_cron_calls', array());

if (count($calls) > 1) {
    $mean = 0;
    $max = 0;
    $min = 0;
    for ($i = 1; $i < count($calls); $i++) {
        $diff = $calls[$i] - $calls[$i - 1];
        $mean += $diff;
        if ($min == 0 || $min > $diff) {
            $min = $diff;
        }
        if ($max < $diff) {
            $max = $diff;
        }
    }
    $mean = $mean / count($calls) - 1;
}
?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2>Newsletter Diagnostic</h2>
        <p>
            If something is not working, here are some test procedures and diagnostics. But before you try these,
            write down any configuration changes that you may have made.
            For example: Did you use sender email or name? What was the return path? What was the reply to?
        </p>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <?php $controls->init(); ?>



            <div id="tabs">

                <ul>
                    <li><a href="#tabs-tests">Tests</a></li>
                    <li><a href="#tabs-logging">Logging</a></li>
                    <li><a href="#tabs-2">Semaphores and Crons</a></li>
                    <li><a href="#tabs-4">System</a></li>
                    <li><a href="#tabs-upgrade">Maintenance</a></li>
                    <?php if (isset($_GET['debug'])) { ?>
                        <li><a href="#tabs-debug">Debug Data</a></li>
                    <?php } ?>
                </ul>

                <!-- TESTS -->
                <div id="tabs-tests">
                    <p>Here you can test if the blog is able to send emails reliabily.</p>

                    <p>Email address where to send test messages: <?php $controls->text('test_email', 50); ?></p>

                    <p>
                        <?php //$controls->button('test_wp', 'Send an email with WordPress');  ?>
                        <?php $controls->button('test', 'Send few test emails'); ?>
                    </p>

                    <p class="description">
                        First test emailing with WordPress if it does not work you need to contact your provider. Test on different addresses.
                        <br>
                        Second test emailing with Newsletter. You must receive three distinct email in different formats.
                        <br>
                        If the WordPress test works but Newsletter test doesn't, check the main configuration and try to change the sender,
                        return path and reply to email addresses.
                    </p>
                </div>

                <!-- LOGGING -->
                <div id="tabs-logging">

                    <p>
                        The logging feature of Newsletter, when enabled, writes detailed information of the working
                        status inside few (so called) log files. Log files, one per module, are stored inside the folder
                        <code>wp-content/logs/newsletter</code>.
                    </p>

                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Active since</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    Log level
                                </td>
                                <td>
                                    <?php $controls->log_level('log_level'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Log folder
                                </td>
                                <td>
                                    <?php
                                    if (!is_dir(NEWSLETTER_LOG_DIR)) {
                                        echo '<span class="newsletter-error-span">The log folder does not exists, no logging possible!</span>';
                                    } else {
                                        echo 'The log folder exists.';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p><?php $controls->button_save(); ?></p>
                </div>

                <!-- SEMAPHORES -->
                <div id="tabs-2">
                    <h4>Semaphores</h4>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Active since</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    Newsletter delivery
                                </td>
                                <td>
                                    <?php
                                    $value = get_transient('newsletter_main_engine');
                                    if ($value)
                                        echo (time() - $value) . ' seconds';
                                    else
                                        echo 'Not set';
                                    ?>
                                    <?php $controls->button('delete_transient', 'Delete', null, 'newsletter_main_engine'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>Crons</h4>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Function</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    WordPress Cron System
                                </td>
                                <td>
                                    <?php
                                    if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON)
                                        echo 'DISABLED. (can be a problem, see the <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-delivery-engine" target="_tab">delivery engine documentation</a>)';
                                    else
                                        echo "ENABLED. (it's ok)";
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    WordPress schedules
                                </td>
                                <td>
                                    <?php
                                    $schedules = wp_get_schedules();
                                    if (empty($schedules)) {
                                        echo 'Really bad, no schedules found, missing even the WordPress default schedules!';
                                    } else {
                                        $found = false;

                                        foreach ($schedules as $key => &$data) {
                                            if ($key == 'newsletter')
                                                $found = true;
                                            echo $key . ' - ' . $data['interval'] . ' s<br>';
                                        }

                                        if (!$found) {
                                            echo 'The "newsletter" schedule was not found, email delivery won\'t work.';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Delivery Engine
                                </td>
                                <td>
                                    <?php echo NewsletterModule::format_scheduler_time('newsletter'); ?>
                                    <?php $controls->button('trigger', 'Trigger now'); ?>
                                    <p class="description">
                                        If inactive or always in "running now" status your blog has a problem: <a href="http://www.thenewsletterplugin.com/how-to-make-the-wordpress-cron-work" target="_blank">read more here</a>.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>Collected samples</td>
                                <td>
                                    <?php echo count($calls); ?>
                                    <p class="description">Samples are collected in a maximum number of <?php echo Newsletter::MAX_CRON_SAMPLES; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Scheduler execution interval mean</td>
                                <td>
                                    <?php
                                    if (count($calls) > 10) {
                                        echo (int) $mean . ' seconds';
                                        if ($mean < NEWSLETTER_CRON_INTERVAL * 1.2) {
                                            echo ' (<span style="color: green; font-weight: bold">OK</span>)';
                                        } else {
                                            echo ' (<span style="color: red; font-weight: bold">KO</span>)';
                                        }
                                    } else {
                                        echo 'Still not enough data. It requires few hours to collect a relevant data set.';
                                    }
                                    ?>

                                    <p class="description">
                                        Should be less than <?php echo NEWSLETTER_CRON_INTERVAL; ?> seconds.
                                        <a href="http://www.thenewsletterplugin.com/plugins/newsletter/newsletter-delivery-engine" target="_blank">Read more</a>.
                                    </p>

                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- SYSTEM -->
                <div id="tabs-4">

                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PHP Version</td>
                                <td>
                                    <?php echo phpversion(); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Database Wait Timeout</td>
                                <td>
                                    <?php $wait_timeout = $wpdb->get_var("select @@wait_timeout"); ?>
                                    <?php echo $wait_timeout; ?> (seconds)
                                </td>
                            </tr>
                            <tr>
                                <td>PHP Execution Time</td>
                                <td>
                                    <?php echo ini_get('max_execution_time'); ?> (seconds)
                                </td>
                            </tr>
                            <tr>
                                <td>NEWSLETTER_MAX_EXECUTION_TIME</td>
                                <td>
                                    <?php
                                    if (defined('NEWSLETTER_MAX_EXECUTION_TIME')) {
                                        echo NEWSLETTER_MAX_EXECUTION_TIME . ' (seconds)';
                                    } else {
                                        echo 'Not set';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>NEWSLETTER_CRON_INTERVAL</td>
                                <td>
                                    <?php echo NEWSLETTER_CRON_INTERVAL . ' (seconds)'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>PHP Memory Limit</td>
                                <td>
                                    <?php echo @ini_get('memory_limit'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>WordPress plugin url</td>
                                <td>
                                    <?php echo WP_PLUGIN_URL; ?>
                                    <br>
                                    Filters:

                                    <?php
                                    $filters = $wp_filter['plugins_url'];
                                    if (!is_array($filters))
                                        echo 'no filters attached to "plugin_urls"';
                                    else {
                                        echo '<ul>';
                                        foreach ($filters as &$filter) {
                                            foreach ($filter as &$entry) {
                                                echo '<li>';
                                                if (is_array($entry['function']))
                                                    echo get_class($entry['function'][0]) . '->' . $entry['function'][1];
                                                else
                                                    echo $entry['function'];
                                                echo '</li>';
                                            }
                                        }
                                        echo '</ul>';
                                    }
                                    ?>
                                    <p class="description">
                                        This value should contains the full URL to your plugin folder. If there are filters
                                        attached, the value can be different from the original generated by WordPress and sometime worng.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>Blog Charset</td>
                                <td>
                                    <?php echo get_option('blog_charset'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>WordPress Memory limit</td>
                                <td>
                                    <?php echo WP_MEMORY_LIMIT; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>WP_DEBUG</td>
                                <td>
                                    <?php echo WP_DEBUG ? 'true' : 'false'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Absolute path</td>
                                <td>
                                    <?php echo ABSPATH; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Tables Prefix</td>
                                <td>
                                    <?php echo $wpdb->prefix; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Database Charset and Collate</td>
                                <td>
                                    <?php echo DB_CHARSET; ?> <?php echo DB_COLLATE; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Action file accessibility (obsolete)</td>
                                <td>
                                    <?php
                                    $res = wp_remote_get(plugins_url('newsletter') . '/do/subscribe.php?test=1');
                                    if (is_wp_error($res)) {
                                        echo 'It seems the Newsletter action files are not reachable. See the note and the file permission check below.';
                                    } else {
                                        echo 'OK';
                                    }
                                    ?>
                                    <p class="description">
                                        If this internal test fails, subscription, confirmation and so on could fail. Try to open 
                                        <a href="<?php echo plugins_url('newsletter') . '/do/subscribe.php?test=1' ?>" target="_blank">this link</a>: if
                                        it reports "ok", consider this test as passed.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>File permissions (obsolete)</td>
                                <td>
                                    <?php
                                    $index_owner = fileowner(ABSPATH . '/index.php');
                                    $index_permissions = fileperms(ABSPATH . '/index.php');
                                    $subscribe_permissions = fileperms(NEWSLETTER_DIR . '/do/subscribe.php');
                                    $subscribe_owner = fileowner(NEWSLETTER_DIR . '/do/subscribe.php');
                                    if ($index_permissions != $subscribe_permissions || $index_owner != $subscribe_owner) {
                                        echo 'Plugin file permissions or owner differ from blog index.php permissions, that may compromise the subscription process';
                                    } else {
                                        echo 'OK';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div id="tabs-upgrade">
                    <p>
                        Plugin and modules are able to upgrade them self when needed. If you urgently need to try to force an upgrade, press the
                        button below.
                    </p>

                    <p>
                        <?php $controls->button('check-versions', 'Check for new extension versions'); ?>
                    </p>

                    <p>
                        <?php $controls->button('upgrade', 'Force an upgrade'); ?>
                    </p>

                    <p>
                        Restore al dismissed messages
                    </p>
                    <p>
                        <?php $controls->button('undismiss', 'Restore'); ?>
                    </p>

                    <p>
                        Very old versions need to be upgraded on a spacial way. Use the button blow.
                    </p>
                    <p>
                        <?php $controls->button('upgrade_old', 'Force an upgrade from very old versions'); ?>
                    </p>
                </div>

                <?php if (isset($_GET['debug'])) { ?>
                    <div id="tabs-debug">
                        <h3>Extension versions data</h3>
                        <pre style="font-size: 11px"><?php echo esc_html(print_r(get_option('newsletter_extension_versions'), true)); ?></pre>

                        <h3>Update plugins data</h3>
                        <pre style="font-size: 11px"><?php echo esc_html(print_r(get_site_transient('update_plugins'), true)); ?></pre>
                    </div>
                <?php } ?>
            </div>

        </form>

    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>