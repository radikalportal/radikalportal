<?php
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';

$module = NewsletterStatistics::instance();
$controls = new NewsletterControls();

$email_id = (int) $_GET['id'];
$email = $module->get_email($email_id);

if ($email->send_on == 0) {
    $wpdb->query($wpdb->prepare("update " . NEWSLETTER_EMAILS_TABLE . " set send_on=unix_timestamp(created) where id=%d limit 1", $email->id));
    $email = $module->get_email($email->id);
}

$count = $wpdb->get_var($wpdb->prepare("select count(*) from " . NEWSLETTER_SENT_TABLE . " where email_id=%d", $email_id));
if (true || $count == 0) {

    if (empty($email->query)) {
        $email->query = "select * from " . NEWSLETTER_USERS_TABLE . " where status='C'";
    }

    $query = str_replace('*', 'id, unix_timestamp(created) as created', $email->query);
    $ids = $wpdb->get_results($query . " and unix_timestamp(created)<" . $email->send_on);

    foreach ($ids as $id) {
        $wpdb->query($wpdb->prepare("insert ignore into " . $wpdb->prefix .
                        'newsletter_sent (user_id, email_id, time, status, error) values (%d, %d, %d, %d, %s)', $id->id, $email->id, $email->send_on, 0, ''));
    }

    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter_sent s1 join " . $wpdb->prefix . "newsletter_stats s2 on s1.user_id=s2.user_id and s1.email_id=s2.email_id and s1.email_id=%d set s1.open=1, s1.ip=s2.ip", $email->id));

    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter_sent s1 join " . $wpdb->prefix . "newsletter_stats s2 on s1.user_id=s2.user_id and s1.email_id=s2.email_id and s2.url<>'' and s1.email_id=%d set s1.open=2, s1.ip=s2.ip", $email->id));
}

$total_count = $total_sent = $email->total;
$open_count = (int) $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where email_id=" . $email_id);
$click_count = (int) $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where url<>'' and email_id=" . $email_id);

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

<script type="text/javascript" src="<?php echo plugins_url('newsletter') ?>/js/Chart2.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url('newsletter') ?>/js/jquery.vmap.min.js"></script>
<script type="text/javascript" src="<?php echo plugins_url('newsletter') ?>/js/jquery.vmap.world.js"></script>
<link href="<?php echo plugins_url('newsletter') ?>/css/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>

<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_DIR . '/tnp-header.php' ?>
    <div id="tnp-heading">
        <h2><?php _e('Statistics of', 'newsletter') ?> "<?php echo htmlspecialchars($email->subject); ?>"</h2>

        <?php $controls->show(); ?>

    </div>


    <div id="tnp-body" style="min-width: 500px">

        <form action="" method="post">
            <?php $controls->init(); ?>

            <div class="row">

                <div class="col-md-6">
                    <!-- START Statistics -->
                    <div class="tnp-widget">

                        <h3>Subscribers Reached <a href="admin.php?page=newsletter_statistics_view_users&id=<?php echo $email->id ?>">Details</a> 
                            <a href="admin.php?page=newsletter_statistics_view_retarget&id=<?php echo $email->id ?>">Retarget</a></h3>
                        
                        <div class="inside">
                            <div class="row tnp-row-pie-charts">
                                <div class="col-md-6">
                                    <canvas id="tnp-rates1-chart"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="tnp-rates2-chart"></canvas>
                                </div>
                            </div>

                            <script type="text/javascript">
                               
                                var rates1 = {
                                    labels: [
                                        "Not opened",
                                        "Opened"
                                    ],
                                    datasets: [
                                        {
                                            data: [<?php echo $total_sent - $open_count; ?>, <?php echo $open_count; ?>],
                                            backgroundColor: [
                                                "#E67E22",
                                                "#2980B9"
                                            ],
                                            hoverBackgroundColor: [
                                                "#E67E22",
                                                "#2980B9"
                                            ]
                                        }]};
                                        
                                var rates2 = {
                                    labels: [
                                        "Opened",
                                        "Clicked"
                                    ],
                                    datasets: [
                                        {
                                            data: [<?php echo $open_count; ?>, <?php echo $click_count; ?>],
                                            backgroundColor: [
                                                "#2980B9",
                                                "#27AE60"
                                            ],
                                            hoverBackgroundColor: [
                                                "#2980B9",
                                                "#27AE60"
                                            ]
                                        }]};

                                jQuery(document).ready(function ($) {
                                    ctx1 = $('#tnp-rates1-chart').get(0).getContext("2d");
                                    ctx2 = $('#tnp-rates2-chart').get(0).getContext("2d");
                                    myPieChart1 = new Chart(ctx1, {type: 'pie', data: rates1});
                                    myPieChart2 = new Chart(ctx2, {type: 'pie', data: rates2});
                                });

                            </script>

                            <div class="row tnp-row-values">
                                <div class="col-md-6">
                                    <div class="tnp-data">
                                        <?php if ($email->status == 'sending' || $email->status == 'paused'): ?>
                                            <div class="tnp-data-title">Sent</div>
                                            <div class="tnp-data-value"><?php echo $email->sent; ?> of <?php echo $email->total; ?></div>
                                        <?php else: ?>
                                            <div class="tnp-data-title">Total Sent</div>
                                            <div class="tnp-data-value"><?php echo $email->total; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="tnp-data">
                                        <div class="tnp-data-title">Interactions</div>
                                        <div class="tnp-data-value"><?php echo $open_count; ?> (<?php echo percent($open_count, $total_sent); ?>)</div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="tnp-data">
                                        <div class="tnp-data-title">Opened</div>
                                        <div class="tnp-data-value"><?php echo $open_count - $click_count; ?> (<?php echo percent($open_count - $click_count, $total_sent); ?>)</div>
                                    </div>
                                    <div class="tnp-data">
                                        <div class="tnp-data-title">Clicked</div>
                                        <div class="tnp-data-value"><?php echo $click_count; ?> (<?php echo percent($click_count, $total_sent); ?>)</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="tnp-widget">
                        <h3>World Map</h3>
                        <div class="inside">
                            <a href="http://www.thenewsletterplugin.com/premium?utm_source=plugin&utm_medium=link&utm_content=worldmap&utm_campaign=newsletter-reports" target="_blank">
                                <img src="<?php echo plugins_url('newsletter') ?>/statistics/images/map.gif">
                            </a>
                        </div>
                    </div>
                </div>

            </div><!-- row -->


        </form>

    </div>
    <?php include NEWSLETTER_DIR . '/tnp-footer.php' ?>
</div>
