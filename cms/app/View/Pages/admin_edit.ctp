	<div id="items">

		<div class="titles clearfix">
			<div class="pull-left">
				<h2><?php if(empty($this->request->data[$model]['id'])) echo __('Add', true); else echo __('Edit', true); ?> <?php echo __('Página') ?></h2>
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


						<div class="well form-vertical form-box">
							<fieldset>						

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.title_spa', __("Title", true).' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.title_spa', array('error' => array('required' => __("Enter the title", true)) ) ); ?>
									</div>
								</div>


								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.axis_id', __( 'Eje', true).' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.axis_id', array('empty' => '...', 'options' => $axes, 'error' => array('required' => __("Enter the product type", true)) ) ); ?>
									</div>
								</div>

								<div class="type_image">
							   		<div class="control-group clearfix">
								        <?php echo $this->Form->label('image', __( 'Imagen principal', true), array('class' => 'control-label')); ?>
								        <div class="controls">
									        <?php echo $this->Uploader->input('image', array('allowDelete' => false, 'showThumb' => true, 'error' => array('mimeType' => __('Invalid file extension', true) ))) ?>
								        </div>
							        </div>
						        </div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.tags', __('Etiquetas', true).' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.tags', array('error' => array('required' => __("Enter the title", true)) ) ); ?>
									</div>
								</div>

								<div class="control-group clearfix">
									<?php echo $this->Form->label($model.'.content_spa', __("Long description", true).' <em>*</em>', array('class' => 'control-label')); ?>
									<div class="controls">
										<?php echo $this->Form->input($model.'.content_spa', array('type' => 'textarea', 'placeholder' => __('Description'), 'class' => 's600 editor', 'error' => array('required' => __("Enter the description", true)) ) ); ?>
									</div>
								</div>



							</fieldset>
						</div>

							<div class="well form-vertical media">
								<h3><?php echo __('Imágenes y videos relacionados') ?></h3>
								<fieldset>
									<div id="media" class="ajaxContainer"></div>
									<p><?php echo $this->Html->link( __("Agregar imagen o video", true), array('controller' => 'media', 'action' => 'insert_index', 'model' => $model), array('rel' => "{'width':505,'height':470,'title':'".__('Add new image / video', true)."','addUrl':'page_id:#PageId','actions':'close'}", 'class' => 'modalLink', 'escape' => false)); ?></p>
								</fieldset>
							</div><!-- /.well -->

							<div class="well form-vertical prizes">
								<h3><?php echo __('Files') ?></h3>
								<fieldset>
									<div id="files" class="ajaxContainer"></div>
									<p><?php echo $this->Html->link( __("Add new file", true), array('controller' => 'files', 'action' => 'insert_edit'), array('rel' => "{'width':505,'height':470,'title':'".__('Add new file', true)."','addUrl':'page_id:#PageId'}", 'class' => 'modalLink', 'escape' => false)); ?></p>
								</fieldset>
							</div><!-- /.well -->

						</div><!-- /edit-main -->

				</div><!-- /tab-pane -->


		</div><!-- /.tabbable -->


		<div class="form-footer clearfix">
			<?php echo $this->Form->submit( __("Save", true), array('div' => false, 'class' => 'btn btn-primary') ); ?> <?php echo $this->Html->link( __("Cancel", true), array('action'=>'back', 'index'), array('class' => 'btn btn-small')) ?>
		</div>

		<?php echo $this->Form->input('id', array('type' => 'hidden') ); ?>
		<?php echo $this->Form->input('permalink', array('type' => 'hidden') ); ?>

	</div>
	<?php echo $this->Form->end(); ?>

	<script type="text/javascript">
	$(document).ready(function() {

		init = function(){

			var id = $('#<?php echo $model ?>Id').val();
			var mapTime;

			loadMedia = function(action){

				switch(action){
					case 'reload':
						$('#admin-modal').modal('hide');
						loadMedia();
						break;
					default:
						loader('#media', 'mediaLoader');
						$('#media').load('/admin/media/insert/model:<?php echo $model ?>/<?php echo $model_url ?>_id:'+id, function(){
							$('#media .btn').click(function(){
								loader('#media', 'mediaLoader');
								$.ajax({
									url:$(this).attr('href'),
									success:function(){
										loaderDelete('mediaLoader');
										loadMedia();
									}
								})
								return false;
							})
							bind_events();
							loaderDelete('mediaLoader');
						});
						$('#media').show();
				}
			}

			loadFiles = function(action){
				switch(action){
					case 'reload':
						$('#admin-modal').modal('hide');
						loadFiles();
						break;
					default:
						loader('#files', 'filesLoader');
						$('#files').load('/admin/files/insert/model:<?php echo $model ?>/<?php echo $model_url ?>_id:'+id, function(){
							$('#files .btn-ajax').click(function(){
								loader('#files', 'filesLoader');
								$.ajax({
									url:$(this).attr('href'),
									success:function(response){
										loaderDelete('filesLoader');
										loadFiles();
									}
								})
								return false;
							})
							bind_events();
							loaderDelete('filesLoader');
						});
						$('#files').show();
				}
			}

			//loadMedia();
			loadFiles();
			loadMedia();

		}

		<?php if(empty($this->request->data[$model]['id'])): ?>
		$.ajax({
			url:"<?php echo Router::url(array('controller' => $controller, 'action' => 'add')) ?>",
			dataType:'json',
			type:'get',
			success: function( response ){
				if(response.id.length){
					$('#<?php echo $model ?>Id').val( response.id );
					init();
				}
			}
		});
		<?php else: ?>
		init();
		<?php endif; ?>

	});

	</script>