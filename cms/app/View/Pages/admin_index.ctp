
	<div id="items">


	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __('Páginas') ?></h2>
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
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => '/'.$this->params['url'])) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p15"><?php echo $this->Paginator->sort($model.'.image_file_name', __("Preview", true), array('class' => 'sorter') ); ?></th>
			<th class="p30"><?php echo $this->Paginator->sort($model.'.title_spa', __("Title", true), array('class' => 'sorter') ); ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item[$model]['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item[$model]['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item[$model]['id'], 'class' => 'check')) ?></td>
			<td><?php echo (!empty($item[$model]['image_file_name']))?$this->Html->link($this->Html->image('/files/'.$controller.'/thumb_'.$item[$model]['image_file_name']), '/files/'.$controller.'/medium_'.$item[$model]['image_file_name'], array('rel' => $model, 'class' => 'fancybox', 'escape' => false)) : ' - ' ?></td>
			<td><?php echo $item[$model]['title_spa'] ?> <br />
				<input type="text" class="s400" value="<?php echo Router::url( array('controller' => 'news', 'action' => 'view', $item[$model]['id'], $item[$model]['permalink'] ), true)  ?>" name="test[]" /></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__("Edit", true), array('action' => 'edit', $item[$model]['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
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

	</div>
