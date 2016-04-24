<?php
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$module = NewsletterStatistics::instance();
$controls = new NewsletterControls();
$emails = Newsletter::instance()->get_emails();

$types = $wpdb->get_results("select distinct type from " . NEWSLETTER_EMAILS_TABLE);
$type_options = array();
foreach ($types as $type) {
    if ($type->type == 'followup')
        continue;
    if ($type->type == 'message') {
        $type_options[$type->type] = 'Standard Newsletter';
    } else if ($type->type == 'feed') {
        $type_options[$type->type] = 'Feed by Mail';
    } else if (strpos($type->type, 'automated') === 0) {
        list($a, $id) = explode('_', $type->type);
        $type_options[$type->type] = 'Automated Channel ' . $id;
    } else {
        $type_options[$type->type] = $type->type;
    }
}

function percent($value, $total) {
    if ($total == 0)
        return '-';
    return sprintf("%.2f", $value / $total * 100) . '%';
}

function percentValue($value, $total) {
    if ($total == 0)
        return 0;
    return round($value / $total * 100);
}
?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2><?php _e('Newsletters', 'newsletter') ?></h2>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <?php $controls->init(); ?>

            <table class="widefat">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th><?php _e('Subject', 'newsletter') ?></th>
                        <th>Type</th>
                        <th><?php _e('Status', 'newsletter') ?></th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th><?php _e('Tracking', 'newsletter') ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($emails as &$email) { ?>
                        <?php if ($email->type != 'message' && $email->type != 'feed') continue; ?>
                        <tr>
                            <td><?php echo $email->id; ?></td>
                            <td><?php echo htmlspecialchars($email->subject); ?></td>
                            <td><?php echo $module->get_email_type_label($email) ?></td>
                            <td><?php echo $module->get_email_status_label($email)?></td>
                            <td><?php if ($email->status == 'sent' || $email->status == 'sending') echo $email->sent . ' ' . __('of', 'newsletter') . ' ' . $email->total; ?></td>
                            <td><?php if ($email->status == 'sent' || $email->status == 'sending') echo $module->format_date($email->send_on); ?></td>
                            <td><?php echo $email->track == 1 ? 'Yes' : 'No'; ?></td>
                            <td>
                                <a class="button" href="<?php echo NewsletterStatistics::instance()->get_statistics_url($email->id); ?>">statistics</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>
</div>
