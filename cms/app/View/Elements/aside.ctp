            <div class="col-left">
                <h3>Categorías</h3>

				<ul class="categories">
				    <?php foreach($_producttypes as $category): ?>

				        <li><?php echo $this->Html->link($category['Producttype']['title_spa'], array('controller' => 'products', 'action' => 'index', $category['Producttype']['id'],$category['Producttype']['permalink']), array('title' => $category['Producttype']['title_spa'], isset($_nav) && $_nav == $category['Producttype']['permalink'] ? ' class="selected"' : '')); ?></li>

				    <?php endforeach; ?>
				</ul>

                <div class="b clearfix">

                <?php echo $this->Form->create('Product', array( 'url' => array('action' => 'search'),  'inputDefaults' => array( 'label' => false, 'div' => false, 'class' => '')) ) ?>

                    <?php echo $this->Form->input('q', array('placeholder' => 'Buscar', 'id' => 'serch')); ?>
                    <?php echo $this->Form->submit('Buscador', array('class' => 'ir serch style_button')); ?>

                <?php echo $this->Form->end() ?>

                </div>

                <ul class="left-nav">
                    <li <?php echo (isset($_nav) && $_nav == "home") ? ' class="selected"' : '' ?>><?php echo $this->Html->link('inicio', '/'); ?></li>
                    <li <?php echo (isset($_nav) && $_nav == "we") ? ' class="selected"' : '' ?>><?php echo $this->Html->link('condiciones', array('controller' => 'pages', 'action' => 'display', 'condiciones')); ?></li>
                    <li <?php echo (isset($_nav) && $_nav == "questions") ? ' class="selected"' : '' ?>><?php echo $this->Html->link('preguntas frecuentes', array('controller' => 'questions', 'action' => 'index')); ?></li>
                    <li <?php echo (isset($_nav) && $_nav == "contact") ? ' class="selected"' : '' ?>><?php echo $this->Html->link('contacto', array('controller' => 'pages', 'action' => 'contact')); ?></li>
                </ul>

                <h3>Novedades</h3>

                <?php echo $this->element('subscribe'); ?>

                <div class="envios">
                    <h3 class="ir truck">Retiro y entrega a domicilio</h3>
                    <p>Retiro  entrega a domicilio</p>
                </div>
            </div>