<?php
echo $this->Html->scriptBlock("

	var editor_id = 1;
	CKEDITOR.stylesSet.add( 'my_styles', [
    		// Block-level styles
    		{ name: 'Título Negro', element: 'span', attributes: { class:'TX-titulo' } },
    		{ name: 'Bajada Gris', element: 'span', attributes: { class:'TX-bajada' } },
    		{ name: 'Título Rojo', element: 'span', attributes: { class:'TX-tit-tex' } }
		]);

	$('.editor').each(function(){
		$(this).attr('id', 'editor_' + editor_id);

		

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
				[ 'Maximize' ],
				[ 'Styles' ]
			],
			toolbar: 'Custom',
			uiColor: '#eeeeee',
			width:462,
			height:250,
			resize_enabled:true,
			stylesSet: 'my_styles',
			contentsCss: '".Router::url('/css/main.css', true)."'
		});

		

		editor_id++;
	});

");
?>
