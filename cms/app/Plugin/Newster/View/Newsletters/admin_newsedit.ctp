
	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data['Newsletter']['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __d( 'newster', 'boletín') ?></h2>
			</div>
		</div>

		<?php echo $this->Session->flash() ?>

		<?php echo $this->Form->create( 'NewslettersItem', array('type' => 'file', 'class' => '', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's350'))) ?>
		<div class="tabbable tabbable-bordered form-vertical">

			<div class="tab-content tab-content-empty">

				<div id="tab-pane_grl" class="tab-pane active updateMapWithAddress">


						<div class="well form-vertical form-box">
							<fieldset>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('order_by', __d( 'newster', 'N° de Orden').' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('order_by', array('class' => 's150', 'error' => array('required' => __d( 'newster', 'Ingrese número de orden') ) ) ) ?>
									</div>
								</div>

								<?php if( isset($newsterSettings['relatedModel']) && !empty($newsterSettings['relatedModel'])): ?> 

								<div class="control-group clearfix">
									<?php echo $this->Form->label('related_id', __d( 'newster', 'Relacionado'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('related_id', array('class' => 's450', 'empty' => '', 'options' => $items, 'error' => array('required' => __d( 'newster', 'Seleccione item relacionado') ) ) ) ?>
									</div>
								</div>

								<?php endif ?>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('title', __d( 'newster', 'Título'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('title', array('class' => 's450', 'error' => array('required' => __d( 'newster', 'Ingrese un título') ) ) ) ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('content', __d( 'newster', 'Texto'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('content', array('type' => 'textarea', 'class' => 's450') ); ?>
									</div>
								</div>

								<div class="type_image">
							        <div class="control-group clearfix">
								        <?php echo $this->Form->label('pic', __d( 'newster', 'Imagen'), array('class' => 'control-label')); ?>
								        <div class="controls">
									        <?php echo $this->Uploader->input('pic', array('allowDelete' => true, 'showThumb' => true, 'allowCrop' => false, 'error' => __('Select image', true))) ?>
								        </div>
							        </div>
						        </div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('url', __d( 'newster', 'URL'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('url', array('class' => 's450') ) ?>
									</div>
								</div>

							</fieldset>
						</div>

				</div><!-- /tab-pane -->

				<div class="form-footer clearfix">
					<?php echo $this->Form->submit( __d( 'newster', 'Guardar'), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __d( 'newster', 'Cancel'), array( 'plugin' => 'newster', 'action'=>'back', 'news'), array('class' => 'btn btn-small')) ?>
				</div>

			</div>

		


		</div><!-- /.tabbable -->


		<?php echo $this->Form->input('id', array('type' => 'hidden') ); ?>

	</div>
	<?php echo $this->Form->end(); ?>