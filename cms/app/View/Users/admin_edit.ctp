	<?php
		$user = $this->Session->read('Auth.User');
	?>

	<?php echo $this->element('admin_sidebar_users') ?>

	<div id="items" class="items-col">

		<h2><?php if(empty($this->request->data[$model]['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __("user") ?></h2>

		<?php echo $this->Form->create('User', array('class' => 'form-horizontal', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's200'))) ?>
		<div class="tabbable tabbable-bordered">

			<?php if(!empty( $this->request->data['User']['id'] )): ?>
			<ul class="nav nav-tabs">
				<li id="tab_grl" class="active"><a href="#tab-pane_grl" data-toggle="tab"><?php echo __("General") ?></a></li>
				<li id="tab_pass"><a href="#tab-pane_pass" data-toggle="tab"><?php echo __("Change password") ?></a></li>
			</ul>

			<div class="tab-content">
			<?php else: ?>
			<div class="tab-content tab-content-bordered">
			<?php endif; ?>

				<div id="tab-pane_grl" class="tab-pane active">
					<fieldset>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('username', __("Username", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('username', array('error' => array( 'unique' => __("Ya existe un usuario con este nombre", true), 'required' => __("Ingrese el nombre de usuario", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('email', __("E-mail", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('email', array('error' => array('email' => __("Ingrese una dirección de e-mail válida", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('first_name', __("First Name", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('first_name', array('error' => array('required' => __("Ingrese el nombre completo", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('last_name', __("Last Name", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('last_name', array('error' => array('required' => __("Ingrese el nombre completo", true)) ) ); ?>
							</div>
						</div>
				
						<?php if(empty( $this->request->data['User']['id'] )): ?>
						<div class="control-group clearfix">
							<?php echo $this->Form->label('new_password', __("Password", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('new_password', array('type' => 'password', 'class' => 's150', 'error' => array( 'required' => __("Ingrese la contraseña", true) ) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<div class="controls">
								<?php echo $this->Form->input('new_password_confirm', array('type' => 'password', 'class' => 's150', 'error' => __("Las contraseñas no coinciden", true) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('send_password', __("Send password?", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('send_password', array('type' => 'checkbox', 'class' => 'checkBox') ); ?>
								<div class="help-inline"><?php echo __('Send password to new user by email') ?></div>
							</div>
						</div>
						<?php endif; ?>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('role', __("Role", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('role', array('type' => 'select', 'options' => array('admin' => __('Administrator', true), 'client' => __('Client', true), 'guide' => __('Guide', true)), 'empty' => __('Select...', true), 'error' => array('required' => __("Seleccione un tipo de usuario", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('active', __("Active", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('active', array('class' => 'checkBox') ); ?>
							</div>
						</div>

					</fieldset>
				</div><!-- /tab-pane -->

				<?php if(!empty( $this->request->data['User']['id'] )): ?>
				<div id="tab-pane_pass" class="tab-pane">
					<fieldset>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('new_password', __("Password", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('new_password', array('type' => 'password', 'class' => 's150', 'error' => array( 'required' => __("Ingrese la contraseña", true) ) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('new_password_confirm', __("Confirm password", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('new_password_confirm', array('type' => 'password', 'class' => 's150', 'error' => __("Las contraseñas no coinciden", true) ) ); ?>
							</div>
						</div>

					</fieldset>
				</div><!-- /tab-pane -->
				<?php endif; ?>

			</div>

		</div><!-- /.tabbable -->

		<div class="form-footer clearfix">
			<?php echo $this->Form->submit( __("Save", true), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __("Cancel", true), array('action'=>'back', 'index'), array('class' => 'btn btn-small')) ?>
		</div>

		<?php echo $this->Form->input('id', array('type' => 'hidden' ) ); ?>
		<?php echo $this->Form->end(); ?>
	</div>
