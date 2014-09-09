<?php
class UtilHelper extends Helper {

	var $helpers = array('Html','Session');

	function link($title, $url, $options = null, $confirm = null, $scape = true, $prefix = 'http://'){

		if(strpos($url, 'http') === false)return $this->Html->link( $title, $prefix . $url, $options, $confirm, $scape);
		else return $this->Html->link( $title, $url, $options, $confirm, $scape);

	}

	function getYouTubeID($ytURL) {

		$ytvIDlen = 11;
		$idStarts = strpos($ytURL, "?v=");
		if($idStarts === FALSE)
			$idStarts = strpos($ytURL, "&v=");
		$idStarts +=3;
		$ytvID = substr($ytURL, $idStarts, $ytvIDlen);

		return $ytvID;

	}

	function getVimeoID($URL) {

		$IDlen = 8;
		$idStarts = strpos($URL, "om/");
		$idStarts +=3;
		$ID = substr($URL, $idStarts, $IDlen);

		return $ID;

	}

	function externalVideoId($url){

		if(strpos($url, 'youtube') !== FALSE){
			return $this->getYouTubeID($url);
		}elseif(strpos($url, 'vimeo') !== FALSE){
			return $this->getVimeoID($url);
		}

		return;

	}

	function externalVideoThumb($url, $options = null, $extra = null){

		$image_url = '/img/thumb_video.png';

		if(strpos($url, 'youtube') !== FALSE){

			$video_id = $this->getYouTubeID($url);
			$image_url = 'http://img.youtube.com/vi/'.$video_id.'/default.jpg';

		}elseif(strpos($url, 'vimeo') !== FALSE){

			$video_id = $this->getVimeoID($url);
			$vimeo_url = 'http://vimeo.com/api/v2/video/'.$video_id.'.json';
			$contents = @file_get_contents($vimeo_url);
			$array = json_decode($contents);
			$image_url = $array[0]->thumbnail_small; //medium // large

		}

		return $this->Html->image($image_url, $options, $extra);

	}

	function externalVideoPreview($url){

		if(strpos($url, 'youtube') !== FALSE){
			return 'http://youtube.com/embed/'.$this->getYouTubeID($url);
		}elseif(strpos($url, 'vimeo') !== FALSE){
			return 'http://player.vimeo.com/video/'.$this->getVimeoID($url);
		}

		return;

	}

}
?>