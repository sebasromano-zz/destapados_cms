<?php $base = Router::url( '/', true) ?>

<table style="border: none; border-collapse: collapse; width: 650px;">
<tr>
	<td colspan="2" style="padding: 10px; border-bottom: 1px solid #ccc;"><?php echo $this->Html->link( $this->Html->image(  $base . '/img/admin-login-logo.png', array('alt' => 'GDP, Noticias de nuestra gestión')), $base, array('escape' => false) ) ?></td>
</tr>
<?php 

foreach( $items as $item): 

	$link = $item['url'];
	//if( empty($link) && isset($item['permalink']) )$link = 'http://prensa.mendoza.gov.ar/noticia/'.$item['id'].'/'.$item['permalink'];
	//elseif( empty($link) )$link = '#';

	$title =  $item['title'];
	if( empty($title) && isset($item['title_spa']) )$title = $item['title_spa'];

	$content =  $item['content'];
	if( empty($content) && isset($item['summary_spa']) )$content = $item['summary_spa'];

	$image = '';
	if( !empty($item['pic_file_name'] ) )$image = '<img src="'.$base.'/files/newslettersitems/mini_'. $item['pic_file_name'].'" />';

?>
<tr>
	<td style="padding: 10px; width: 210px;"><?php echo $image ?></td>
	<td style="padding: 10px; vertical-align: top;">
		<p style="font-famliy: Arial, sans-serif; font-size: 18px; color: #023965; margin: 0 0 5px 0;"><a href="<?php echo $link ?>" style="color: #023965;"><?php echo $title ?></a></p>
		<p style="font-famliy: Arial, sans-serif; font-size: 12px; color: #333; margin: 0;"><?php echo $content ?> </p>
	</td>
</tr>
<?php endforeach ?>
<tr>
	<td colspan="2" style="font-famliy: Arial, sans-serif; font-size: 12px; color: #333; padding: 10px; border-top: 1px solid #ccc;">IDITS (c) 2014</td>
</tr>
</table>