	<div class="nav-main">
		<ul class="nav">
			<li<?php echo ( isset($_nav) && $_nav == "questions") ? ' class="active"' : ''; ?>><?php echo $this->Html->link( 'Preguntas', array('controller' => 'questions', 'action' => 'index'), array('escape' => false)) ?></li>
			<li<?php echo ( isset($_nav) && $_nav == "users") ? ' class="active"' : ''; ?>><?php echo $this->Html->link( 'Usuarios', array('controller' => 'gamers', 'action' => 'index'), array('escape' => false)) ?></li>
			<!--<li<?php echo ( isset($_nav) && $_nav == "categories") ? ' class="active"' : ''; ?>><?php echo $this->Html->link( 'Categorías', array('controller' => 'categories', 'action' => 'index'), array('escape' => false)) ?></li> -->
			<li><?php echo $this->Html->link( 'Salir', array( 'admin' => false, 'controller' => 'users', 'action' => 'logout'), array('escape' => false)) ?></li>
		</ul>
	</div><!--/.nav-main -->
