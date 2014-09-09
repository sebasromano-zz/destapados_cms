<?php // Ñandú ?>

<div id="mainContent">


	<div class="list">

		<div class="listHeader clearfix">
			<h3><?php __('¿Está seguro que desea reportar como SPAM el email') ?> "<?php echo $email; ?>"?</h3>
		</div>

		<?php if ( $confirm == 1 ) : ?>
			<div class="successBoxSmall">
				<?php __("El email se ha reportado como SPAM.") ?>
			</div>
		<?php elseif(empty($subscriber)): ?>
			<div class="errorBoxSmall">
				<?php __("El email no existe.") ?>
			</div>
		<?php elseif(!empty($subscriber)): ?>
			<div class="editFormButtons">
				<?php echo $html->link( __("Confirmar", true), '/subscribers/reportspam/'.$email.'/confirm:1', array('class' => 'button')); ?>
			</div>
		<?php endif; ?>

	</div>

</div>