<ul id="<?php echo $namespace; ?>-primary-navigation">
    <?php foreach( $menu_hooks as $menu_key => $menu_hook ): ?>
        <li>
            <a id="<?php echo "{$namespace}-nav-{$menu_key}"; ?>" href="<?php echo admin_url( 'admin.php?page=' . $menu_hook['path'] ); ?>"<?php if( $screen->id == $menu_hook['hook'] ) echo ' class="active"'; ?>>
                <span><?php echo $menu_hook['label']; ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<div id="deprecation-notice" class="updated below-h2">
  <p>We&rsquo;re no longer updating this plugin and have created a free Flare app that is:</p>
  <ul>
    <li>Faster</li>
    <li>More Customizable</li>
    <li>Supports More Services</li>
    <li>And Has More Features On the Way!</li>
  </ul>
  <p><a href="http://filament.io/flare?utm_source=flare_wp&utm_medium=dep_notice&utm_campaign=filament" target="_blank" class="cta-btn"><span>Install Flare App</span></a></p>
</div>