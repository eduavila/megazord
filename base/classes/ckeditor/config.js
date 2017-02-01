/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	//config.language = 'pt-br';
	//config.uiColor = '#AADC6E';
	config.toolbar = 'Full';
	config.toolbar_Full =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar','PageBreak' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		
		//'/',
		{ name: 'styles', items : [ 'Styles','Format', 'FontSize'  ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'tools', items : [ 'Maximize' ] }
	];
	config.extraAllowedContent = 'span(*)';
	config.language = 'pt-br';
	config.scayt_sLang = 'pt_BR';
	config.disableNativeSpellChecker = false;
	config.scayt_autoStartup = true;
	config.pasteFromWordPromptCleanup = false;
	config.pasteFromWordRemoveFontStyles = true;
	config.pasteFromWordRemoveStyles = true;
};
