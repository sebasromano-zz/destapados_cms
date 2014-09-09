<?php
/**
 * Envero helper
 * improvements de algunas funciones y otras necesarias
 */
class EnveroHelper extends Helper
{
	//
	var $helpers = array('Html','Session');
	var $first = array(); // almacena el primer elemento (luego de un getFirst)

    /**
     * Retorna el primer elemento de un array, agregar condiciones
     */
    function getFirst($elements, $condition = ''){
    
        if( isset($elements[0]) && is_array($elements) ){
            $this->first = $elements[0];
            return $elements[0];
        }else{
            $this->first = array();
            return false;
        }
    
    }

   /**
    * Modifica la url, para agregarle http://
    */
   function externalLink($title, $url, $options = null, $confirm = null, $scape = true, $prefix = 'http://'){

        if(strpos($url, 'http') === false) return $this->Html->link( $title, $prefix . $url, $options, $confirm, $scape);
        else return $this->Html->link( $title, $url, $options, $confirm, $scape);

    }

   /**
    * Cargo la imagen de vista previa de videos externos (youtube y vimeo)
    */
   //function externalVideoThumb($url, $options = null, $extra = null){
   function externalVideoThumb($url, $options = array()){

		// imagen por defecto
		$image_url = '/img/admin/minithumb_video.gif';

		if(eregi("vimeo",$url)){

			// imagen de vimeo
			$video_id = $this->getVimeoID($url);
			$vimeo_url = 'http://vimeo.com/api/v2/video/'.$video_id.'.php';
			$contents = @file_get_contents($vimeo_url);
			$array = @unserialize(trim($contents));
			$image_url = $array[0]['thumbnail_large'];
			/*
			$vimeo_url = 'http://vimeo.com/api/clip/'.$video_id.'/php';die($vimeo_url);
			$contents = @file_get_contents($vimeo_url);
			$array = @unserialize(trim($contents));
			$image_url = $array[0]['user_thumbnail_large'];
			*/

		} else {

			// imagen de youtube
			$video_id = $this->getYouTubeID($url);
			$image_url = 'http://img.youtube.com/vi/'.$video_id.'/default.jpg';

		}

        //return $this->Html->image($image_url, $options, $extra);
        return $this->Html->image($image_url, $options);

    }

	/*
	 * Retrieve the video ID from a YouTube video URL
	 * @param $ytURL The full YouTube URL from which the ID will be extracted
	 * @return $ytvID The YouTube video ID string
	 */
	function getYouTubeID($ytURL) {

		$ytvIDlen = 11;	// This is the length of YouTube's video IDs

		// The ID string starts after "v=", which is usually right after
		// "youtube.com/watch?" in the URL
		$idStarts = strpos($ytURL, "?v=");

		// In case the "v=" is NOT right after the "?" (not likely, but I like to keep my
		// bases covered), it will be after an "&":
		if($idStarts === FALSE)
			$idStarts = strpos($ytURL, "&v=");
		// If still FALSE, URL doesn't have a vid ID
		//if($idStarts === FALSE)
			//die("YouTube video ID not found en ".$ytURL.".");

		// Offset the start location to match the beginning of the ID string
		$idStarts +=3;

		// Get the ID string and return it
		$ytvID = substr($ytURL, $idStarts, $ytvIDlen);

		return $ytvID;

	}

	/*
	 * Retrieve the video ID from a Vimeo video URL
	 * @param $vURL The full YouTube URL from which the ID will be extracted
	 * @return $vID The YouTube video ID string
	 */
	function getVimeoID($URL) {

		$IDlen = 8;	// This is the length of Vimeo's video IDs

		// The ID string starts after "v=", which is usually right after
		// "vimeo.com/" in the URL
		$idStarts = strpos($URL, "om/");

		// If still FALSE, URL doesn't have a vid ID
		//if($idStarts === FALSE)
			//die("Vimeo video ID not found.");

		// Offset the start location to match the beginning of the ID string
		$idStarts +=3;

		// Get the ID string and return it
		$ID = substr($URL, $idStarts, $IDlen);

		return $ID;

	}

	// Muestra un check o una cruz para visualizar el estado de un campo
	//
	// opciones:
	//   showCross: [default:true] Indica si se debe mostrar la cruz en el estado inactivo
	//   activeURL: [default:false] Si la imagen activa debe tener un link se indica aquí
	//   inactiveURL: [default:false] Si la imagen inactiva debe tener un link se indica aquí
	//	 checkTitle: [default:false] Texto a mostrar en el atributo 'title' de la imagen en el caso de estar chequeado
	//	 crossTitle: [default:false] Texto a mostrar en el atributo 'title' de la imagen en el caso de mostrar la cruz
	//
	// ej: echo $envero->graphicStatus( $item['Gallery']['active'], array( 'activeURL' => '/admin/galleries/changestatus/1', 'inactiveURL' => '/admin/galleries/changestatus/0' ) );
	//
	function graphicStatus( $value, $options = null ) {

		$output = '';

		// chequea en las opciones si tiene que mostrarse la cruz o no
		if ( isset( $options['showCross'] ) && $options['showCross'] == false ) : $showCross = false; else : $showCross = true; endif;
		if ( isset( $options['checkTitle'] ) && $options['checkTitle'] == false ) : $checkTitle = false; else : $checkTitle = $options['checkTitle']; endif;
		if ( isset( $options['crossTitle'] ) && $options['crossTitle'] == false ) : $crossTitle = false; else : $crossTitle = $options['crossTitle']; endif;

		if ( $value ) :

			// chequea si se debe presentar el check on un link
			if ( !empty( $options['activeURL'] ) ) :
				$output = $this->Html->link( $this->Html->image('admin/active.png'), $options['activeURL'], array('class' => 'iconLink', 'title' => $checkTitle), false, false );
			else :
				$output = $this->Html->image('admin/active.png', array('title' => $checkTitle) );
			endif;

		else :

			if ( $showCross ) :
				if ( !empty( $options['inactiveURL'] ) ) :
					$output = $this->Html->link( $this->Html->image('admin/inactive.png'), $options['inactiveURL'], array('class' => 'iconLink', 'title' => $crossTitle), false, false );
				else :
					$output = $this->Html->image('admin/inactive.png', array('title' => $crossTitle) );
				endif;
			endif;

		endif;

		return $this->output( $output );

	}

	// chequea si existe el contenido en el idioma actual sino lo muestra en el idioma por defecto
	function getLangContent( $item = '', $model = '', $field = '' ){
		if(!empty($model)){
			if(!empty($item[$model][$field.'_'.$this->Session->read('Config.language') ])){
				return $item[$model][$field.'_'.$this->Session->read('Config.language')];
			} else {
				return $item[$model][$field.'_'.$this->Session->read('Config.language_default')];
			}
		} else {
			if(!empty($item[$field.'_'.$this->Session->read('Config.language') ])){
				return $item[$field.'_'.$this->Session->read('Config.language')];
			} else {
				return $item[$field.'_'.$this->Session->read('Config.language_default')];
			}
		}
	}

}
?>
