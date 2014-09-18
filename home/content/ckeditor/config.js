/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
        
       
};

CKEDITOR.editorConfig = function(config) {
   // Aqui se añade la configuración del kcfinder con la cual se habilita para subir imagenes 
   config.filebrowserBrowseUrl = '/home/content/ckeditor/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/home/content/ckeditor/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/home/content/ckeditor/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/home/content/ckeditor/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/home/content/ckeditor/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/home/content/ckeditor/kcfinder/upload.php?type=flash';
   config.entities = false;
   // Objetos para agregar y quitar herramientas del editor
   config.extraPlugins = 'font,justify,imageresize';
   config.removePlugins = 'about,others';
};

