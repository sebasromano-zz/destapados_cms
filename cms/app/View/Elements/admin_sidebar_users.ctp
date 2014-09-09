	<div class="sidebar well">
		<ul class="nav nav-list">
			<li <?php if($_nav == 'users' && $_nav_sub == 'index') echo 'class="active"' ?>><?php echo $this->Html->link( __("Users", true).' <i class="icon-item icon-chevron-right icon-white"></i>', array('controller' => 'users', 'action' => 'index'), array( 'class' => '', 'escape' => false) ); ?></li>
			<li <?php if($_nav == 'users' && $_nav_sub == 'add') echo 'class="active"' ?>><?php echo $this->Html->link( __("Add User", true).' <i class="icon-item icon-chevron-right icon-white"></i>', array('controller' => 'users', 'action' => 'edit'), array( 'class' => '', 'escape' => false) ); ?></li>
			<li <?php if($_nav == 'users' && $_nav_sub == 'profile') echo 'class="active"' ?>><?php echo $this->Html->link( __("Profile", true).' <i class="icon-item icon-chevron-right icon-white"></i>', array('controller' => 'users', 'action' => 'profile'), array( 'class' => '', 'escape' => false) ); ?></li>
		</ul>
    	</div>
