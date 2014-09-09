
$(document).ready( function() {

	update_iframe_size = function ( iframe_class ){

		iframeElement = $('iframe.'+iframe_class);

		iframe = iframeElement[0];
		iframeWindow = iframe.contentWindow? iframe.contentWindow : iframe.contentDocument.defaultView; //console.log(iframewindow);

		iframeElement.ready(function(){
			iframeElement.css({'height': (iframeWindow.$('.wrapperContent').height()+50 )+'px'});
		})

	}

	select_to_checkboxes = function (){

		$('.to-checkbox').each(function(){

			var element = $(this);
			var name = $(this).attr('name');
			$(this).removeClass('to-checkbox');

			var content = [];
			content[0] = '<div class="select-to-checkbox ' + $(this).attr('class') + '">';

			var i = 1;
			$('option', this).each(function(){

				if(element.hasClass('to-tree')){
					var marginLeft = (($(this).html().length - $(this).html().replace(/→/g, '').length)*20);
				}
			
				content[i++] = '<div class="select-to-checkbox-item clearfix" '+(marginLeft != undefined ? 'style="margin-left:'+marginLeft+'px;"' : '')+' >';
				content[i++] = '<div class="select-to-checkbox-field">';

				if($(this).attr('selected')) content[i++] = '<input type="checkbox" value="' + $(this).attr('value') + '" name="' + name + '" checked="checked" />';
				else content[i++] = '<input type="checkbox" value="' + $(this).attr('value') + '" name="' + name + '" />';

				content[i++] = '</div>';
				content[i++] = '<div class="select-to-checkbox-label">';
				content[i++] = element.hasClass('to-tree') ? $(this).html().replace(/→/g, '') : $(this).html();
				content[i++] = '</div>';
				content[i++] = '</div>';

			});
			
			content[i] = '</div>';

			$(this).parent().html(content.join(''));

		});

	}

	bind_fancybox = function(){

		$('.fancybox').each(function(){

			var element = $(this);

			element.click(function(){
				top.$.fancybox({
					href: element.attr('href')
				})
				return false;
			})

		});

		$('.fancybox_video').each(function(){

			var element = $(this);

			element.click(function(){
				top.$.fancybox({
					href: element.attr('href'),
					type: 'iframe',
					maxWidth: 640,
					maxHeight: 480,
					fitToView	: false,
					width: '70%',
					height: '70%',
					autoSize: false,
					closeClick: false,
					openEffect: 'none',
					closeEffect: 'none'
				})
				return false;
			})

		});

	}

	bind_modal_links = function(){

		$('.modalLink').live('click',function(){

			var element = $(this);
			var modalData = $.parseJSON( element.attr('rel').replace(/'/g, '"') );
			var modalUrl = element.attr('href');
			if(modalData.addUrl != undefined){
				var addUrl = modalData.addUrl.split(':');
				modalUrl = modalUrl+'/'+addUrl[0]+':'+$(addUrl[1]).val();
			}
			if(modalData.actions != undefined){
				var modalActions = modalData.actions;
			} else {
				var modalActions = 'default';
			}

			$('#admin-modal h3').html( modalData.title );

			$('#modal-content').addClass('loading');
			$('#modal-content').html('');
			$('#admin-modal').modal({ keyboard: false });

			switch(modalData.type){
				case 'ajax':
					$.ajax({
						url: modalUrl,
						success:function( response ){
							$('#modal-content').removeClass('loading');
							var modalContent = jQuery( response );
							$('#modal-content').html( modalContent );
							$('#admin-modal .btn-primary').click(function(){
								modalContent.find('form').submit();
								return false;
							});
						}
					});
				break;
				case 'iframe':
				default:
					var modal_iframe = jQuery('<iframe id="modal_iframe" style="border:0;width:'+modalData.width+'px;height:'+modalData.height+'px;" src="'+modalUrl+'" scrolling="auto" border="0" />');
					$('#modal-content').removeClass('loading');
					$('#modal-content').html( modal_iframe );

					$('#admin-modal .modal-actions').hide();
					$('#admin-modal .modal-actions-'+modalActions).removeClass('hidden');
					$('#admin-modal .modal-actions-'+modalActions).show();

					$('#admin-modal .btn-submit').unbind('click');
					$('#admin-modal .btn-submit').click(function(){
						$('#modal_iframe').contents().find('form').submit();
						return false;
					});
			}

			return false;
		});

	}

	bind_events = function(){
		select_to_checkboxes();
		bind_modal_links();
		bind_fancybox();
	}

	checkboxes_actions = function ( controller ){

		// checkboxes
		var checks = 0;
		var checks_selected = 0;
		$('.check').each(function(){
			checks += 1;
		});

		$('.allCheckBox').click(function(){
			$('.check').attr('checked', $(this).is(':checked'))
			checks_selected = 1;
		});

		$('.check').click(function(){
			checkboxes_select()
		});

		checkboxes_select = function(){

			checks_selected = 0;
			$('.allCheckBox').attr('checked', false);
			$('.check').each(function(i){
				if($(this).is(':checked')){
					checks_selected += 1;
				}
			});
			if(checks_selected != 0 && checks_selected == checks){
				$('.allCheckBox').attr('checked', true);
			}

		}

		checkboxes_select();

		$('.to_action').click(function(){
			var href_action = $(this).attr('href');
			var rel_action = $(this).attr('rel');
			if(rel_action == 'delete_single') checks_selected = 1;
			if(checks_selected != 0){
				switch(rel_action){
					case 'delete':
						modal_title = '¿Seguro que desea eliminar?';
						modal_content = 'Esta acción eliminará los elementos seleccionados y no se puede deshacer.';
						modal_html = '<div class="modal fade" id="modal"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3>'+modal_title+'</h3></div><div class="modal-body"><p>'+modal_content+'</p></div><div class="modal-footer"><a data-dismiss="modal" href="#" class="btn">Cancelar</a><a href="#" onclick="$(modal_html).modal(\'hide\'); $(\'#IndexForm\').attr({action: \'/cms/admin/'+controller+'/delete\'}); $(\'#IndexForm\').submit(); return false;" class="btn btn-primary">Aceptar</a></div></div>';
						$(modal_html).modal();
					break;
					case 'delete_single':
						modal_title = '¿Seguro que desea eliminar?';
						modal_content = 'Esta acción eliminará los elementos seleccionados y no se puede deshacer.';
						modal_html = '<div class="modal fade" id="modal"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3>'+modal_title+'</h3></div><div class="modal-body"><p>'+modal_content+'</p></div><div class="modal-footer"><a data-dismiss="modal" href="#" class="btn">Cancelar</a><a href="#" onclick="$(modal_html).modal(\'hide\'); window.location.href = \''+href_action+'\'; return false;" class="btn btn-primary">Aceptar</a></div></div>';
						$(modal_html).modal();
					break;
					default:
						$('#IndexForm').attr({
							action: "/cms/admin/"+controller+"/"+rel_action
						});
						$('#IndexForm').submit();
				}
			} else {
				modal_title = 'Debe seleccionar un elemento';
				modal_content = 'Para realizar la acción solicitada debe tener al menos un elemento seleccionado.';
				modal_html = '<div class="modal fade" id="modal"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3>'+modal_title+'</h3></div><div class="modal-body"><p>'+modal_content+'</p></div><div class="modal-footer"><a data-dismiss="modal" href="#" class="btn btn-primary">Aceptar</a></div></div>';
				$(modal_html).modal();
			}
			return false;
		});

	}

	loader = function(element_id, loader_id) {
		
		var element = $(element_id);
		element.css('opacity','.3');

		var loader = $('<div id="' + loader_id + '" class="ajaxLoader"></div>');
		loader.css({
			'top': element.offset().top,
			'left': element.offset().left,
			'width': element.width(),
			'height': element.height()
		});
		loader.data('element_id', element_id);
		$(loader).appendTo('body');

	}

	loaderDelete = function( id ) {
		var loader = $('#'+ id);
		$(loader.data('element_id')).css('opacity','1');
		loader.remove();
	}

	$('.show_modal').click(function(){
		var href_action = $(this).attr('href');
		var rel_action = $(this).attr('rel');
		switch(rel_action){
			case 'delete':
				modal_title = '¿Seguro que desea eliminar?';
				modal_content = 'Esta acción eliminará los elementos seleccionados y no se puede deshacer.';
				modal_html = '<div class="modal fade" id="modal"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3>'+modal_title+'</h3></div><div class="modal-body"><p>'+modal_content+'</p></div><div class="modal-footer"><a data-dismiss="modal" href="#" class="btn">Cancelar</a><a href="#" onclick="$(modal_html).modal(\'hide\'); $(\'#IndexForm\').attr({action: \'/admin/'+controller+'/delete\'}); $(\'#IndexForm\').submit(); return false;" class="btn btn-primary">Aceptar</a></div></div>';
				$(modal_html).modal();
			break;
			case 'delete_single':
				modal_title = '¿Seguro que desea eliminar?';
				modal_content = 'Esta acción no se puede deshacer.';
				modal_html = '<div class="modal fade" id="modal"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3>'+modal_title+'</h3></div><div class="modal-body"><p>'+modal_content+'</p></div><div class="modal-footer"><a data-dismiss="modal" href="#" class="btn">Cancelar</a><a href="#" onclick="$(modal_html).modal(\'hide\'); window.location.href = \''+href_action+'\'; return false;" class="btn btn-primary">Aceptar</a></div></div>';
				$(modal_html).modal();
			break;
		}
		return false;
	});

	$('a[data-toggle="tab"]').on('shown', function (e) {

		current_tab = $(e.target);
		previous_tab = $(e.relatedTarget);

		tab_id = current_tab.attr('href').substr( current_tab.attr('href').lastIndexOf( '_' ) + 1 );
		selected_tab = $('#tab-pane_' + tab_id);

		// chequeo si la pestaña tiene un mapa y tiene que centrarlo en una dirección
		/*if(selected_tab.hasClass('updateMapWithAddress')){
			updateMapWithAddress();
		}*/

		// chequeo si la pestaña tiene un mapa
		if ( selected_tab.hasClass('hasMap') ) {
			iframeElement = $('iframe', selected_tab);

			iframe = iframeElement[0];
			iframeWindow= iframe.contentWindow? iframe.contentWindow : iframe.contentDocument.defaultView; //console.log(iframewindow);

			iframeWindow.google.maps.event.addListenerOnce( iframeWindow.map, 'tilesloaded', function() {
				var interval = setInterval(function(){
					if(iframeWindow.mapLoaded){
						clearInterval(interval);
						iframeWindow.map.setCenter( iframeWindow.pointCenter );
					}
				}, 500);

			} );

		}

	});

	if( $('.tab-content').size() ){
		$('.tab-pane').each(function(){
			var count = $('.error-message', $(this) ).size();
			if( count ){
				var id_tab = $(this).attr('id').split('_');
				$('#tab_' + id_tab[1]).addClass('error');
				$('#tab_' + id_tab[1] + ' a').append(' <span class="badge badge-important">'+count+'</span>');
			}
		});
	}

	$('.ajaxLink').live('click',function(event){
		var linkElement = $(this);
		loader(linkElement.attr('rel'), 'loader_'+$(linkElement.attr('rel')).attr('id'));
		$.ajax({
			data:null,
			method:'post',
			success:function (data, textStatus) {
				loaderDelete('loader_'+$(linkElement.attr('rel')).attr('id'));
				$(linkElement.attr('rel')).html(data);
			},
			url:linkElement.attr('href')
		});
		return false;
	});

	//$('.custom-file').filestyle({ imagewidth : 74, field_text: '' });

	$('.titles a[data-toggle="tab"]').on('shown', function (e) {
		var tabCurrent = e.target
		var tabPrevious = e.relatedTarget
		$('.titles a[data-toggle="tab"]').removeClass('active');
		$(tabCurrent).addClass('active');
	})

	bind_events();

});
