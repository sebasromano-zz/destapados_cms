	<?php echo $this->Html->css('/js/vendor/datetimepicker/jquery.datetimepicker.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->script('vendor/datetimepicker/jquery.datetimepicker.js'); ?>
	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data['Newsletter']['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __d( 'newster', 'boletín') ?></h2>
			</div>
		</div>

		<?php echo $this->Session->flash() ?>

		<?php echo $this->Form->create( 'Newsletter', array('type' => 'file', 'class' => '', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's350'))) ?>
		<div class="tabbable tabbable-bordered form-vertical">

			<div class="tab-content tab-content-empty">

				<div id="tab-pane_grl" class="tab-pane active updateMapWithAddress">


						<div class="well form-vertical form-box">
							<fieldset>

								<div class="control-group clearfix">
									<?php echo $this->Form->label( 'send_date', __("Fecha de envío", true), array('class' => 'control-label')); ?>
									<div class="controls">
										<div class="input-append">
											<?php echo $this->Form->input( 'send_date', array( 'type' => 'text', 'class' => 's200', 'default' => date('d/m/Y H:i:00') )); ?>
										</div>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('newsletter_title', __("Title", true).' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('newsletter_title', array('class' => 's450 field-title', 'error' => array('required' => __d( 'newster', 'Ingrese un título') ) ) ) ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('newsletter_text', __("Texto alternativo", true), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('newsletter_text', array('type' => 'textarea', 'class' => 's450') ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label('Newsletterlist', __d( 'newster', 'Listas a las que se les enviará'), array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input('Newsletterlist', array( 'options' => $newsletterlists, 'multiple' => 'checkbox', 'class' => '') ); ?>
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

<script type="text/javascript">
$(function(){
	$('#NewsletterSendDate').datetimepicker({
		lang:'es',
		format:'d/m/Y H:i:s'
	});
});
</script>