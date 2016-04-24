<?php
/* @var $wpdb wpdb */
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$module = NewsletterStatistics::instance();
$controls = new NewsletterControls();

if ($controls->is_action('country')) {
    $module->country();
    $controls->messages = $module->country_result;
}

if ($controls->is_action('import')) {
    $wpdb->query("insert ignore into " . $wpdb->prefix . "newsletter_sent (user_id, email_id, time) select user_id, email_id, UNIX_TIMESTAMP(created) from " . NEWSLETTER_STATS_TABLE);
    $controls->messages = 'Done!';
}

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


if (empty($controls->data['type'])) {
    $emails = $wpdb->get_results("select send_on, id, subject, total, status, type, track, sent, subject from " . NEWSLETTER_EMAILS_TABLE . " where status='sent' order by send_on desc limit 30");
} else {
    $emails = $wpdb->get_results($wpdb->prepare("select send_on, id, subject, total, type from " . NEWSLETTER_EMAILS_TABLE . " where status='sent' and type=%s order by send_on desc limit 30", $controls->data['type']));
}
$overview_labels = array();
$overview_titles = array();
$overview_open_rate = array();
$overview_click_rate = array();

$total_sent = 0;
$open_count_total = 0;
$click_count_total = 0;
foreach ($emails as $email) {
    $entry = array();
    $total_sent += $email->total;
    if (empty($email->total)) {
//        $entry[0] = date('Y-m-d', $email->send_on);
//        $entry[1] = 0;
//	$entry[2] = $email->subject;// . ' (' . percent($open_count, $email->total) . ')';
//        $entry[3] = 0;
        continue;
    }
    //$entry[0] = $email->subject . ' [' . date('Y-m-d', $email->send_on) . ', ' . $email->type . ']';
    $total_sent += $email->total;
    $entry[0] = date('Y-m-d', $email->send_on);
    $open_count = $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where email_id=" . $email->id);
    $open_count_total += $open_count;
    $entry[1] = $open_count / $email->total * 100;
    $entry[1] = round($entry[1], 2);
    $entry[2] = $email->subject; // . ' (' . percent($open_count, $email->total) . ')';
    $click_count = $wpdb->get_var("select count(distinct user_id) from " . NEWSLETTER_STATS_TABLE . " where url<>'' and email_id=" . $email->id);
    $click_count_total += $click_count;
    $entry[3] = $click_count / $email->total * 100;
    $entry[3] = round($entry[3], 2);

    $overview_labels[] = strftime('%a, %e %b', $email->send_on);
    $overview_open_rate[] = $entry[1];
    $overview_click_rate[] = $entry[3];
    $overview_titles[] = $entry[2];
}

