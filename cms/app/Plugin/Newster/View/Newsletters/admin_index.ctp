	<div id="items">

	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __d( 'newster', 'Boletines') ?></h2>
		</div>
	</div>

	<?php echo $this->Session->flash(); ?>

	<div class="search clearfix form-inline">
		<div class="pull-left btn-group">
		<?php
			echo $this->Html->link( '<i class="icon-plus"></i> '.__d( 'newster', 'Nuevo boletín'), array( 'plugin' => 'newster', 'action' => 'edit'), array('class' => 'btn btn-small', 'escape' => false));
			echo $this->Html->link( '<i class="icon-trash"></i> '.__d( 'newster', 'Eliminar'), '#', array('class' => 'btn btn-small to_action'.( count( $items ) <= 0 ? ' disabled ' : ''), 'rel' => 'delete', 'escape' => false));
		?>
		</div>
		<div class="search-main pull-right">
			<?php echo $this->Form->create('Search', array('url' => array( 'plugin' => 'newster', 'controller' => 'newsletters', 'action' => 'index'), 'inputDefaults' => array('div' => false, 'label' => false, 'class' => 's175 pull-left search-item')) ) ?>
			<div class="search-query-field pull-right">
				<?php echo $this->Form->input('query', array('placeholder' => __('Search'), 'class' => 's150 search-query')) ?>
				<i class="icon-search"></i>
				<?php if($this->Session->check('App.Newster.Newsletter.query')) echo $this->Html->link('<i class="icon-remove"></i>', array( 'plugin' => 'newster', 'action' => 'index', 'search' => 'clear-search'), array('class' => 'clear-search-ico clear-search', 'escape' => false) ) ?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>

	<?php if ( count( $items ) > 0 ) : ?>
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => '/'.$this->params['url'])) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p20"><?php echo $this->Paginator->sort( 'Newsletter.send_date', __d( 'newster', 'Fecha envío'), array('class' => 'sorter') ); ?></th>
			<th class="p30"><?php echo $this->Paginator->sort( 'Newsletter.newsletter_title', __d( 'newster', 'Título'), array('class' => 'sorter') ); ?></th>
			<th class="p20"><?php echo $this->Paginator->sort( 'Newsletter.newsletter_title', __d( 'newster', 'Estado'), array('class' => 'sorter') ); ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item['Newsletter']['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item['Newsletter']['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item['Newsletter']['id'], 'class' => 'check')) ?></td>
			<td><?php echo $this->Time->format('d/m/Y H:i',$item['Newsletter']['send_date']); ?></td>
			<td><?php echo $item['Newsletter']['newsletter_title']; ?></td>
			<td><?php echo (isset( $newsletter_status[ $item['Newsletter']['status'] ]))? $newsletter_status[ $item['Newsletter']['status'] ] : '' ?></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-eye-open"></i> '.__d( 'newster', 'Preview'), array( 'admin' => false, 'plugin' => 'newster', 'controller' => 'submissions', 'action' => 'view', $item['Newsletter']['id']), array( 'target' => '_blank', 'class' => 'btn btn-small', 'escape' => false) ); ?>
					<?php if( !in_array($item['Newsletter']['status'], array(2,3,4) ) ): ?>
					<?php echo $this->Html->link( '<i class="icon-list"></i> '.__d( 'newster', 'Noticias'), array( 'plugin' => 'newster', 'action' => 'news', $item['Newsletter']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__d( 'newster', 'Editar'), array( 'plugin' => 'newster', 'action' => 'edit', $item['Newsletter']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
					<?php endif ?>
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