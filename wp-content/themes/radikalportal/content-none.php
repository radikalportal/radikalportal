<header class="page-header">
	<h1 class="page-title">Ingenting funnet</h1>
</header>

<div class="page-content">
	<?php if ( is_search() ) : ?>

	<p>Beklager, søkeordene dine ga ingen treff. Vennligst prøv igjen med noen andre søkeord.</p>

	<?php else : ?>

	<p>Ingenting ble funnet på denne adressen. Kanskje prøve et søk?</p>
	<?php get_search_form(); ?>

	<?php endif; ?>
</div><!-- .page-content -->
