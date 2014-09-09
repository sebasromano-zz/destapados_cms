<?php
App::uses('Model', 'Model');
class AppModel extends Model {

	public function find($type, $options = array()) {
		 switch ($type) {
			 case 'superlist':
				 if(!isset($options['fields']) || count($options['fields']) < 3) {
					 return parent::find('list', $options);
				 }

				 if(!isset($options['separator'])) {
					 $options['separator'] = ' / ';
				 }
				 $i = 0;
				 foreach($options['fields'] as $field){
					$fieldList[ $i ] = explode(".", $field);
					$i++;
				 }
				 $list = parent::find('all', $options);

				 $returnList = array();
				 foreach($list as $item){
					  $returnList[ $item[ $this->name ][ $fieldList[0][1] ] ] = $item[ $fieldList[1][0] ][ $fieldList[1][1] ] . $options['separator'] .  $item[ $fieldList[2][0] ][ $fieldList[2][1] ];
				 }

				 return $returnList;
				
			 break;

			 default:
				 return parent::find($type, $options);
			 break;
		 }
	}

    /**
     * Source: https://github.com/dereuromark/tools/blob/master/Model/MyModel.php#L1191
     * Validation of Date fields (as the core one is buggy!!!)
     *
     * @param options
     * - dateFormat (defaults to 'ymd')
     * - allowEmpty
     * - after/before (fieldName to validate against)
     * - min (defaults to 0 - equal is OK too)
     * @return bool Success
     * 2011-03-02 ms
     */
    /*public function validateDate($data, $options = array()) {
        
        // TODO: revisar si esta debiera ser la fecha
        if (!defined('DEFAULT_DATE')) {
            define('DEFAULT_DATE', date('Y-m-d'));
        }
        if (!defined('FORMAT_DB_DATE')) {
            define('FORMAT_DB_DATE', date('Y-m-d'));
        }
        
        $format = !empty($options['format']) ? $options['format'] : 'ymd';
        if (is_array($data)) {
            $value = array_shift($data);
        } else {
            $value = $data;
        }

        $dateTime = explode(' ', trim($value), 2);
        $date = $dateTime[0];

        if (!empty($options['allowEmpty']) && (empty($date) || $date == DEFAULT_DATE)) {
            return true;
        }
        if (Validation::date($date, $format)) {
            # after/before?
            $days = !empty($options['min']) ? $options['min'] : 0;
            if (!empty($options['after']) && isset($this->data[$this->alias][$options['after']])) {
                die(pr( $this->data[$this->alias][$options['after']].' > '.date(FORMAT_DB_DATE, strtotime($date) - $days * DAY) ));
                if ($this->data[$this->alias][$options['after']] > date(FORMAT_DB_DATE, strtotime($date) - $days * DAY)) {
                    return false;
                }
            }
            if (!empty($options['before']) && isset($this->data[$this->alias][$options['before']])) {
                if ($this->data[$this->alias][$options['before']] < date(FORMAT_DB_DATE, strtotime($date) + $days * DAY)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }*/

    /**
     * Compara fechas
     * @param options array
     * - compareField
     * - compareValue
     * - compareOperator
     *
     * Ejemplo: (notar el array de parametros dentro de "rule")
     * $validate = array( 'field_name' => array( 'rule' => array('validateDate', array('compareField' => 'another_field_name', 'compareOperator' => '>=')), 'on' => 'create', 'allowEmpty' => false, 'required' => true ) );
     *
     * 
     * TODO: agregar el manejo de distintos formatos, por ahora solo funciona con el formato estadounidense
     */
    public function validateDate($check, $options = array()) {
        $defaultOptions = array(
            'compareField' => false,            // nombre del campo en $this->request->data contra el cual comparar. Tiene precedencia sobre compareValue
            'compareValue' => date('Y-m-d'),    // valor contra el cual compara
            'compareOperator' => '>='           // tipo de comparación a realizar: ==, !=, >, >=, <, <=, (otros?)
            //'format'                          // TODO: ver como manejar el tema de los formatos?
        );
        $options = array_merge( $defaultOptions, $options );
        
        if( is_array($check) ) {
            $value = array_values($check);
            $value = $value[0];
        } else {
            $value = $check;
        }
        //$value = is_array($check) ? array_shift($data) : $check ;

        //$is_create = empty($this->id) && ( !isset($this->data[$this->alias][$this->primaryKey]) || empty($this->data[$this->alias][$this->primaryKey]) );    // determino si es un create o un update

        /*if( isset($options['required']) && !empty($options['required']) && !isset($this->data[$this->alias][]) ) {
            
        }*/

        /*
        if( isset($options['on']) && !empty($options['on']) ) {
            if( $options['on'] === 'create' && !$is_create ) {       // valida si es una regla on => create y estamos en un update
                return true;
            } elseif( $options['on'] === 'update' && $is_create ) {  // valida si es una regla on => update y estamos en un create
                return true;
            } else {                                                // opción "on" incorrecta
                return false;   // TODO: lanzar una excepción
            }
        }
        
        if( !empty($options['allowEmpty']) && empty($value) ) {
            return true;
        }
        */
        
        //if (Validation::date($date, $format)) {
            $compareTo = $options['compareValue'];
            if( !empty($options['compareField']) ) {
                if( isset($this->data[$this->alias][$options['compareField']]) ) {
                    $compareTo = $this->data[$this->alias][$options['compareField']];
                } else {
                    // TODO: lanzar una excepción ?
                    return false;
                }
            }//die(pr( $compareTo ));
            
            $compareMap = array(
                '==' => $value == $compareTo,
                '!=' => $value != $compareTo,
                '>' => $value > $compareTo,
                '>=' => $value >= $compareTo,
                '<' => $value < $compareTo,
                '<=' => $value <= $compareTo,
            );

            if( isset($compareMap[ $options['compareOperator'] ]) ) {
                return $compareMap[ $options['compareOperator'] ];
            } else {
                // TODO: lanzar una excepción ?
                return false;
            }
            
        //}
        //return false;
    }

}