$overview_labels = array_reverse($overview_labels);
$overview_open_rate = array_reverse($overview_open_rate);
$overview_click_rate = array_reverse($overview_click_rate);

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
        
        
        <h2><?php _e('Global Newsletter Statistics', 'newsletter') ?></h2>
            
    </div>
        

    

    <div id="tnp-body">
        <form method="post" action="">

            <?php $controls->init(); ?>

            <?php if (empty($emails)) { ?>
                <img src="http://cdn.thenewsletterplugin.com/tnp-reports-dummy-image.png" style="max-width: 100%">
                
            <?php } else { ?>
                
                <div class="row">
                    
                        <div class="tnp-statistics-info-box">
                            <p class="tnp-legend">Select Newsletter category:</p>
                            <?php $controls->select('type', $type_options, 'All') ?>
                            <?php $controls->button('update', __('Update Charts', 'newsletter')) ?>
                        </div>
                    
                </div>

                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="tnp-widget">
                            <h3>Overview</h3>
                            <div class="inside">
                                
                                <p class="tnp-events-legend">Subscribers interactions distribution over time,<br>starting from the sending day.</p>
                                
                                <div id="tnp-events-chart">
                                    <canvas id="tnp-events-chart-canvas"></canvas>
                                </div>

                                <script type="text/javascript">
                                    var events_data = {
                                        labels: <?php echo json_encode($overview_labels) ?>,
                                        datasets: [
                                            {
                                                label: "Open",
                                                fill: false,
                                                strokeColor: "#27AE60",
                                                backgroundColor: "#27AE60",
                                                borderColor: "#27AE60",
                                                pointBorderColor: "#27AE60",
                                                pointBackgroundColor: "#27AE60",
                                                data: <?php echo json_encode($overview_open_rate) ?>
                                            },
                                            {
                                                label: "Click",
                                                fill: false,
                                                strokeColor: "#C0392B",
                                                backgroundColor: "#C0392B",
                                                borderColor: "#C0392B",
                                                pointBorderColor: "#C0392B",
                                                pointBackgroundColor: "#C0392B",
                                                data: <?php echo json_encode($overview_click_rate) ?>,
                                                yAxisID: "y-axis-2"
                                            }
                                        ]
                                    };
                                    
                                    var titles = <?php echo json_encode(array_reverse($overview_titles)) ?>;

                                    jQuery(document).ready(function ($) {
                                        ctxe = $('#tnp-events-chart-canvas').get(0).getContext("2d");
                                        eventsLineChart = new Chart(ctxe, {type: 'line', data: events_data,
                                            options: {
                                                scales: {
                                                    xAxes: [{type:"category","id":"x-axis-1", gridLines: {display: false}, ticks: {fontFamily: "Source Sans Pro"}}],
                                                    yAxes: [
                                                        {type: "linear", "id": "y-axis-1", gridLines: {display: false}, ticks: {fontColor: "#27AE60", fontFamily: "Source Sans Pro"}},
                                                        {type: "linear", "id": "y-axis-2", position: "right", gridLines: {display: false}, ticks: {fontColor: "#C0392B", fontFamily: "Source Sans Pro"}}
                                                    ]
                                                },
                                                tooltips: {
                                                    callbacks: {
                                                        afterTitle: function(data) {
                                                            return titles[data[0].index];
                                                        },
                                                        label: function(tooltipItem, data) {
                                                            return data.datasets[0].label + ": " + data.datasets[0].data[tooltipItem.index] + "% " + 
                                                                    data.datasets[1].label + ": " + data.datasets[1].data[tooltipItem.index] + "%";
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="tnp-data">
                                            <div class="tnp-data-title"><?php _e('Total Sent Messages', 'newsletter') ?></div>
                                            <div class="tnp-data-value"><?php echo $total_sent; ?></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tnp-data">
                                            <div class="tnp-data-title"><?php _e('Total Interactions', 'newsletter') ?></div>
                                            <div class="tnp-data-value"><?php echo $open_count_total; ?> (<?php echo percent($open_count_total, $total_sent); ?>)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tnp-data">
                                            <div class="tnp-data-title"><?php _e('Opened Newsletters', 'newsletter') ?></div>
                                            <div class="tnp-data-value"><?php echo $open_count_total - $click_count_total; ?> (<?php echo percent($open_count_total - $click_count_total, $total_sent); ?>)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="tnp-data">
                                            <div class="tnp-data-title"><?php _e('Clicked Newsletters', 'newsletter') ?></div>
                                            <div class="tnp-data-value"><?php echo $click_count_total; ?> (<?php echo percent($click_count_total, $total_sent); ?>)</div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
           

                    <!-- WORLD MAP -->
                    <div class="col-md-6">
                        <div class="tnp-widget">
                            <h3><?php _e('Countries', 'newsletter') ?></h3>
                            <div class="inside">
                                <?php
                                if (!has_action('newsletter_statistics_index_map')) {
                                    ?><a href="http://www.thenewsletterplugin.com/premium?utm_source=plugin&utm_medium=link&utm_content=worldmap&utm_campaign=newsletter-reports" target="_blank">
                                        <img src="<?php echo plugins_url('newsletter') ?>/statistics/images/map.gif" style="width: 100%">
                                    </a><?php
                                } else {
                                    do_action('newsletter_statistics_index_map');
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="row">


                    <!-- LAST NEWSLETTERS -->
                    <div class="col-md-12">
                        <div class="tnp-widget">
                            <h3><?php _e('Last newsletters', 'newsletter') ?> <a href="admin.php?page=newsletter_statistics_newsletters"><?php _e('Details', 'newsletter') ?></a></h3>
                            <div class="inside">
                                <?php
                                $emails = $wpdb->get_results($wpdb->prepare("select send_on, id, subject, total, status, type, track, sent, subject from " . NEWSLETTER_EMAILS_TABLE . " where status in ('sent', 'sending') and send_on<%d order by send_on desc limit 5", time()));
                                ?>

                                <table class="widefat">
                                       <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th><?php _e('Subject', 'newsletter') ?></th>
                                            <th>Type</th>
                                            <th><?php _e('Status', 'newsletter') ?></th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($emails as &$email) { ?>
                                            <tr>
                                                <td><?php echo $email->id; ?></td>
                                                <td><?php echo htmlspecialchars($email->subject); ?></td>
                                                <td><?php echo $module->get_email_type_label($email); ?></td>
                                                <td><?php echo $module->get_email_status_label($email); ?></td>
                                                <td>
                                                    <a href="<?php echo NewsletterStatistics::instance()->get_statistics_url($email->id); ?>" class="button">Statistics</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="tnp-statistics-info-box">
                            <p class="tnp-legend">Check Statistics global<br>configurations.</p>
                            <a class="button" href="admin.php?page=newsletter_statistics_settings"><?php _e('Settings') ?></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                   
                    

                    
                </div>
                


            <?php } ?>


        </form>

        
    </div>
    <?php include NEWSLETTER_DIR . '/tnp-footer.php' ?>
</div>
