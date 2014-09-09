	<div class="welcome">
		<h1><?php echo file_exists(WWW_ROOT.'img'.DS.'admin-login-logo.png') ? $this->Html->image('admin-login-logo.png', array('alt' => 'IDITS')) : 'Venta Resuelta' ?></h1>
		<div class="welcome-form">
			<?php $authMessage = $this->Session->flash(); if(!empty($authMessage)) echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>'.strip_tags($authMessage).'</div>'; ?>
			<?php echo $this->Form->create('User', array('inputDefaults' => array('label' => false, 'div' => false, 'class' => 'input'))) ?>
				<div class="control-group clearfix">
					<?php echo $this->Form->input('username', array('placeholder' => __('Username', true))); ?>
				</div>
				<div class="control-group clearfix">
					<?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => __('Password', true))); ?>
				</div>
				<div class="clearfix">
					<?php echo $this->Form->submit( __("Enter", true), array('div' => false, 'class' => 'btn btn-primary')) ?>
					<!-- <div class="fields-checkboxes clearfix">
						<?php echo $this->Form->input('remember_me', array('type' => 'checkbox', 'class' => false)); ?>
						<?php echo $this->Form->label('remember_me', __("Keep me logged in", true)); ?>
					</div>-->
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
