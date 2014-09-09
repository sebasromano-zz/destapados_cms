	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data[$model]['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __("Usuario") ?></h2>
			</div>
			
			<div class="pull-right btn-group">
			<?php
				//echo $this->Html->link( __("General + EN", true), '#tab-pane_grl', array('data-toggle' => 'tab', 'class' => 'btn btn-small active', 'escape' => false));
				//echo $this->Html->link( __("ES", true), '#tab-pane_spa', array('data-toggle' => 'tab', 'class' => 'btn btn-small', 'escape' => false));
			?>
			</div>
			
		</div>

		<?php echo $this->Form->create($model, array('type' => 'file', 'class' => '', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's350'))) ?>
		<div class="tabbable tabbable-bordered form-vertical">

			<div class="tab-content tab-content-empty">

				<div id="tab-pane_grl" class="tab-pane active updateMapWithAddress">

					<div class="edit-main">

						<div class="well form-vertical form-box">
							<fieldset>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.last_name', 'Apellido', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.last_name', array('error' => array('required' => __("Ingrese la pregunta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.first_name', 'Nombre', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.first_name', array('error' => array('required' => __("Ingrese la pregunta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.email', 'Email', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.email', array('error' => array('required' => __("Ingrese la pregunta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.gender', 'Género', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.gender', array( 'class' => '', 'empty' => '', 'options' => array('female' => 'Femenino', 'male' => 'Masculino') ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.locale', 'Locale', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.locale', array('class' => 's50') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.timezone', 'Timezone', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.timezone', array('class' => 's50') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.fb_id', 'FB id', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.fb_id', array('class' => 's150', 'type' => 'text') ); ?>
									</div>
								</div>

							</fieldset>
						</div>

					</div><!-- /edit-main -->



				</div><!-- /tab-pane -->
				</div><!-- /tab-pane -->

			</div>

		</div><!-- /.tabbable -->

		<div class="form-footer clearfix">
			<?php echo $this->Form->submit( __("Save", true), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __("Cancel", true), array('action'=>'back', 'index'), array('class' => 'btn btn-small')) ?>
		</div>

		<?php echo $this->Form->input('id', array('type' => 'hidden') ); ?>
		<?php echo $this->Form->input('permalink', array('type' => 'hidden') ); ?>

	</div>
	<?php echo $this->Form->end(); ?>