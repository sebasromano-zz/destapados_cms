<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
<head>
    <!-- meta -->
    <?php echo $this->Html->meta(array('charset' => 'utf-8')) ?>
    <?php echo $this->Html->meta( array('name' => 'viewport', 'content' => 'width=device-width,initial-scale=1')) ?>

    <meta content='index, follow, noodp, noarchive' name='robots'>
    <meta content='index, follow' name='googlebot'>

    <!-- title -->
    <title><?php if( isset($title_for_layout) && !empty($title_for_layout) ) echo " :: $title_for_layout" ?></title>
</head>
<body>
    
<?php echo $content_for_layout; ?>
<?php echo $this->element('sql_dump'); ?>

</body>
</html>


