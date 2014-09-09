	<div class="sidebar well">
		<ul class="nav nav-list">
			<li <?php if($_nav == 'questions' && $_nav_sub == 'index') echo 'class="active"' ?>><?php echo $this->Html->link( __("Preguntas", true).' <i class="icon-item icon-chevron-right icon-white"></i>', array('controller' => 'questions', 'action' => 'index'), array( 'class' => '', 'escape' => false) ); ?></li>

			<li <?php if($_nav == 'questions' && $_nav_sub == 'add') echo 'class="active"' ?>><?php echo $this->Html->link( __("Add pregunta", true).' <i class="icon-item icon-chevron-right icon-white"></i>', array('controller' => 'questions', 'action' => 'edit'), array( 'class' => '', 'escape' => false) ); ?></li>
		</ul>
    	</div>
