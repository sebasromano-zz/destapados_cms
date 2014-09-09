<?php
class UploaderHelper extends Helper {

	var $helpers = array('Html','Form');

	function input($name, $options = null) {

	    // modelo y nombre
		$this->setEntity($name);
		$__model = $this->Form->model();
		$__field = $this->field();
		$__field_db = $__field.'_file_name';   // nombre de la columna en la base de datos (distinto del nombre del campo por el plugin)

		if( $_model =& ClassRegistry::getObject($__model) ) {

			// me fijo si hay algún error
			if( isset($this->validationErrors[$__model][$__field_db]) ) {
				// el campo pasa a valer nada
				$this->request->data[$__model][$__field_db] = '';
			}

		} else {
		    // si no puedo cargar el modelo relacionado no muestro nada
			return __('_FUB_ERROR_LOADING_MODEL', true);
		}

		// no deja borrar la imagen
		$showThumb = false;
		if(isset($options['showThumb']) && $options['showThumb']){
			unset($options['showThumb']);
			$showThumb = true;
		}

		// no deja borrar el archivo
		$allowDelete = false;
		if(isset($options['allowDelete']) && $options['allowDelete']){
			unset($options['allowDelete']);
			$allowDelete = true;
		}

		$exists = empty($this->request->data[$__model][$__field_db]) ? 'new' : 'exists';
		$output = '<div class="fileupload fileupload-'. $exists .'" data-provides="fileupload">';
		$output .=  '<span class="btn btn-file">';
		$output .=      '<span class="fileupload-new">'. __('Select file', true) .'</span>';
		$output .=      '<span class="fileupload-exists">'. __('Change', true) .'</span>';
		$output .=      $this->Form->input($name, array('type' => 'file', 'error' => false));
		/*
		$options['type'] = 'file';
		$output .=      $this->Form->input($name, $options);
		*/
		$output .=  '</span> ';
		
		//if( !empty($this->request->data[$__model][$__field_db]) ) {
		if( $showThumb ) {
		    if( $allowDelete ) {
		$output .=  '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">'.__('Remove', true).'</a> ';
		    }
		        
		$output .=  '<span class="fileupload-new thumbnail" style="width: 50px; height: 50px; line-height: 50px;">';
		        //if( empty($this->request->data[$__model][$__field_db]) ) {
		$output .=      $this->Html->image('thumb_default.gif');
		        //} else {
		$output .=  '</span>';
		$output .=  '<span class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;">';
		    if( !empty($this->request->data[$__model][$__field_db]) ) {
		$output .=      $this->Html->image('/files/'.strtolower(Inflector::pluralize($__model)).'/thumb_'.$this->request->data[$__model][$__field_db]);
		    }
		$output .=  '</span>';
		} else {
		//$output .=  '<span class="fileupload-preview fileupload-'. $exists .'">';
		$output .=  '<span class="fileupload-preview">';
		    if( !empty($this->request->data[$__model][$__field_db]) ) {
		$output .=      $this->request->data[$__model][$__field_db];
		    }
		$output .=  '</span>';
		}
		//}
		
		if($allowDelete) {
		    if( !$showThumb ) {
		$output .=  ' <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>';
		    }
		}
		$output .= '</div>';
		
		if( isset($options['error']) )
		    $output .= $this->Form->error($__field, $options['error']);
		else
		    $output .= $this->Form->error($__field);
		
		return $output;

	}

}
