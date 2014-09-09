<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
	<!-- meta -->
	<?php echo $this->Html->meta(array('charset' => 'utf-8')) ?>
	<?php echo $this->Html->meta( array('name' => 'viewport', 'content' => 'width=device-width,initial-scale=1')) ?>

	<!-- title -->
	<title>DESTAPADOS<?php if(!empty($title_for_layout)) echo ' :: '.$title_for_layout ?></title>

	<!-- css -->
	<?php if($this->Session->check('Auth.User')): ?>
	<style type="text/css">
	body { padding-top: 75px; padding-bottom: 40px; }
	</style>
	<?php endif ?>
	<?php echo $this->Html->css('bootstrap.min.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('bootstrap-fileupload.min.css', null, array('media' => 'screen,projection')); ?>
	<?php if ( isset($loadJQueryUI) ) echo $this->Html->css('/js/vendor/jquery_ui/custom-theme/jquery-ui-1.8.18.custom.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('/js/vendor/fancybox/jquery.fancybox.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('admin.css', null, array('media' => 'screen,projection')); ?>

	<!-- javascript -->
	<?php echo $this->Html->script('vendor/modernizr-2.6.2.min.js'); ?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
	<?php echo $this->Html->script('vendor/bootstrap.min.js'); ?>
	<?php echo $this->Html->script('vendor/bootstrap-fileupload.min.js'); ?>
	<?php if ( isset($loadCKEditor) ) echo $this->Html->script('vendor/ckeditor/ckeditor.js'); ?>
	<?php if ( isset($loadJQueryUI) ) echo $this->Html->script('vendor/jquery_ui/jquery-ui-1.8.18.custom.min.js'); ?>
	<?php if ( isset($loadJQueryTableDnD) ) echo $this->Html->script('vendor/jquery.tablednd.0.8.min.js'); ?>
	<?php echo $this->Html->script('vendor/fancybox/jquery.fancybox.pack.js'); ?>
	<?php echo $this->Html->script('vendor/jquery.filestyle.js'); ?>
	<?php echo $this->Html->script('vendor/bootstrap-fileupload.min.js'); ?>
	<?php echo $this->Html->script('admin.js'); ?>

</head>
<body>

	<?php if($this->Session->check('Auth.User')): ?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<?php echo $this->Html->link(file_exists(WWW_ROOT.'img'.DS.'admin-logo.png') ? $this->Html->image('admin-logo.png', array('alt' => 'IDITS')) : 'IDITS', '/admin', array('class' => 'brand', 'escape' => false)) ?>
				<?php echo $this->element('admin_nav') ?>
			</div>
		</div>
	</div>
	<?php endif ?>
	<div class="container">

		<?php echo $content_for_layout ?>
		<?php echo $this->element('sql_dump'); ?>

	</div><!--/.container-->

	<?php if ( isset($loadJQueryUI) ) echo $this->element('datepicker') ?>
	<?php if ( isset($loadCKEditor) ) echo $this->element('ckeditor_'.$loadCKEditor); ?>
	<?php echo $this->element('admin_modal'); ?>
	<?php echo $this->Js->writeBuffer() ?>

</body>
</html>
