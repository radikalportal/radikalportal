<script type="text/javascript">
    var __namespace = '<?php echo $namespace; ?>';
</script>
<div id="<?php echo $namespace; ?>-wrapper" class="wrap">
    
    <h2><?php echo $friendly_name; ?> Options</h2>
    
    <div id="<?php echo $namespace; ?>-form">
        <?php flare_primary_navigation(); ?>
        
        <div id="<?php echo $namespace; ?>-metrics-header">
            <h3>Coming Soon</h3>
            <p>Advanced Flare Metrics delivered weekly to your Inbox.</p>
            <p><a href="http://filament.io/applications/flare?utm_source=flare_wp&utm_medium=deployment&utm_content=metrics&utm_campaign=filament" class="button" target="_blank">Learn More</a></p>
        </div>
        <img src="<?php echo FLARE_URLPATH; ?>/images/metrics-preview.png" id="<?php echo $namespace; ?>-metrics-preview" alt="" />
    </div>
    
    <?php include( FLARE_DIRNAME . '/views/elements/_footer.php' ); ?>
    
</div>