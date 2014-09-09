	<div id="items">

	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __d( 'newster', 'Listas de suscripción') ?></h2>
		</div>
	</div>

	<?php echo $this->Session->flash(); ?>

	<div class="search clearfix form-inline">
		<div class="pull-left btn-group">
		<?php
			echo $this->Html->link( '<i class="icon-plus"></i> '.__d( 'newster', 'Nueva'), array( 'plugin' => 'newster', 'action' => 'edit'), array('class' => 'btn btn-small', 'escape' => false));
			echo $this->Html->link( '<i class="icon-trash"></i> '.__d( 'newster', 'Eliminar'), '#', array('class' => 'btn btn-small to_action'.( count( $items ) <= 0 ? ' disabled ' : ''), 'rel' => 'delete', 'escape' => false));
		?>
		</div>
	</div>

	<?php if ( count( $items ) > 0 ) : ?>
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => '/'.$this->params['url'])) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p60"><?php echo $this->Paginator->sort( 'Newsletterlist.title', __d( 'newster', 'Título'), array('class' => 'sorter') ); ?></th>
			<th class="p10"><?php echo __d( 'newster', 'Activa') ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item['Newsletterlist']['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item['Newsletterlist']['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item['Newsletterlist']['id'], 'class' => 'check')) ?></td>
			<td><?php echo $item['Newsletterlist']['title']; ?></td>
			<td><?php echo ($item['Newsletterlist']['active'] == 1)?'Sí':'-' ?></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__d( 'newster', 'Editar'), array( 'plugin' => 'newster', 'action' => 'edit', $item['Newsletterlist']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
				</div>
			</td>
		</tr>
		<?php $i++; endforeach; ?>
	</table>
	<script type="text/javascript">
	$(function(){
		checkboxes_actions("newster/newsletterlists");
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