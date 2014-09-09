
	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data['Subscriber']['id'])) echo __d('newster', 'Agregar'); else echo __d('newster', 'Editar') ?> <?php echo __d( 'newster', 'suscriptor') ?></h2>
			</div>
		</div>

		<?php echo $this->Session->flash() ?>

		<?php echo $this->Form->create( 'Subscriber', array('type' => 'file', 'class' => '', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's350'))) ?>
		<div class="tabbable tabbable-bordered form-vertical">

			<div class="tab-content tab-content-empty">

				<div id="tab-pane_grl" class="tab-pane active updateMapWithAddress">


						<div class="well form-vertical form-box">
							<fieldset>

								<div class="control-group clearfix">
									<?php echo $this->Form->label( 'email', __d( 'newster', 'Email'), array('class' => 'control-label')); ?>
									<div class="controls">
										<div class="input-append">
											<?php echo $this->Form->input( 'email', array( 'error' => array('email' => __d( 'newster', 'Ingrese un email válido') ) )); ?>
										</div>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('last_name', __d( 'newster', 'Apellido(s)'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('last_name', array('class' => 's450', 'error' => array('required' => __d( 'newster', 'Ingrese un título') ) ) ) ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('first_name', __d( 'newster', 'Nombre(s)'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('first_name', array('class' => 's450', 'error' => array('required' => __d( 'newster', 'Ingrese un título') ) ) ) ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('Newsletterlist', __d( 'newster', 'Listas de interés'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('Newsletterlist', array('multiple' => 'checkbox', 'class' => '') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('active', __d( 'newster', 'Usuario activo'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('active', array('class' => '') ); ?>
									</div>
								</div>

							</fieldset>
						</div>

				</div><!-- /tab-pane -->

				<div class="form-footer clearfix">
					<?php echo $this->Form->submit( __d( 'newster', 'Guardar'), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __d( 'newster', 'Cancel'), array( 'plugin' => 'newster', 'action'=>'back', 'index'), array('class' => 'btn btn-small')) ?>
				</div>

			</div>

		


		</div><!-- /.tabbable -->


		<?php echo $this->Form->input('id', array('type' => 'hidden') ); ?>

	</div>
	<?php echo $this->Form->end(); ?>