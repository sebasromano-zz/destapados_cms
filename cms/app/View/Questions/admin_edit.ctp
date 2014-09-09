	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data[$model]['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __("Pregunta") ?></h2>
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
									<?php echo $this->Form->label($model.'.category_id', 'Categoría <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.category_id', array( 'class' => '') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.title', 'Pregunta <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.title', array('error' => array('required' => __("Ingrese la pregunta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.source', 'Fuente', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.source', array('error' => array('required' => __("Ingrese la pregunta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.answer_1', 'Respuesta 1 <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.answer_1', array( 'error' => array('required' => __("Ingrese la respuesta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.answer_2', 'Respuesta 2 <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.answer_2', array( 'error' => array('required' => __("Ingrese la respuesta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.answer_3', 'Respuesta 3 <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.answer_3', array( 'error' => array('required' => __("Ingrese la respuesta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.answer_4', 'Respuesta 4 <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.answer_4', array( 'error' => array('required' => __("Ingrese la respuesta", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.answer_ok', 'Respuesta correcta <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.answer_ok', array( 'class' => 's150', 'error' =>  "Debe ingresar número del 1 al 4" ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.suggested', 'Sugerida?', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.suggested', array( 'class' => '') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.active', 'Activa', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.active', array( 'class' => '') ); ?>
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