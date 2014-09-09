<?php
/**
 * Permalink Behavior by envero.org
 *
 * Ejemplo de uso:
 *
 *	var $actsAs = array(
 * 		// los parÃ¡metros son opcionales y los indicados son los por defecto
 *		'Permalink' => array(
 *			'field_id' => 'id', // campo de id comÃºn
 * 			'field_permalink' => 'permalink', // campo donde se guardarÃ¡ el permalink generado
 * 			'field_source' => 'title', // campo usado para generar el permalink
 * 			'separator' => '-', // separador (para espacios en blanco)
 *			)
 *		);
 *
 */
class PermalinkBehavior extends ModelBehavior{

	/**
     * Inicializa el comportamiento.
     *
     * @param object $Model Modelo
     * @param array $options configuraciones
     * @access public
     */
    function setup(&$model, $options = array()){
		
		$model->permalinkSettings = array();
		$model->permalinkSettings['field_id'] = (isset($options['field_id']))?$options['field_id']:'id';			
		$model->permalinkSettings['field_permalink'] = (isset($options['field_permalink']))?$options['field_permalink']:'permalink';			
		$model->permalinkSettings['field_source'] = (isset($options['field_source']))?$options['field_source']:'title';	
		$model->permalinkSettings['separator'] = (isset($options['separator']))?$options['separator']:'-';	

	}

	/**
	 * Al guardar seteo el permalink
	 */
	function beforeSave(&$model){
		$sourceText = '';
		// si no tiene seteado el campo id, debo generar permalink
		if( empty( $model->data[ $model->name ][ $model->permalinkSettings['field_permalink'] ] ) ){
			// obtengo el texto base para generar el permalink
			$sourceText = ( isset( $model->data[ $model->name ][ $model->permalinkSettings['field_source'] ] ) )? $model->data[ $model->name ][ $model->permalinkSettings['field_source'] ]:'';
			// genera el permalink
			$model->data[ $model->name ][ $model->permalinkSettings['field_permalink'] ] = $this->generatePermalink( $model, $sourceText);
		}
		// retorna TRUE para poder guardar
		return true;
	}
	
	function generatePermalink(	&$model, $text = ''){
		$permalink = '';
		if(!empty($text)){			
			// lee el encoding de la aplicaciÃ³n
			mb_internal_encoding( Configure::read('App.encoding') );
			$permalink = Inflector::slug( mb_strtolower( $text ),  $model->permalinkSettings['separator']);
			
			// busco para ver que sea Ãºnico (igual sino me darÃ­a error al guardar)
			$model->recursive = -1;
			$conditions = array();
			$conditions[ $model->permalinkSettings['field_permalink'] ] = $permalink;
			$item = $model->find('first', array( 'conditions' => $conditions ) );
			// si existe agrego una marca de tiempo
			if($item){
				$varcharLen = 255;
				$microtime = $model->permalinkSettings['separator'].time();
				// si es muy largo el string lo corto
				if( mb_strlen($permalink) > $varcharLen ){
					// mb_substr PHP  > 4.0.6
					$permalink = mb_substr( $permalink, 0, ( $varcharLen - mb_strlen($microtime) ) ); 
				}
				// finalemnte al permalink le agrego el timestamp
				$permalink.=$microtime;
			}
			return $permalink;
		}
		return '';
	}

}
?>
