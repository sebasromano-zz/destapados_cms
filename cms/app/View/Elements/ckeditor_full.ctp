<?php
echo $this->Html->scriptBlock("

	var editor_id = 1;
	$('.editor').each(function(){
		$(this).attr('id', 'editor_' + editor_id);

		//CKEDITOR.config.extraPlugins = 'addimg';    // agrego el plugin
		CKEDITOR.replace('editor_' + editor_id, {
			toolbar_Custom: [
				[ 'Bold', 'Italic', 'Underline' ],
				[ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				[ 'NumberedList','BulletedList','-','Blockquote'],
				[ 'Link','Unlink' ],
				[ 'TextColor' ],
				[ 'FontSize' ],
				[ 'PasteText', 'PasteFromWord' ],
				[ 'RemoveFormat' ],
				[ 'Maximize' ]
				//[ 'AddImg' ],
			],
			toolbar: 'Custom',
			uiColor: '#eeeeee',
			width: 800,
			height: 450,
			resize_enabled:true
		});

		editor_id++;
	});

");