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
	<title>Venta Resuelta<?php if(!empty($title_for_layout)) echo ' - '.$title_for_layout ?></title>

	<!-- css -->
	<?php echo $this->Html->css('bootstrap.min.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('bootstrap-fileupload.min.css', null, array('media' => 'screen,projection')); ?>
	<?php if ( isset($loadJQueryUI) ) echo $this->Html->css('/js/vendor/jquery_ui/custom-theme/jquery-ui-1.8.18.custom.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('/js/vendor/fancybox/jquery.fancybox.css', null, array('media' => 'screen,projection')); ?>
	<?php echo $this->Html->css('admin.css', null, array('media' => 'screen,projection')); ?>
	<style type="text/css">
	body { padding:0; }
	</style>

	<!-- javascript -->
	<?php echo $this->Html->script('vendor/modernizr-2.6.2.min.js'); ?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
	<?php echo $this->Html->script('vendor/bootstrap.min.js'); ?>
	<?php if ( isset($loadCKEditor) ) echo $this->Html->script('vendor/ckeditor/ckeditor.js'); ?>
	<?php if ( isset($loadJQueryUI) ) echo $this->Html->script('vendor/jquery_ui/jquery-ui-1.8.18.custom.min.js'); ?>
	<?php echo $this->Html->script('vendor/fancybox/jquery.fancybox.pack.js'); ?>
	<?php echo $this->Html->script('vendor/jquery.filestyle.js'); ?>
	<?php echo $this->Html->script('admin.js'); ?>

</head>
<body>

	<?php echo $content_for_layout ?>

	<?php if ( isset($loadJQueryUI) ) echo $this->element('datepicker') ?>
	<?php if ( isset($loadCKEditor) ) echo $this->element('ckeditor_'.$loadCKEditor); ?>
	<?php echo $this->Js->writeBuffer() ?>

</body>
</html>
