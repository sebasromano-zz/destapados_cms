<?php
 
/*
 
 * CakeMap -- a google maps integrated application built on CakePHP framework.
 * Based on initial versione from Garrett J. Woodworth : gwoo@rd11.com
 * Rewritten by  : http://www.small-software-utilities.com

 * @author      info@small-software-utilities.com
 * @version     0.2
 * @license     OPPL
 
 * Modified by     Mahmoud Lababidi <lababidi@bearsontherun.com>
 * Date        Dec 16, 2006
 * Rewritten by small software utilities <info @small-software-utilities.com>
 * Date        May, 2010
 
*/
 
class GoogleMapHelper extends Helper {
 
	var $errors = array();
	var $key = "";
	var $url ="http://maps.google.com/maps/api/js?sensor=false";

	function init(){

		$out = "
		<script type=\"text/javascript\">
		var GMapInterval = setInterval(function(){
			if(mapLoaded){
				clearInterval(GMapInterval);
				var allFunctions = functions.split(',');
				jQuery.each(allFunctions, function(functionKey, functionName) {
					if(functionName.length) setTimeout(functionName+'()', 100);
				});
				//map.setCenter( pointCenter );
			}
		}, 200);

		</script>";
		return $out;

	}

	// http://www.google.com/mapfiles/markerA.png

