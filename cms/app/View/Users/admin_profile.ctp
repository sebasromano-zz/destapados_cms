	<?php
		$user = $this->Session->read('Auth.User');
	?>

	<?php echo $this->element('admin_sidebar_users') ?>

	<div id="items" class="items-col">

		<h2><?php echo __("Profile") ?></h2>

		<?php echo $this->Form->create('User', array('class' => 'form-horizontal', 'inputDefaults' => array('label' => false, 'div' => false, 'class' => 's200'))) ?>
		<div class="tabbable tabbable-bordered">

			<div class="tab-content tab-content-bordered">

				<div id="tab-pane_grl" class="tab-pane active">
					<fieldset>

						<legend><?php echo __('Name') ?></legend>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('username', __("Username", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('username', array('error' => array( 'unique' => __("Ya existe un usuario con este nombre", true), 'required' => __("Ingrese el nombre de usuario", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('first_name', __("First Name", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('first_name', array('error' => array('required' => __("Ingrese el nombre completo", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('last_name', __("First Name", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('last_name', array('error' => array('required' => __("Ingrese el nombre completo", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('nickname', __("Nickname", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('nickname', array('error' => array( 'unique' => __("Ya existe un usuario con este nombre", true), 'required' => __("Ingrese el nombre de usuario", true)) ) ); ?>
							</div>
						</div>

						<legend><?php echo __('Contact Info') ?></legend>
				
						<div class="control-group clearfix">
							<?php echo $this->Form->label('email', __("E-mail", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('email', array('error' => array('email' => __("Ingrese una dirección de e-mail válida", true)) ) ); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('website', __("Website", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('website'); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('skype', __("Skype", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('skype'); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('gtalk', __("GTalk", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('gtalk'); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('phone', __("Phone", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('phone'); ?>
							</div>
						</div>

						<legend><?php echo __('About Yourself') ?></legend>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('bio', __("Biographical Info", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('bio'); ?>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('avatar', __("Profile Picture", true), array('class' => 'control-label')); ?>
							<div class="controls">
								<div class="fields-upload clearfix">
									<?php if(!empty($this->request->data[$model]['avatar_file_name'])) echo $this->Html->link($this->Upload->image($this->request->data, $model.'.avatar', array('style' => 'thumb')), '/files/'.$controller.'/original_'.$this->request->data[$model]['avatar_file_name'], array('class' => 'fancybox', 'escape' => false)) ?>
									<div class="fields-upload-input clearfix">
										<?php echo $this->Form->file('avatar', array('class' => 'custom-file')); ?>
									</div>
								</div>
							</div>
						</div>

						<div class="control-group clearfix">
							<?php echo $this->Form->label('new_password', __("New Password", true).' <em>*</em>', array('class' => 'control-label')); ?>
							<div class="controls">
								<?php echo $this->Form->input('new_password', array('type' => 'password', 'class' => 's150', 'error' => array( 'required' => __("Ingrese la contraseña", true) ) ) ); ?>
								<div class="help-block"><?php echo __('If you would like to change the password type a new one. Otherwise leave this blank.') ?></div>
							</div>
						</div>

						<div class="control-group clearfix">
							<div class="controls">
								<?php echo $this->Form->input('new_password_confirm', array('type' => 'password', 'class' => 's150', 'error' => __("Las contraseñas no coinciden", true) ) ); ?>
								<div class="help-block"><?php echo __('Type your new password again.') ?></div>
							</div>
						</div>

					</fieldset>
				</div><!-- /tab-pane -->

			</div>

		</div><!-- /.tabbable -->

		<div class="form-footer clearfix">
			<?php echo $this->Form->submit( __("Update Profile", true), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __("Cancel", true), array('action'=>'back', 'index'), array('class' => 'btn btn-small')) ?>
		</div>

		<?php echo $this->Form->input('id', array('type' => 'hidden' ) ); ?>
		<?php echo $this->Form->end(); ?>
	</div>
