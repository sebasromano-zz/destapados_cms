<?php //Ñandú

class NewslettersoptionsController extends AppController {

	var $name = 'Newslettersoptions';

	function admin_edit() {
		
        $this->layout = 'admin';
    	$this->set('title_for_layout', __('Opciones', true));
    	$this->set('saved', 0);
		$id = 1; 

	    $this->set('_selected_menu', "newsletters");
	    $this->set('_selected_submenu', "newslettersoptions");

	    // return page handling
		if ( $this->Session->check('App.returnTo') ) : $returnTo = $this->Session->read('App.returnTo'); else: $returnTo = 'index'; endif;
		$this->set( 'returnTo', $returnTo );

		if ( !empty($this->data) ) {
			
			$this->data['Newslettersoption']['id'] = 1;

			if($this->Newslettersoption->save($this->data)){
		    	$this->set('saved', 1);
			}

        } else if ( !empty($id) ) {
			$this->data = $this->Newslettersoption->read(null, $id);
  		}

    }


}
?>