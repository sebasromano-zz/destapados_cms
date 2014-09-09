<div class="modal admin-modal" id="admin-modal">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3><?php echo $title_for_layout ?></h3>
	</div>
	<div class="modal-body">
		<div id="modal-content" class="loading"><p>&nbsp;</p></div>
	</div>
	<div class="modal-footer">
		<div class="modal-actions modal-actions-close hidden">
			<?php echo $this->Html->link(__('Close'), '#', array('class' => 'btn btn-cancel btn-primary', 'data-dismiss' => 'modal', 'escape' => false)) ?>
		</div>
		<div class="modal-actions modal-actions-save hidden">
			<?php echo $this->Html->link(__('Save'), '#', array('class' => 'btn btn-primary btn-submit', 'escape' => false)) ?>
		</div>
		<div class="modal-actions modal-actions-default">
			<?php echo $this->Html->link(__('Save'), '#', array('class' => 'btn btn-primary btn-submit', 'escape' => false)) ?>
			<?php echo $this->Html->link(__('Cancel'), '#', array('class' => 'btn btn-cancel', 'data-dismiss' => 'modal', 'escape' => false)) ?>
		</div>
	</div>
</div>
