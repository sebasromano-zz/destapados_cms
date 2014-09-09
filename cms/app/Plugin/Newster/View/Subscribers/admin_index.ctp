	<div id="items">

	<div class="titles clearfix">
		<div class="pull-left">
			<h2><?php echo __d( 'newster', 'Suscriptores') ?></h2>
		</div>
	</div>

	<?php echo $this->Session->flash(); ?>

	<div class="search clearfix form-inline">
		<div class="pull-left btn-group">
			
			<?php 
			echo $this->Html->link( __d( 'newster', 'Acciones') . ' <i class="caret"></i>', '#', array( 'data-toggle' => 'dropdown', 'class' => 'btn btn-small dropdown-toggle', 'escape' => false));
			echo $this->Html->link( '<i class="icon-plus"></i> '.__d( 'newster', 'Nuevo suscriptor'), array( 'plugin' => 'newster', 'action' => 'edit'), array('class' => 'btn btn-small', 'escape' => false)); ?>
			<?php //echo $this->Html->link( '<i class="icon-file"></i> '.__d( 'newster', 'Importar...'), '#', array('class' => 'btn btn-small to_action'.( count( $items ) <= 0 ? ' disabled ' : ''), 'rel' => 'delete', 'escape' => false)) ?>			
			<ul class="dropdown-menu">
				<?php foreach( $newsletterlists as $list_id => $list_title): ?>
				<li><?php echo $this->Html->link( __d( 'newster', 'Agregar a lista ').'"'.$list_title.'"', '#', array('class' => 'to_action', 'rel' => 'addtolist/'.$list_id, 'escape' => false) ) ?></li>
				<li><?php echo $this->Html->link( __d( 'newster', 'Quitar de lista ').'"'.$list_title.'"', '#', array('class' => 'to_action', 'rel' => 'removefromlist/'.$list_id, 'escape' => false) ) ?></li>
			<?php endforeach ?>
				<li class="divider"></li>
				<li><?php echo $this->Html->link( __d( 'newster', 'Marcar como activos'), '#', array('class' => 'to_action', 'rel' => 'active', 'escape' => false) ) ?></li>
				<li><?php echo $this->Html->link( __d( 'newster', 'Marcar como inactivos'), '#', array('class' => 'to_action', 'rel' => 'unactive', 'escape' => false) ) ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link( __d( 'newster', 'Eliminar'), '#', array('class' => 'to_action', 'rel' => 'delete', 'escape' => false) ) ?></li>
			</ul>
		</div>
		<div class="search-main pull-right">
			<?php echo $this->Form->create('Search', array('url' => array( 'plugin' => 'newster', 'controller' => 'subscribers', 'action' => 'index'), 'inputDefaults' => array('div' => false, 'label' => false, 'class' => 's175 pull-left search-item')) ) ?>
			<div class="search-query-field pull-right">
				<?php echo $this->Form->input('query', array('placeholder' => __('Search'), 'class' => 's150 search-query')) ?>
				<i class="icon-search"></i>
				<?php if($this->Session->check('App.Newster.Subscribers.query')) echo $this->Html->link('<i class="icon-remove"></i>', array( 'plugin' => 'newster', 'action' => 'index', 'search' => 'clear-search'), array('class' => 'clear-search-ico clear-search', 'escape' => false) ) ?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>

	<?php if ( count( $items ) > 0 ) : ?>
	<?php echo $this->Form->create('Index', array('id' => 'IndexForm', 'url' => '/'.$this->params['url'])) ?>
	<table class="table table-striped">
		<tr>
			<th class="p2"><?php echo $this->Form->input('all', array('label' => false, 'type' => 'checkbox', 'value' => 'all', 'class' => 'allCheckBox')) ?></th>
			<th class="p20"><?php echo $this->Paginator->sort( 'Subscriber.full_name', __d( 'newster', 'Nombre'), array('class' => 'sorter') ); ?></th>
			<th class="p30"><?php echo $this->Paginator->sort( 'Subscriber.email', __d( 'newster', 'Email'), array('class' => 'sorter') ); ?></th>
			<th class="p30"><?php echo  __d( 'newster', 'Intereses') ?></th>
			<th class="p2"><?php echo  __d( 'newster', 'Activo') ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php $i=1; foreach ( $items as $item ): ?>
		<tr id="item_<?php echo $item['Subscriber']['id'] ?>">
			<td><?php echo $this->Form->input('Index.check'.$item['Subscriber']['id'], array('label' => false, 'type' => 'checkbox', 'value' => $item['Subscriber']['id'], 'class' => 'check')) ?></td>
			<td><?php echo $item['Subscriber']['full_name']; ?></td>
			<td><?php echo $item['Subscriber']['email']; ?></td>
			<td>
				<?php if( !empty($item['SubscribersList'])): ?>
				<?php $a=0;foreach( $item['SubscribersList'] as $lista): 
					echo ($a > 0)?', ':'';
					echo (isset( $newsletterlists[ $lista['newsletterlist_id'] ] ))?$newsletterlists[ $lista['newsletterlist_id'] ]:'';
					$a++;endforeach;
				 endif ?>
			</td>
			<td><?php echo ($item['Subscriber']['active'] == 1)?'<i class="icon-ok"></i>':'' ?></td>
			<td>
				<div class="pull-right btn-group">
					<?php echo $this->Html->link( '<i class="icon-pencil"></i> '.__d( 'newster', 'Editar'), array( 'plugin' => 'newster', 'action' => 'edit', $item['Subscriber']['id']), array( 'class' => 'btn btn-small', 'escape' => false) ); ?>
				</div>
			</td>
		</tr>
		<?php $i++; endforeach; ?>
	</table>
	<script type="text/javascript">
	$(function(){
		checkboxes_actions("newster/subscribers");
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