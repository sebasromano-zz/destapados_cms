<?php // Ñandú ?>

<div id="mainContent">


	<div class="list">

		<div class="listHeader clearfix">
			<h3><?php __('¿Está seguro que desea desuscribir el email') ?> "<?php echo $email; ?>" <?php __('de nuestras listas de correos?') ?></h3>
		</div>

		<?php if ( $confirm == 1 ) : ?>
			<div class="successBoxSmall">
				<?php __("Se ha desuscripto correctamente.") ?>
			</div>
		<?php elseif(empty($subscriber)): ?>
			<div class="errorBoxSmall">
				<?php __("Este email ya se ha desuscripto.") ?>
			</div>
		<?php elseif(!empty($subscriber)): ?>
			<div class="editFormButtons">
				<?php echo $html->link( __("Confirmar", true), '/subscribers/optout/'.$email.'/confirm:1', array('class' => 'button')); ?>
			</div>
		<?php endif; ?>

	</div>

</div>