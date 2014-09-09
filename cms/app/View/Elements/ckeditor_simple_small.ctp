<?php
echo $this->Html->scriptBlock("

	var editor_id = 1;
	$('.editor').each(function(){
		$(this).attr('id', 'editor_' + editor_id);

		CKEDITOR.replace('editor_' + editor_id, {
			toolbar_Custom: [
				[ 'Bold', 'Italic' ],
				[ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				[ 'NumberedList','BulletedList'],
				[ 'Link','Unlink' ],
				[ 'PasteFromWord' ],
				[ 'RemoveFormat' ],
			],
			toolbar: 'Custom',
			uiColor: '#eeeeee',
			width:462,
			height:120,
			resize_enabled:true
		});

		editor_id++;
	});

");
?>
