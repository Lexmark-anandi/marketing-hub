/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = Cookies.getJSON('activesettings').systemLanguage;
//    config.height = '500px';
	config.format_tags = 'p;h1;h2;h3';
	//config.removePlugins = 'magicline,liststyle,tabletools,scayt,contextmenu';
	config.removePlugins = 'magicline,elementspath';
    config.image_previewText = ' '; 
	config.fontSize_sizes = 	'6pt/6pt;7pt/7pt;8pt/8pt;9pt/9pt;10pt/10pt;11pt/11pt;12pt/12pt;14pt/14pt;16pt/16pt;18pt/18pt;20pt/20pt;22pt/22pt;24pt/24pt;26pt/26pt;28pt/28pt;36pt/36pt;48pt/48pt;72pt/72pt;80pt/80pt;90pt/90pt;100pt/100pt;110pt/110pt;120pt/120pt;130pt/130pt;140pt/140pt;150pt/150pt;160pt/160pt;170pt/170pt;180pt/180pt;190pt/190pt;210pt/210pt;220pt/220pt;230pt/230pt;240pt/240pt;250pt/250pt;260pt/260pt;270pt/270pt;280pt/280pt;290pt/290pt;300pt/300pt;';
	config.colorButton_colors =	
		'000000,32323C,5A5A64,A5A5AA,C9C9d1,E6E6F0,EFF0F6,FFFFFF,' +
		'006446,008945,00AD21,00C425,3AF23A,' +
		'1C64B4,FAA519';
	config.colorButton_enableAutomatic = false;
	config.colorButton_enableMore = false;
	
	config.enterMode = 2; // 1 = p / 2 = br

    config.toolbar_SYS =  
    [
            { name: 'styles', items : [ 'Format', 'Styles', '-', 'Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight', '-', 'Subscript','Superscript', '-', 'NumberedList','BulletedList'] },
            '/',
            { name: 'editing', items : [ 'Outdent','Indent','-','Undo','Redo', '-', 'Find','-','Templates', '-', 'Link','Anchor', '-', 'Image','Table','CreateDiv','HorizontalRule','SpecialChar','-', 'Source', '-', 'Maximize', 'ShowBlocks' ] }
    ];	

    config.toolbar_SYS_SMALL =
    [
            { name: 'styles', items : [ 'Format', 'Styles', '-', 'Bold','Italic','Underline', '-', 'Templates'] },
            '/',
            { name: 'editing', items : [ 'NumberedList','BulletedList', '-', 'Link', 'Image','Table','CreateDiv','HorizontalRule','SpecialChar', 'Source' ] }
    ];	

    config.toolbar_SYS_MINIMAL =
    [
            { name: 'styles', items : [ 'Format', 'Styles', '-', 'Bold','Italic','Underline','Subscript','Superscript', '-', 'Link', 'Unlink', 'NumberedList','BulletedList','Outdent','Indent'] }
    ];	

    config.toolbar_SYS_MIN =
    [
            { name: 'styles', items : [ 'Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight', 'NumberedList','BulletedList', '-', 'FontSize', 'TextColor' ] }
    ];	

	config.contentsCss = '/mh/admin/css-ckeditor.css';
//	config.stylesSet = 'sys:../../../../site/include/cke_styles.js';

////    config.filebrowserImageBrowseUrl = '../mai/system/functions/system/fu-filebrowser.php';	
//	config.filebrowserFlashBrowseUrl = '../mai/system/functions/system/fu-filebrowser.php';	
////    config.filebrowserLinkBrowseUrl = '../mai/system/functions/system/fu-filebrowser.php';

////    if(ckEditorTemplates != '') {
////        if(ckEditorTemplates.substring(0, 1) == '/') ckEditorTemplates = '..'+ckEditorTemplates;
////        else ckEditorTemplates = '../' + ckEditorTemplates;
////        config.templates_files = [ ckEditorTemplates ];
////    }
////    else config.templates_files = [];
////    
////    
////    if(ckEditorStyles != '') {
////        if(ckEditorStyles.substring(0, 1) == '/') ckEditorStyles = '..'+ckEditorStyles;
////        else ckEditorStyles = '../' + ckEditorStyles;
////        
////        $.ajax({
////            url: ckEditorStyles,
////            success: function() {
////                config.stylesSet = 'standard';
////            }
////        });
////    }
};