	function map($default, $style = 'width: 400px; height: 400px', $icon = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png' ){

		if (empty($default)){return "error: You have not specified an address to map"; exit();}

		$out = "<div id=\"map\"";
		$out .= isset($style) ? "style=\"".$style."\"" : null;
		$out .= " ></div>";
		$out .= "
		<script type=\"text/javascript\">
		//< ![CDATA[
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
		var geocoder = new google.maps.Geocoder;
		var map;
		var pointCenter;
		var functions = \"\";
		var iconimage = \"".$icon."\";

		//var iconshadow = \"http://labs.google.com/ridefinder/images/mm_20_shadow.png\";
		var iconshadow = \"\";

		var mapLoaded = false;
		var marker0Loaded = false;

		";

		if(!empty($default['lat']) && !empty($default['long'])){

		$out .= "
		var myOptions = {
			zoom: ".((!empty($default['zoom']))?$default['zoom']:10).",
			center: new google.maps.LatLng(" . $default['lat'] . ", " . $default['long'] . "),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl: true
		};
		map = new google.maps.Map(document.getElementById(\"map\"), myOptions);
		pointCenter = new google.maps.LatLng(" . $default['lat'] . ", " . $default['long'] . ");
		mapLoaded = true;
		";

		} elseif(!empty($default['address'])) {

		$out .= "
		geocoder.geocode({'address': '".$default['address']."'}, function(results, status){
			//console.log('Status: '+ status);
			if (status == google.maps.GeocoderStatus.OK){
				var myOptions = {
					zoom: ".$default['zoom'].",
					center: results[0].geometry.location,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					streetViewControl: true
				};
				map = new google.maps.Map(document.getElementById(\"map\"), myOptions);
				pointCenter = results[0].geometry.location;
				mapLoaded = true;
			}
		});
		";

		}

		$out .="

		function cleardirs(){
			if(directionsDisplay) directionsDisplay.setMap(null);
			div = document.getElementById('".(isset($default['directions_div'])?$default['directions_div']:'directions_div')."');
			if(div) div.innerHTML = \"\";
		}
		";

		if(isset($default['directions_div'])){

			$out .= "
			directionsDisplay = new google.maps.DirectionsRenderer();
			directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById('".$default['directions_div']."'));

			function calcRoute(fromid,tolat,tolon) {
				directionsDisplay.setMap(map);
				from = document.getElementById(fromid).value;
				var start = from;
				var end = new google.maps.LatLng(tolat,tolon);
				var request = {
					origin:start,
					destination:end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};

				directionsService.route(request, function(result, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(result);
					}
				});
			}
			";

		}

		$out .="
		//]]>
		</script>";

		return $out;

	}

	function getroute($default, $style = 'width: 400px; height: 400px' ){

		$out = "<div id=\"map\"";
		$out .= isset($style) ? "style=\"".$style."\"" : null;
		$out .= " ></div>";
		$out .= "
		<script type=\"text/javascript\">
		//< ![CDATA[
		var directionDisplay;
		var directionsService = new google.maps.DirectionsService();
		var map;
		var iconimage = \"http://labs.google.com/ridefinder/images/mm_20_red.png\";
		var iconshadow = \"http://labs.google.com/ridefinder/images/mm_20_shadow.png\";
		var myOptions = {
			zoom: ".$default['zoom'].",
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: new google.maps.LatLng(".$default['lat'].", ".$default['long'].")
		};

		var map = new google.maps.Map(document.getElementById(\"map\"), myOptions);
		";

		if(isset($default['origin']) && isset($default['destination'])){
	
			$out .= "
			directionsDisplay = new google.maps.DirectionsRenderer();
			directionsDisplay.setMap(map);

			directionsDisplay.setMap(map);

			var start = '".$default['origin']."';
			var end = '".$default['destination']."';

			var request = {
				origin:start,
				destination:end,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};

			directionsService.route(request, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(result);
				}
			});
			";

		}

		$out .="
		//]]>
		</script>";

		return $out;

	}


	function addMarkers(&$data, $options=null) {

		$out = "
		<script type=\"text/javascript\">
		";

		if(is_array($data)){
			$i = 0;
			foreach($data as $n => $m){

				$keys = array_keys($m);
				$point = $m[$keys[0]];

				if(!preg_match('/[^0-9\\.\\-]+/',$point['long']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['long']) && !preg_match('/[^0-9\\.\\-]+/',$point['lat']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['lat'])) {
					$out .= "
					var point".$i.";
					var marker".$i.";
					";
					$i++;
				} elseif(!empty($point['address'])){
					$out .= "
					var point".$i.";
					var marker".$i.";
					";
					$i++;
				}

			}
		}

		$out .= "
		function GMapAddMarkers(){
		//< ![CDATA[
		";

		if(is_array($data)){

			$i = 0;

			foreach($data as $n => $m){

				$keys = array_keys($m);
				$point = $m[$keys[0]];

				if(!preg_match('/[^0-9\\.\\-]+/',$point['long']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['long']) && !preg_match('/[^0-9\\.\\-]+/',$point['lat']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['lat'])) {

					$out .= "
					point".$i." = new google.maps.LatLng(".$point['lat'].",".$point['long'].");
					marker".$i." = new google.maps.Marker({
						position: point".$i.",
						zoom: ".((!empty($point['zoom']))?$point['zoom']:10).",
						map: map,
						title:\"".(isset($point['title'])?$point['title']:'')."\",
						shadow: iconshadow,
						icon: iconimage
					});
					marker0Loaded = true;
					";

					if(isset($point['html'])){

						$out .= " var infowindow$i = new google.maps.InfoWindow({
							content: \"$point[html]\"
						});
						google.maps.event.addListener(marker".$i.", 'click', function() {
							infowindow$i.open(map,marker".$i.");
						});
						";

					}

					if($options['iconHover']){

						$out .= "
						var jQuery, $; $ = window.parent.$; jQuery = window.parent.jQuery;

						google.maps.event.addListener(marker".$i.", 'mouseover', function() {
							this.setIcon('".$options['iconHover']."');
							$('#ad_".$point['ad_id']."').trigger('mouseenter');
						});

						google.maps.event.addListener(marker".$i.", 'mouseout', function() {
							this.setIcon('".$options['icon']."');
							$('#ad_".$point['ad_id']."').trigger('mouseleave');
						});

						";

					}

					$data[$n][$keys[0]]['js']="marker$i.openInfoWindowHtml(marker$i.html);";

					$i++;

				} elseif(!empty($point['address'])){

					$out .= "
					geocoder.geocode({'address': '".$point['address']."'}, function(results, status){
					if (status == google.maps.GeocoderStatus.OK){

						point".$i." = results[0].geometry.location;
						marker".$i." = new google.maps.Marker({
							position: point".$i.",
							zoom: ".((!empty($point['zoom']))?$point['zoom']:10).",
							map: map,
							title:\"".(isset($point['title'])?$point['title']:'')."\",
							shadow: iconshadow,
							icon: iconimage
						});

						marker0Loaded = true;

					";

					if(isset($point['html'])){

						$out .= "var infowindow$i = new google.maps.InfoWindow({
							content: \"$point[html]\"
						});
						google.maps.event.addListener(marker".$i.", 'click', function() {
							infowindow$i.open(map,marker".$i.");
						});";

					}

					if($options['iconHover']){

						$out .= "

						var jQuery, $; $ = window.parent.$; jQuery = window.parent.jQuery;

						google.maps.event.addListener(marker".$i.", 'mouseover', function() {
							this.setIcon('".$options['iconHover']."');
							$('#ad_".$point['ad_id']."').trigger('mouseenter');
						});

						google.maps.event.addListener(marker".$i.", 'mouseout', function() {
							this.setIcon('".$options['icon']."');
							$('#ad_".$point['ad_id']."').trigger('mouseleave');
						});

						";

					}


					$out .= "
					}});
					";

					$data[$n][$keys[0]]['js']="marker$i.openInfoWindowHtml(marker$i.html);";

					$i++;

				} //*/

			}

			$out .= "
			var LatLngList = new Array(";
			$j_count = 0;
			for($j=0;$j<$i;$j++):
				$out .= "point".$j;
				if($j<$i-1) $out .= ",";
				else $out .= ");";
				$j_count++;
			endfor;

			if($j_count == 1){

				$out .= "
				/*
				if(point0 != undefined){
					map.setCenter(point0);
					map.setZoom(15);
				}
				*/
				";

			} elseif($j_count > 1){

				$out .= "
				var bounds = new google.maps.LatLngBounds();
				for (var h = 0, LtLgLen = LatLngList.length; h < LtLgLen; h++) {
					bounds.extend (LatLngList[h]);
				}
				map.fitBounds (bounds);
				";

			}

		}

		$out .= "
		//]]>
		}
		functions = functions + 'GMapAddMarkers,';
		</script>";

