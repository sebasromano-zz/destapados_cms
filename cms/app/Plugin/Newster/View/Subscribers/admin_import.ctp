<?php // Ñandú ?>

<div id="mainContent">

	<h2><?php __('Importar suscriptores') ?></h2>

	<div class="list">

		<?php echo $form->create('Subscriber', array('type' => 'file', 'action' => 'import', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 'textField w350px'))) ?>

			<div class="editForm minHeight200px">

				<div id="editFormContent_gral" class="editFormContent">
					
					<?php if($imported == 1): ?>

					    <br/>Direcciones importadas: <strong><?php echo $emailsTotal ?></strong>
						
					<?php else: ?>
				
						<div class="formRow clearfix">
							<?php echo $form->label('app', __("Software de exportación", true), array('class' => 'floatLeft w150px alignRight marginRight20')); ?>
							<div class="floatLeft">
								<?php echo $form->input('app', array('class' => 'selectBox w360px', 'type' => 'select', 'options' => $apps, 'error' => array('required' => __('Seleccione el software de exportación.', true)) ) ); ?>
							</div>
						</div>
		
						<div class="formRow clearfix">
							<?php echo $form->label('import_file', __("Importar desde archivo", true).' <em>*</em>', array('class' => 'floatLeft w150px alignRight marginRight20')); ?>
							<div class="floatLeft">
								<?php echo $form->input('import_file', array( 'type' => 'file', 'div' => false, 'label' => false, 'class' => 'textField w200px', 'error' => array('file' => __('Seleccione el archivo a importar.', true)) )); ?>
								<div class="fieldTip">
									<?php __('El archivo debe ser .csv y debe contener solo nombre y e-mail') ?>
								</div>
							</div>
						</div>

					<?php endif; ?>

				</div><!-- /editFormContent -->

			</div>

			<div class="editFormButtons">
				<?php if($imported == 1): ?>
					<?php echo $html->link( __("Volver", true), $returnTo, array('class' => 'btn')); ?>
				<?php else: ?>
					<?php echo $form->submit( __("Guardar", true), array('div' => false, 'class' => 'button alignMiddle') ); ?>&nbsp;&nbsp;<?php __('o') ?> <?php echo $html->link( __("Cancelar", true), $returnTo ); ?>
				<?php endif; ?>
			</div>

		<?php echo $form->end(); ?>

	</div>

</div>
