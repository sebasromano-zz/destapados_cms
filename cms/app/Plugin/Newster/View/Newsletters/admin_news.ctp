	<div id="items">

	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __d( 'newster', 'Noticias relacionadas') ?></h2>
			<h3></h3>
		</div>
	</div>

	<?php echo $this->Session->flash(); ?>

	<div class="search clearfix form-inline">
		<div class="pull-left btn-group">
		<?php
			echo $this->Html->link( '<i class="icon-chevron-left"></i> '.__d( 'newster', 'Volver a boletines'), array( 'plugin' => 'newster', 'action' => 'index'), array('class' => 'btn btn-small', 'escape' => false));
			echo $this->Html->link( '<i class="icon-plus"></i> '.__d( 'newster', 'Nueva noticia'), array( 'plugin' => 'newster', 'action' => 'newsedit'), array('class' => 'btn btn-small', 'escape' => false));
			echo $this->Html->link( '<i class="icon-trash"></i> '.__d( 'newster', 'Eliminar'), '#', array('class' => 'btn btn-small to_action'.( count( $items ) <= 0 ? ' disabled ' : ''), 'rel' => 'newsdelete', 'escape' => false));
		?>
		</div>
	</div>

	<?php if ( count( $items ) > 0 ) : ?>
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => array( 'controller' => 'newsletters') )) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p10"><?php echo __d( 'newster', 'N° de Orden') ?></th>
			<th class="p40"><?php echo __d( 'newster', 'Título') ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item['NewslettersItem']['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item['NewslettersItem']['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item['NewslettersItem']['id'], 'class' => 'check')) ?></td>
			<td><?php echo $item['NewslettersItem']['order_by']; ?></td>
			<td><?php if( !empty($item['NewslettersItem']['title']) ){echo $item['NewslettersItem']['title']; }else{ if(isset($item['Related']['title_spa'])) echo $item['Related']['title_spa']; } ?></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__d( 'newster', 'Editar'), array( 'plugin' => 'newster', 'action' => 'newsedit', $item['NewslettersItem']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
				</div>
			</td>
		</tr>
		<?php $i++; endforeach; ?>
	</table>
	<script type="text/javascript">
	$(function(){
		checkboxes_actions("newster/newsletters");
	})
	</script>
	<?php echo $this->Form->end() ?>
	<?php else : ?>
	<div class="alert">
		<?php echo __("No records") ?>
	</div>
	<?php endif; ?>

	<?php echo $this->element('admin_pag') ?>

	</div>