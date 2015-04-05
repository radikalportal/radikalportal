<?php

require_once '../../../../wp-load.php';

if (!is_user_logged_in()) die();
if (Newsletter::instance()->options['editor'] == 1 && !current_user_can('manage_categories')) die();
else if (!current_user_can('manage_options')) die();

require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterEmails::instance();

if ($controls->is_action('create')) {
    $module->save_options($controls->data);

    $email = array();
    $email['status'] = 'new';
    $email['subject'] = __('Here the email subject', 'newsletter-emails');
    $email['track'] = 1;

    $theme_options = $module->get_current_theme_options();

    $theme_url = $module->get_current_theme_url();
    $theme_subject = '';

    ob_start();
    include $module->get_current_theme_file_path('theme.php');
    $email['message'] = ob_get_clean();

    if (!empty($theme_subject)) {
        $email['subject'] = $theme_subject;
    }

    ob_start();
    include $module->get_current_theme_file_path('theme-text.php');
    $email['message_text'] = ob_get_clean();

    $email['type'] = 'message';
    $email['send_on'] = time();
    $email = Newsletter::instance()->save_email($email);

    header('Location: ' . $module->get_admin_page_url('edit') . '&id=' . $email->id);
}