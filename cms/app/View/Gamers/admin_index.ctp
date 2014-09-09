
	<?php if(!$this->params['isAjax']): ?>
	<?php //echo $this->element('admin_sidebar_questions') ?>

	<div id="items">
	<?php endif; ?>

	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __("Usuarios") ?></h2>
		</div>
	</div>

	
	<?php echo $this->Session->flash(); ?>

	<div class="search clearfix form-inline">
		<div class="pull-left btn-group">
		<?php
			echo $this->Html->link( '<i class="icon-plus"></i> '.__("Add", true), array('action' => 'edit'), array('class' => 'btn btn-small', 'escape' => false));
			echo $this->Html->link( '<i class="icon-trash"></i> '.__("Delete", true), '#', array('class' => 'btn btn-small to_action'.( count( $items ) <= 0 ? ' disabled ' : ''), 'rel' => 'delete', 'escape' => false));
		?>
		</div>
		<div class="search-main pull-right">
			<?php echo $this->Form->create('Search', array('url' => array( 'controller' => $controller, 'action' => 'index'), 'inputDefaults' => array('div' => false, 'label' => false, 'class' => 's175 pull-left search-item')) ) ?>
			<div class="search-query-field pull-right">
				<?php echo $this->Form->input('query', array('placeholder' => __('Search'), 'class' => 's150 search-query')) ?>
				<i class="icon-search"></i>
				<?php if($this->Session->check('App.'.$model.'.query')) echo $this->Html->link('<i class="icon-remove"></i>', array( 'controller' => $controller, 'action' => 'index', 'search' => 'clear-search'), array('class' => 'clear-search-ico clear-search', 'escape' => false) ) ?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>

	<?php if ( count( $items ) > 0 ) : ?>
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => $this->params['url'])) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p40"><?php echo $this->Paginator->sort('Gamer.name', __("Categoría", true), array('class' => 'sorter') ); ?></th>
			<th class="p10">Género</th>
			<th class="p10">Niveles</th>
			<th class="p10">Puntaje</th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item[$model]['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item['Gamer']['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item['Gamer']['id'], 'class' => 'check')) ?></td>
			<td><?php echo $item['Gamer']['name']; ?></td>
			<td><?php echo $item['Gamer']['gender']; ?></td>
			<td><?php echo $item['Gamer']['levels'] ?></td>
			<td><?php echo $item['Gamer']['score'] ?></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__("Edit", true), array('action' => 'edit', $item['Gamer']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
				</div>
			</td>
		</tr>
		<?php $i++; endforeach; ?>
	</table>
	<script type="text/javascript">
	$(function(){
		checkboxes_actions("<?php echo $controller ?>");
	})
	</script>
	<?php echo $this->Form->end() ?>
	<?php else : ?>
	<div class="alert">
		<?php echo __("No records") ?>
	</div>
	<?php endif; ?>

	<?php echo $this->element('admin_pag') ?>
	<?php if(!$this->params['isAjax']): ?>
	</div>
	<?php endif; ?>