		return $out;

	}

	function addClick($var, $script=null){

		$out = "
		<script type=\"text/javascript\">
		function GMapAddClick(){
		//< ![CDATA[
		$script
		google.maps.event.addListener(map, 'click', ".$var.", true);
		//]]>
		}
		functions = functions + 'GMapAddClick,';
		</script>";

		return $out;

	}

	function addZoom($var, $script=null) {

		$out = "
		<script type=\"text/javascript\">
		function GMapAddZoom(){
		//< ![CDATA[
		$script
		google.maps.event.addListener(map, 'zoom_changed', ".$var.", true);
		//]]>
		}
		functions = functions + 'GMapAddZoom,';
		</script>";

		return $out;

	}

	function addMarkerOnClick($innerHtml = null) {

		$mapClick = '
		var mapClick = function (event) {
			var marker = new google.maps.Marker({
				position:event.latLng,
				icon: iconimage,
				map:map
			});

			var infowindow = new google.maps.InfoWindow({
				content: \"'.$innerHtml.'\"
			});

			google.maps.event.addListener(marker, \'click\', function() {
				infowindow.open(map,marker);
			});
		}
		';

		return $this->addClick('mapClick', $mapClick);

	}

	function moveMarkerOnClick($lngctl, $latctl, $zoomctl = false, $iframe = false, $innerHtml = null) {

		if($iframe){
			$mapClick = '
			var jQuery, $; $ = window.parent.$; jQuery = window.parent.jQuery;
			var mapClick = function (event) {

				marker0.setPosition( event.latLng );
	 
				$(\'#'.$lngctl.'\').val( event.latLng.lng() );
				$(\'#'.$latctl.'\').val( event.latLng.lat() );
				$(\'#'.$zoomctl.'\').val( map.getZoom() );
	 
			}

			var GMapUpdateLatLngFieldsInterval = setInterval(function(){
				if(marker0Loaded){
					clearInterval(GMapUpdateLatLngFieldsInterval);
					$(\'#'.$lngctl.'\').val( point0.lng() );
					$(\'#'.$latctl.'\').val( point0.lat() );
					$(\'#'.$zoomctl.'\').val( map.getZoom() );
				}
			}, 200);
			';
			
		} else {

			$mapClick = '
			var mapClick = function (event) {
				marker0.setPosition(event.latLng);
				lngctl = document.getElementById(\''.$lngctl.'\');
				latctl = document.getElementById(\''.$latctl.'\');
				zoomctl = document.getElementById(\''.$zoomctl.'\');
				if(lngctl) lngctl.value = event.latLng.lng();
				if(latctl) latctl.value = event.latLng.lat();
				if(zoomctl) zoomctl.value = map.getZoom();
			}
			var GMapUpdateLatLngFieldsInterval = setInterval(function(){
				if(marker0Loaded){
					clearInterval(GMapUpdateLatLngFieldsInterval);
					$(\'#'.$lngctl.'\').val( point0.lng() );
					$(\'#'.$latctl.'\').val( point0.lat() );
					$(\'#'.$zoomctl.'\').val( map.getZoom() );
				}
			}, 200);
			';

		}

		return $this->addClick('mapClick', $mapClick);

	}

	function updateZoomOnChange($zoomctl, $iframe = false, $innerHtml = null) { 

		if($iframe){
		
			$mapZoom = '
			var jQuery, $; $ = window.parent.$; jQuery = window.parent.jQuery;
			var mapZoom = function (event) {
				$(\'#'.$zoomctl.'\').val( map.getZoom() );
			}
			';
		
		} else {

			$mapZoom = '
			var mapZoom = function (event) {
				zoomctl = document.getElementById(\''.$zoomctl.'\');
				if(zoomctl) zoomctl.value = map.getZoom();
			}
			';
			
		}

		return $this->addZoom('mapZoom', $mapZoom);

	}

}

?>