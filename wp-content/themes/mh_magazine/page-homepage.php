<?php /* Template Name: Homepage */ ?>
<?php $options = mh_theme_options(); ?>
<?php get_header(); ?>
<div class="mh-wrapper hp clearfix">
	<div class="hp-main">
		<?php dynamic_sidebar('home-1'); ?>
		<?php if (is_active_sidebar('home-2') || is_active_sidebar('home-3') || is_active_sidebar('home-4') || is_active_sidebar('home-5') || is_active_sidebar('home-6')) : ?>
			<div class="hp-columns clearfix">
				<div id="main-content" class="hp-content left">
		    		<?php dynamic_sidebar('home-2'); ?>
					<?php if (is_active_sidebar('home-3') || is_active_sidebar('home-4')) : ?>
						<div class="clearfix">
							<?php if (is_active_sidebar('home-3')) { ?>
								<div class="hp-sidebar hp-home-3">
									<?php dynamic_sidebar('home-3'); ?>
								</div>
							<?php } ?>
							<?php if (is_active_sidebar('home-4')) { ?>
								<div class="hp-sidebar sb-right hp-home-4">
									<?php dynamic_sidebar('home-4'); ?>
								</div>
							<?php } ?>
						</div>
					<?php endif; ?>
					<?php dynamic_sidebar('home-5'); ?>
				</div>
				<?php if (is_active_sidebar('home-6')) { ?>
					<div class="mh-sidebar hp-sidebar hp-home-6">
						<?php dynamic_sidebar('home-6'); ?>
					</div>
				<?php } ?>
			</div>
		<?php endif; ?>
		<?php dynamic_sidebar('home-7'); ?>
		<?php if (is_active_sidebar('home-8') || is_active_sidebar('home-9') || is_active_sidebar('home-10')) : ?>
			<div class="hp-columns clearfix">
	    		<?php if (is_active_sidebar('home-8')) { ?>
					<div class="hp-sidebar hp-home-8">
						<?php dynamic_sidebar('home-8'); ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('home-9')) { ?>
					<div class="hp-sidebar sb-right hp-home-9">
						<?php dynamic_sidebar('home-9'); ?>
					</div>
				<?php } ?>
				<?php if (is_active_sidebar('home-10')) { ?>
					<div class="hp-sidebar sb-right hp-home-10">
						<?php dynamic_sidebar('home-10'); ?>
					</div>
				<?php } ?>
			</div>
		<?php endif; ?>
		<?php dynamic_sidebar('home-11'); ?>
	</div>
	<?php if ($options['sidebars'] == 'two' && is_active_sidebar('home-12')) { ?>
		<div class="hp-sidebar-2 sb-wide sb-right">
    		<?php dynamic_sidebar('home-12'); ?>
		</div>
	<?php } ?>
</div>
<?php get_footer(); ?>