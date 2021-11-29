<script src="/admin/app-assets/vendors/js/ui/blockUI.min.js"></script>
<script type="text/javascript">
/*
 * ************************************************************* *
 * Name       : plugins                                          *
 * Date       : March 2012                                       *
 * Owner      : CreativeMilk                                     *
 * Url        : www.creativemilk.net                             *
 * Version    : 1.0                                              *
 * Updated    : --/--/----                                       *
 * Changelog  :                                                  *
 * Developer  : Mark                                             *
 * Dependency : see below                                        *
 * Lib        : see plugin                                       *
 * Licence    : NOT free                                         *
 * This is part of a themeforest file                            *
 * ************************************************************* *
 */

function block() {
	$.blockUI({
		message: '<div id="e-loader-img" style="left: 50%; top: 50%; margin-left: -26px; margin-top: -26px;"><img src="/karma/images/loaders/type2/light/32.gif" alt=""></div>',
		overlayCSS: {
			backgroundColor: '#000',
			opacity: 0.5,
			cursor: 'wait'
		},
		css: {
			border: 0,
			padding: 0,
			backgroundColor: 'transparent'
		}
	});
}

function unblock() {
	$('body').unblock();
}

function $success(msg) {
	$.e_notify.growl({
			title: 'Operação realizada',
			text: msg,
			position: 'right-top',
			delay: 0,
			time: 5000,
			speed: 500,
			effect: 'slidein',
			sticky: false,
			closable: false,
			maxOpen:3,
			className:'growl-success',
			onShow: function(){},
			onHide: function(){}
	});
}

function $error(msg) {
	$.e_notify.growl({
			title: 'Opsss, aconteceu algo',
			text: msg,
			position: 'right-top',
			delay: 0,
			time: 5000,
			speed: 500,
			effect: 'slidein',
			sticky: false,
			closable: false,
			maxOpen:3,
			className:'growl-error',
			onShow: function(){},
			onHide: function(){}
	});


}

function ajaxSend(url, data = '', method = 'GET', timeout = 10000, form = '', enctype = 'multipart/form-data') {

	let $param = {
		type: method,
		timeout: timeout,
		url: url,
		data: data,
	};

	if (method == 'POST') {
		$param.enctype = enctype;
	}
	if (form != '') {
		var data = new FormData(form[0]);
		$param.enctype = enctype;
		$param.processData = false;
		$param.contentType = false;
		$param.data = data;
	}

	var objeto = new Promise(function(resolve, reject) {

		$param.success = function (response) {
				if(response.success==true){
					resolve(response);
				}
				if(response.success==false){
					let message = 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.';
					if(response.message){
						message = response.message;
					}
					if(response.msg){
						message = response.msg;
					}
					response.message = message;
					reject(response);
				}
				resolve(response);
			};
		$param.error =  function(jqXHR, textStatus, errorMessage){
			if (jqXHR.status === 0) {
				reject({'message': 'Sem conexão, verifique sua conexão com a internet.'});
			} else if (jqXHR.status == 404) {
				reject({'message': 'Página não foi encontrada, comunique a equipe de desenvolvimento.'});
			} else if (jqXHR.status == 500) {
				reject({'message': 'Erro interno do servidor, comunique a equipe de desenvolvimento.'});
			} else if (jqXHR.status == 502) {
				reject({'message': 'Bad Gateway, comunique a equipe de desenvolvimento.'});
			} else if (textStatus === 'parsererror') {
				reject({'message': 'Erro ao tratar objeto JSON, comunique a equipe de desenvolvimento.'});
			} else if (textStatus === 'timeout') {
				reject({'message': 'Sua conexão demorou muito a responder, tente novamente!'});
			} else if (textStatus === 'abort') {
				reject({'message': 'Solicitação foi recusada, tente novamente!'});
			} else {
				if(jqXHR.responseJSON){
					if(jqXHR.responseJSON.message){
						reject({'message': jqXHR.responseJSON.message, 'response':jqXHR});
					}
					if(jqXHR.responseJSON.msg){
						reject({'message': jqXHR.responseJSON.msg, 'response':jqXHR});
					}
				}

				reject({'message': 'Erro no processamento de sua solicitação, comunique a equipe de desenvolvimento.', 'response':jqXHR});
			}
		}
		$.ajax($param);
	});

	return objeto;
}

 $(document).ready(function($){

	<?php if (Session::has('success')) { ?>
		$success('<?= Session::get('success') ?>');
	<?php } Session::forget('success'); ?>
	<?php if (Session::has('error')) { ?>
		$error('<?= Session::get('error') ?>');
	<?php } Session::forget('error'); ?>

	// NOTICE:
	// some parts are wrapped inside a if statment, for those who
	// dont know why this is...it is because this if statment will
	// check if the selector is present on the page. Without this check
	// the plugins will run into an error(downside of third party plugins).
	// if($('#selector').length){ // run the code }


	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : nanoGress
	 * Description : Lightweight nano progressbar.
	 * File Name   : nanogress.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	// end can befound in the main.js file at the bottom
	$.nanoGress.start();

	// notification page
	$('#nanogress-body-trigger').click(function(e){
		$.nanoGress.start();
		setTimeout(function(){
			$.nanoGress.end();
		},4000);
        e.preventDefault();
	});
	$('#nanogress-target-trigger').click(function(e){
		$.nanoGress.start({target: $(this)});
		setTimeout(function(){
			$.nanoGress.end();
		},4000);
		e.preventDefault();
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : jQuery layout
	 * Description : jQuery layout UI
	 * File Name   : layout.js
	 * Plugin Url  : http://layout.jquery-dev.net/index.cfm
	 * Updated     : --/--/----
	 * Dependency  : jQuery core/ jQuery UI
	 * Developer   : Richard
	**/

	if($('#jquery-layout-1').length){
		$("#jquery-layout-1").layout({
			applyDefaultStyles: false,
			east__size:"50%",
			onresize_end: function(){
				// need to (re)initialize a plugin(s), do it here
			}
		});
	}

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Mansonry
	 * Description : Masonry layout plugin
	 * File Name   : masonry.pkgd.js
	 * Plugin Url  : http://masonry.desandro.com/
	 * Updated     : --/--/----
	 * Dependency  :
	 * Developer   : Richard
	**/

	$('#mansory-container').masonry({
		columnWidth: 20,
		itemSelector: '.module-placeholder',
		transitionDuration: 0
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : H5F
	 * Description : Crossbrowser support form forms
	 * Url         : https://github.com/ryanseddon/H5F
	 * Version     : 1.0
	 * Updated     :
	 * Dependency  : h5f.js
	 * Developer   : Mark
	**/

	H5F.setup(document.getElementsByName("form"));

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Bootstrap tabs extend
	 * Description : Extend the bootstrap tabs plugin with AJAX and responsive mode
	 * File Name   : bootstraptabsextend.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Mark
	**/

	$('#res-tabs').bootstrapTabsExtend({
		responsive: true,
		responsiveClass: 'ext-tabs-responsive-mode',
		preloadAjax: false,
		onChange:function(ui){
			//ui.item(element)
			//ui.value(string)
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Simple Select
	 * Description : Lightweigth select styling plugin
	 * File Name   : simpleselect.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.shortcut-menu, .simpleselect').simpleSelect({
		icon:'<i class="fa fa-caret-down"></i>',
		onChange:function(ui){
    		//ui.item(element)
       	    //ui.value(string)
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Power Wizard
	 * Description : Step wizard.
	 * File Name   : powerwizard.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.powerwizard').powerWizard({
		containerSelector:'.powerwizard-content',
		stepsSelector:'.powerwizard-step',
		nextButtonSelector:'.pw-next',
		prevButtonSelector:'.pw-prev',
		navButtonSelector:'.pw-nav',
		activeClass:'pw-active',
		disableClass:'pw-disable',
		clickableTitles: true,
		historyTitles: false,
		startAt: 0,
		useAjax:true,
		onNext:function(ui){
			//ui.step(element)
		},
		onPrev:function(ui){
			//ui.step(element)
		},
		onSwitch:function(ui){
			//ui.step(element)
		},
		onSubmit:function(ui){
			//ui.step(element)
			//ui.output(string)
		},
		ajaxSuccess:function(ui){
			//ui.step(element)
		},
		ajaxError:function(ui){
			//ui.step(element)
		},
		onValidate:function(ui){
			//ui.step(element)

		    var minChar     = 2;
			var returnFalse = false;

		    $(ui.step).find('.required').each(function(){
				if($(this).is(':checkbox')){
					if($(this).is(':checked')){
						$(this).parent().removeClass('has-error');
					}else{
						$(this).parent().addClass('has-error');
						returnFalse = true;
					}
				}else{
					if($(this).val().length > minChar){
						$(this).parent().removeClass('has-error');
					}else{
						$(this).parent().addClass('has-error');
						returnFalse = true;
					}
				}
			});
			if(returnFalse === true){
				if(!$('.alert-wrap').length){
					$(ui.step).children('.inner-padding').prepend('<div class="alert-wrap"><div class="alert alert-danger"><strong>Error:</strong> One or more required fields are empty!</div></div>');
				}
				return false;
			}else{
				$(ui.step).find('.alert-wrap').remove();
			}

		}
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : jQuery knob
	 * Description : Circular stats
	 * File Name   : knob.js
	 * Plugin Url  : http://anthonyterrien.com/knob/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Richard
	**/

	$(".knob").knob();

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Holder.js
	 * Description : Image replacement
	 * File Name   : holder.js
	 * Plugin Url  : imsky.github.com/holder/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	Holder.add_theme("karma-grey", {
		background:"#ddd",
		foreground:"#999",
		size:11,
		font: "Arial"
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap select2
	 * Description : Bootstrap select styling and management
	 * File Name   : select2.js
	 * Plugin Url  : ivaynberg.github.com/select2/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$(".select2").select2({
		minimumResultsForSearch: 5
	});
	// used for tags/tokens inside a input
	$(".select-token").select2({
		tags:["User Interface", "jQuery", "Coffeescript", "Testing", "Mobile", "Respnsive"],
		width: 'off'
	});

	/* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap modal
	 * Description : Bootstrap modal
	 * File Name   : bootstrap.js
	 * Plugin Url  : www.getbootstrap.com
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('#modal-update-trigger').click(function(e){
		$('#modal-update').modal();
		e.preventDefault();
	});

     // demos
	$('#modal-trigger-1').click(function(e){
		$('#dialog-demo-1').modal();
		e.preventDefault();
	});

     // media manager
	$('#cmanager-dummy-preview-trigger').click(function(e){
		$('#cmanager-dummy-preview').modal();
		e.preventDefault();
	});

	/* BOOTSTRAP ----------------------------------------------------------- */

	/** NOTICE: not the default bootstrap typeahead **/

	/**
	 * Name        : Bootstrap typeahead
	 * Description : Bootstrap typeahead(better version)
	 * File Name   : typeahead.js
	 * Plugin Url  : http://twitter.github.com/typeahead.js/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Mark
	**/

	var ts = ['test', 'javascript', 'project', 'help','admin','cms','cmr','java','jquery','json', 'html5', 'help','hello','document','adobe','Buy this theme','Karma']

	// Toolbar 1
	$('#typeahead-toolbar-1').typeahead({
		name: 'toolbar',
		local: ts
	});

	// Toolbar 2
	$('#typeahead-toolbar-2').typeahead({
		name: 'toolbar',
		local: ts
	});

	// Main search
	$('#typeahead-sidebar-search').typeahead({
		name: 'sidebar-search',
		prefetch: '/karma/js/plugins/data/typeahead-dummy.json',
		limit: 5,
		template: [
			'<div class="tt-block">',
			'<strong class="tt-name">@{{name}}</strong>',
			'<p class="tt-description">@{{description}}</p>',
			'</div>'
		].join(''),
		engine: Hogan //needed for the templating
		//ttl_ms: 1 // there's a bug right now that prevents it from being set to 0, will be fixed in v0.8.2
	});


	/* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap dropdown
	 * Description : Bootstrap dropdown menu's and buttons
	 * File Name   : bootstrap.js
	 * Plugin Url  : www.getbootstrap.com
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	// notice that the dropdown plugin has been called true the data attributes,
	// but because of the lak of options on the plugin we have to call it again,
	// as we need some other plugins to work with the dropdown plugin,
	// so this is the only solution for now.

	$('.dropdown-toggle').dropdown();

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Tabs
	 * Description : Bootstrap tabs
	 * File Name   : bootstrap.js
	 * Plugin Url  : www.getbootstrap.com
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('.ext-tabs-sidebar a, .ext-tabs-cmanager a, .ext-tabs a, .ext-tabs-vertical a, .ext-dropdown-tabs-ul a, .ext-tabs-vertical a').click(function(e){
		e.preventDefault();
		$(this).tab('show');
	});

	// This is a easy way to transform a button group into tabs.
	$('.ext-tabs-btn-group a').click(function(e){
		e.preventDefault();
		$(this).addClass('active').parent().find('.active').not($(this)).removeClass('active');
		$(this).tab('show');
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : vTicker
	 * Description : A text ticker.
	 * File Name   : vticker.js
	 * Plugin Url  : http://richhollis.github.io/vticker/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	if($('.vticker').length){
    	$('.vticker').vTicker();
	}

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Tablesorter
	 * Description : Table sorting plugin(bootstrap example)
	 * File Name   : tablesorter.js
	 * Plugin Url  : http://tablesorter.com
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Brandon
	**/

	$.extend($.tablesorter.themes.bootstrap, {
		// these classes are added to the table. To see other table classes available,
		// look here: http://twitter.github.com/bootstrap/base-css.html#tables
		table      : 'table table-bordered',
		header     : 'bootstrap-header', // give the header a gradient background
		footerRow  : '',
		footerCells: '',
		icons      : '', // add "fa fa-white" to make them white; this icon class is added to the <i> in the header
		sortNone   : 'fa fa-sort',
		sortAsc    : 'fa fa-sort-up',
		sortDesc   : 'fa fa-sort-down',
		active     : '', // applied when column is sorted
		hover      : '', // use custom css here - bootstrap class may not override it
		filterRow  : '', // filter row class
		even       : '', // odd row zebra striping
		odd        : ''  // even row zebra striping
	});

	// call the tablesorter plugin and apply the uitheme widget
	$('#tablesorting-1').tablesorter({
		theme          : "bootstrap", // this will
		widthFixed     : true,
		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets        : [ "uitheme", "filter", "zebra" ],
		widgetOptions  : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],
			// reset filters button
			filter_reset : ".reset",
			// set the uitheme widget to use the bootstrap theme class names
			// uitheme : "bootstrap"
		}
	}).tablesorterPager({
		// target the pager markup - see the HTML block below
		container  : $(".pager"),
		// target the pager page select dropdown - choose a page
	    cssGoto    : ".pagenum",
		// remove rows from the table to speed up the sort of large tables.
	    // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		removeRows : false,
		// output string - default is '{page}/{totalPages}';
		// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		output     : '{startRow} - {endRow} / {filteredRows} ({totalRows})'
	});

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Tooltip
	 * Description : Bootstrap tooltip
	 * File Name   : bootstrap.js
	 * Plugin Url  : www.getbootstrap.com
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('.tooltip-top, .table-tooltip').tooltip({
		placement: 'top',
		container: 'body'
	});
	$('.tooltip-right').tooltip({
		placement: 'right',
		container: 'body'
	});
	$('.tooltip-bottom').tooltip({
		placement: 'bottom',
		container: 'body'
	});
	$('.tooltip-left').tooltip({
		placement: 'left',
		container: 'body'
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : CKEditor
	 * Description : WYSIWYG editor
	 * File Name   : ckeditor.js
	 * Plugin Url  : http://ckeditor.com/
	 * Updated     : --/--/----
	 * Dependency  :
	 * Developer   : Brandon
	**/

    if($('#editor1').length){
		CKEDITOR.replace( 'editor1', {
			uiColor: '#eeeeee'
		});

		// This code is generally not necessary, but it is here to demonstrate
		// how to customize specific editor instances on the fly. This fits well
		// this demo because we have editable elements (like headers) that
		// require less features.

		// The "instanceCreated" event is fired for every editor instance created.
		CKEDITOR.on( 'instanceCreated', function( event ) {
			var editor = event.editor,
				element = editor.element;

			// Customize editors for headers and tag list.
			// These editors don't need features like smileys, templates, iframes etc.
			if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'taglist' ) {
				// Customize the editor configurations on "configLoaded" event,
				// which is fired after the configuration file loading and
				// execution. This makes it possible to change the
				// configurations before the editor initialization takes place.
				editor.on( 'configLoaded', function() {

					// Remove unnecessary plugins to make the editor simpler.
					editor.config.removePlugins = 'colorbutton,find,flash,font,' +
						'forms,iframe,image,newpage,removeformat,' +
						'smiley,specialchar,stylescombo,templates';

					// Rearrange the layout of the toolbar.
					editor.config.toolbarGroups = [
						{ name: 'editing',		groups: [ 'basicstyles', 'links' ] },
						{ name: 'undo' },
						{ name: 'clipboard',	groups: [ 'selection', 'clipboard' ] },
						{ name: 'about' }
					];
				});
			}
		});
	}

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Main Menu
	 * Description : Main drop down menu located in the sidebar(s)
	 * File Name   : mainmenu.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.sidebar-nav-v1, .sidebar-nav-v2').mainMenu({
		closeClass: 'fa-caret-down',
		openClass: 'fa-caret-left',
		speed: 400
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Easy File Tree
	 * Description : File tree layout
	 * File Name   : easyfiletree.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.easyfiletree').easyFileTree({
		speed: 200
	});

	/* THIRD PARTY  ----------------------------------------------- */

	/**
	 * Name        : Tiny srollbar
	 * Description : Styled scrollbar
	 * File Name   : tinycrollbar.js
	 * Plugin Url  : http://baijs.nl/tinyscrollbar/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	/*
	* Vertical scrollbar
	* We wrap the scrollbars(plugin does not do this)
	*/
	$('.scrollbar-y, .ext-dropdown-chat-window')
	.wrapInner('<div class="overview">')
	.wrapInner('<div class="viewport">')
	.prepend('<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>');
	// run plugin
	if($('.scrollbar-y').length){
		$('.scrollbar-y').tinyscrollbar({
			size:190// container height = 200px, scrollbar offset = 5px
		});
	}
	/*
	* Horizontal scrollbar
	* We wrap the scrollbars(plugin does not do this)
	*/
	$('.scrollbar-x')
	.wrapInner('<div class="overview">')
	.wrapInner('<div class="viewport">')
	.append('<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>');
	// run plugin
	if($('.scrollbar-x').length){
		$('.scrollbar-x').tinyscrollbar({
			axis: 'x'
		});
	}
	// main chat
	if($('#ext-dropdown-chat').length){
		$('#ext-dropdown-chat .btn').click(function(e){
			$(this).parents('.dropup, .dropdown').find('.ext-dropdown-chat-window').tinyscrollbar({size:210});
		});
	}
	// demo chat
	if($('#ext-dropdown-chat-1, #ext-dropdown-chat-2, #ext-dropdown-chat-3, #ext-dropdown-chat-4, #ext-dropdown-chat-5, #ext-dropdown-chat-6').length){
		$('.ext-dropdown-chat-btn').click(function(e){
			$(this).parents('.dropup, .dropdown').find('.ext-dropdown-chat-window').tinyscrollbar({size:210});
		});
	}
	// dropdown/dropup scrollbar demo's
	$('#trigger-scrollbar-1,#trigger-scrollbar-2,#trigger-scrollbar-3,#trigger-scrollbar-4,#trigger-scrollbar-5,#trigger-scrollbar-6').click(function(e){
		// notice we call the plugin twice, as the first time it wont get the dimensions right
		$(this).parent().find('.scrollbar-y').tinyscrollbar().tinyscrollbar({size:190});
	});
	// reset if the screen gets resized
	$(window).resize(function(){
		// need to call the separtly
		if($('.scrollbar-y').length){
			$('.scrollbar-y').tinyscrollbar_update('relative');
		}
		if($('.scrollbar-x').length){
			$('.scrollbar-x').tinyscrollbar_update('top');// value 'relative' is buggy!
		}
	});
	// re-initialize(if tab is hidden it will not work)
	$('.ext-tabs-sidebar a').click(function(){
		$('.scrollbar-y').tinyscrollbar();
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Lock screen
	 * Description : Screen lock with 3 different types of lock/unlock
	 * File Name   : lockscreen.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery UI, jQuery core
	 * Developer   : Mark
	**/

	/**
	* Form.
	**/
	$('#lockscreen-form-trigger').click(function(e){


		$('#lockscreen-form').find('.lockscreen-modal').append('<div class="lockscreen-loader"></div>');

		/**
		* Show the box.
		**/
		$('#lockscreen-form').show();
		/**
		* Run unlockscreen.
		**/
		$('#lockscreen-form').find('.lockscreen-placeholder').lockScreen({
			type:'form',
			unlockText: 'Type something...',
			delayUnlock: 200,
			unlockAt: 100,
			submitText:'Unlock',
			formAction:'ajax/lockscreen.php',
			minChar:3,
			start:function(){},
			fail:function(){
				$('#lockscreen-form').find('.alert').remove();
				$('#lockscreen-form .lockscreen-modal').prepend('<div class="alert alert-danger">Type some more!</div>');
			},
			success:function(){
				$('#lockscreen-form').find('.alert').remove();
				$('#lockscreen-form').find('.lockscreen-loader').show();
				setTimeout(function(){
					$('#lockscreen-form').find('.lockscreen-loader').hide();
					$('#lockscreen-form').hide();
				},3000);
			}
		});
		e.preventDefault();
	});

	/**
	* Button.
	**/
	$('#lockscreen-button-trigger').click(function(e){
		/**
		* Show the box.
		**/
		$('#lockscreen-button').show();
		/**
		* Run unlockscreen.
		**/
		$('#lockscreen-button').find('.lockscreen-placeholder').lockScreen({
			type:'button',
			unlockText: 'Unlock',
			delayUnlock: 200,
			unlockAt: 100,
			submitText:'Click to unlock',
			formAction:'',
			minChar:3,
			start:function(){},
			fail:function(){},
			success:function(){
				$.nanoGress.start({target: '.lockscreen-modal'});
				$.nanoGress.end({onEnd:function(){
					$('#lockscreen-button').hide();
				}});
			}
		});
		e.preventDefault();
	});

	/**
	* Slider.
	**/
	$('#lockscreen-slider-trigger').click(function(e){
		/**
		* Show the box.
		**/
		$('#lockscreen-slider').show();
		/**
		* Run unlockscreen.
		**/
		$('#lockscreen-slider').find('.lockscreen-placeholder').lockScreen({
			type:'slider',
			unlockText: 'Unlock',
			delayUnlock: 200,
			unlockAt: 100,
			submitText:'Login',
			formAction:'',
			minChar:3,
			start:function(){},
			fail:function(){},
			success:function(){
				$.nanoGress.start({target: '.lockscreen-modal'});
				$.nanoGress.end({onEnd:function(){
					$('#lockscreen-slider').hide();
				}});
			}
		});
		e.preventDefault();
	});

	/**
	* Form(target location).
	**/
	$('#lockscreen-target-trigger').click(function(e){
		/**
		* Show the box.
		**/
		$('#lockscreen-target').show();
		/**
		* Run unlockscreen.
		**/
		$('#lockscreen-target').find('.lockscreen-placeholder').lockScreen({
			type:'form',
			unlockText: 'Type something...',
			delayUnlock: 200,
			unlockAt: 100,
			submitText:'Login',
			formAction:'ajax/lockscreen.php',
			minChar:3,
			start:function(){},
			fail:function(){},
			success:function(){
				$.nanoGress.start({target: '#lockscreen-target'});
				$.nanoGress.end({onEnd:function(){
					$('#lockscreen-target').hide();
				}});
			}
		});
		e.preventDefault();
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Gmap3
	 * Description : Google maps
	 * File Name   : gmap3.js
	 * Plugin Url  : http://gmap3.net/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Richard
	**/

	$('#gmap3-sidebar').gmap3({
		map:{
			options:{
				center: [-33, 151],
				zoom: 8
			}
		},
		marker:{
			latLng: [-33, 151]
		}
	});

	$('#gmap3-1').gmap3({
		marker:{
			latLng:[40.780541,-73.916016]
		},
		map: {
			options: {
				zoom: 12
			}
		}
	});

	$('#gmap3-2').gmap3({
		getgeoloc:{
			callback : function(latLng){
				if (latLng){
					$('#gmap3-2').html('localised !');
					$(this).gmap3({
						marker:{
							latLng:latLng
						}
					});
				} else {
					$('#gmap3-2').html('not localised !');
				}
			}
		}
	});

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Datepicker
	 * Description : Bootstrap dropdown datepicker
	 * File Name   : datepicker.js
	 * Plugin Url  : https://github.com/eternicode/bootstrap-datepicker
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('#datepicker-1, #datepicker-2, #datepicker-3, #datepicker-4, #datepicker-form-1, #datepicker-form-2').datepicker({
		format: 'mm-dd-yyyy'
	});

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap File upload
	 * Description : Bootstrap styeld file upload input
	 * File Name   : bootstrap-fileupload.js
	 * Plugin Url  : jasny.github.com/bootstrap/javascript.html
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Mark
	**/

	// plugin is called with a datasets as this line will case an error
	// (waiting for an plugin update)

    //$('.fileupload').fileupload();

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Masked input
	 * Description : Bootstrap masked inpots
	 * File Name   : bootstrap-maskinput.js
	 * Plugin Url  : jasny.github.com/bootstrap/javascript.html
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('.inputmask').inputmask();

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Carousel
	 * Description : Bootstrap animated carousel
	 * File Name   : bootstrap.js
	 * Plugin Url  : twitter.github.com/bootstrap/javascript.html
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('.carousel').carousel({
		interval: 5000,
		pause: 'hover'
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Full Calendar
	 * Description : Calendar plugin
	 * File Name   : fullcalendar.js
	 * Plugin Url  : http://arshaw.com/fullcalendar/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	/* initialize the external events
	-----------------------------------------------------------------*/

	$('.calendar-events .single-cal-event').each(function() {

		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};

		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);

		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});

	});

	var date = new Date();
	var d    = date.getDate();
	var m    = date.getMonth();
	var y    = date.getFullYear();

	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		events: [
			{
				title: 'All Day Event',
				start: new Date(y, m, 1)
			},
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-2)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d-3, 16, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+4, 16, 0),
				allDay: false
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: 'http://google.com/'
			}
		],
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay, jsEvent, ui) { // this function is called when something is dropped

			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');

			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);

			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;

			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

			// is the "remove after drop" checkbox checked?
			if($(this).parents('.calendar-events').find('.drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}

		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Auto Save Forms
	 * Description : Saves the content of a form to the localstorage if the
	                 browsers gets refreshed to soon or by accident
	 * File Name   : autosaveform.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('#autosavethisform').autoSaveForms({
		saveTime: 500,
		expireAfter: 1,
		onSave: function(ui){
			//ui.item(element)
			//ui.output(string)

			// notify plugin
			$.e_notify.growl({
				 text: 'The form has been auto saved!',
				 position: 'right-bottom',
				 delay: 0,
				 time: 1000,
				 speed: 500,
				 effect: 'fade',
				 sticky: false,
				 closable: false,
				 maxOpen:1,
				 className:'',
				 onShow: function(){},
				 onHide: function(){}
			});
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Responsive helper
	 * Description : Helper plugin which counts the total width from the
	                 containers direct children and ads/removes a class
					 (container or/and children). Build mainly for the
					 layouts which has a 'pull-left' and 'pull-right' class.
	 * File Name   : responsivetables.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  :jQuery core
	 * Developer   : Mark
	**/

	$('.responsive-helper').responsiveHelper({
		containerClass:'',
		childrenClass: 'block',
		inResponsiveMode:function(ui){
			//ui.item(element)
		},
		outResponsiveMode:function(ui){
			//ui.item(element)
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Responsive tables
	 * Description : Responsive tables with column sorting
	 * File Name   : responsivetables.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  :jQuery core
	 * Developer   : Mark
	**/

	// ticketssupport.html
	$('#ticket-table').responsiveTables({
		columnManage: false,
		exclude: '',
		menuIcon: '<i class="fa fa-bars"></i>',
		startBreakpoint: function(ui){
			//ui.item(element)
			ui.item.find('label').parents('.rt-responsive-row').hide();
		},
		endBreakpoint: function(ui){
			//ui.item(element)
			ui.item.find('label').parents('.rt-responsive-row').show();
		},
		onColumnManage: function(){}
	});
	// comments.html
	$('#comment-table').responsiveTables({
		columnManage: false,
		exclude: '',
		menuIcon: '<i class="fa fa-bars"></i>',
		startBreakpoint: function(ui){
			//ui.item(element)
			ui.item.find('label').parents('.rt-responsive-row').hide();
		},
		endBreakpoint: function(ui){
			//ui.item(element)
			ui.item.find('label').parents('.rt-responsive-row').show();
		},
		onColumnManage: function(){}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Chained inputs
	 * Description : Group inputs and auto focus if maxlength is reached
	 * File Name   : chainedinputs.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.groupedform').chainedInputs({
		maxLength: 5,
		onEndBlur: true,
		onSwitch: function(ui){
			//ui.item(element)
		},
		onEnd: function(){}
	});

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Colorpicker
	 * Description : Bootstrap dropdown color picker
	 * File Name   : bootstrap.js
	 * Plugin Url  : http://www.eyecon.ro/bootstrap-colorpicker/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('#colorpicker-1, #colorpicker-2').colorpicker();

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootstrap Timepicker
	 * Description : Bootstrap dropdown time picker
	 * File Name   : timepicker.js
	 * Plugin Url  : http://jdewit.github.com/bootstrap-timepicker/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('#timepicker1').timepicker();

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : noUiSlider
	 * Description : Lightweight range slider
	 * File Name   : nouislider.js
	 * Plugin Url  : refreshless.com/nouislider/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	$("#slider-1").noUiSlider({
		range: [0, 100],
		start: 50,
		handles: 1,
		connect: "lower"
	});

	$("#slider-2").noUiSlider({
		range: [0, 100],
		start: 50,
		handles: 1,
		orientation: "vertical"
	});

	$("#slider-3").noUiSlider({
		range: [0, 1],
	    start: 0,
	    step: 1,
		handles: 1,
		connect: "lower",
	});

	$("#slider-4").noUiSlider({
		range: [0, 100],
		start: [60],
		handles: 1,
		connect: "lower",
		serialization: {
			to: [$("#slider-4-field-from")]
		}
	});

	$("#slider-5").noUiSlider({
		range: [0, 100],
		start: [50, 70],
		handles: 2,
		serialization: {
			to: [$("#slider-5-field-to"),$("#slider-5-field-from")]
		}
	});

	$("#slider-6").noUiSlider({
		range: [0, 10],
		start: [3],
		handles: 1,
		step: 1,
		connect: "lower",
		serialization: {
			to: [$("#slider-6-field-from")],
			resolution: 1
		}
	});

	$("#slider-7").noUiSlider({
		range: [0, 100],
		start: 20,
		handles: 1,
		orientation: "vertical",
		serialization: {
			to: [$("#slider-7-field-from")],
			resolution: 1
		}
	});

	$("#slider-8").noUiSlider({
		range: [0, 100],
		start: 80,
		handles: 1,
		orientation: "vertical",
		serialization: {
			to: [$("#slider-8-field-from")],
			resolution: 1
		}
	});

	$("#slider-9").noUiSlider({
		range: [0, 100],
		start: 60,
		handles: 1,
		orientation: "vertical",
		serialization: {
			to: [$("#slider-9-field-from")],
			resolution: 1
		}
	});

	$("#slider-10").noUiSlider({
		range: [0, 100],
		start: 20,
		handles: 1,
		orientation: "vertical",
		serialization: {
			to: [$("#slider-10-field-from")],
			resolution: 1
		}
	});

	$("#slider-11").noUiSlider({
		range: [0, 100],
		start: 50,
		handles: 1,
		orientation: "vertical",
		serialization: {
			to: [$("#slider-11-field-from")],
			resolution: 1
		}
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : jQuery Autosize
	 * Description : A plugin to enable automatic height for textarea elements.
	 * File Name   : nouislider.js
	 * Plugin Url  : refreshless.com/nouislider/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	$('.autosize').autosize({
		className:'mirroredText'
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : jQuery lightbox
	 * Description : Lightbox with several custom styles and support for videos
	 * File Name   : magnific-popup.js
	 * Plugin Url  : http://dimsemenov.com/plugins/magnific-popup/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	$('.lightbox').magnificPopup({
		type:'image',
		callbacks: {
			open: function(){
			  // remove the marginright from the body added by this plugin
			  $('html').css({marginRight: 0});
			},
			close: function(){ }
		  }
	});

	$('.lightbox-group-1').magnificPopup({
		type:'image',
		gallery: {
  			enabled: true
		},
		image:{
			titleSrc: function(item) {
			  return item.el.attr('title') + '<small>artwork by creativemilk</small>';
			 }
		},
		callbacks: {
			open: function(){
			  // remove the margin right from the body added by this plugin
			  $('html').css({marginRight: 0});
			},
			close: function(){ }
		  }
	});

	$('.lightbox-iframe').magnificPopup({
		disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
		fixedContentPos: false,
		callbacks: {
			open: function(){
			  // remove the padding right from the body added by this plugin
			  $('html').css({paddingRight: 0});
			},
			close: function(){ }
		  }
    });

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : MixItUp
	 * Description : Sorting and filtering plugin
	 * File Name   : mixitup.js
	 * Plugin Url  : http://mixitup.io/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	$('#mix-1').mixitup({
		targetSelector: '.mix',
		filterSelector: '#filter-1 .filter',
		sortSelector: '#filter-1 .sort',
		onMixEnd:function(){
			$('.lightbox').magnificPopup({type:'image'});
		}
	});

	$('#mix-2').mixitup({
		targetSelector: '.mix',
		filterSelector: '#filter-2 .filter',
		sortSelector: '#filter-2 .sort',
		onMixEnd:function(){
			$('.lightbox').magnificPopup({type:'image'});
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Checkbox toggle
	 * Description : Allow mass toggle/un-toggle with a click
	 * File Name   : ccheckboxtoggle.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('#checkboxtoggle-1').checkboxToggle({
		trigger: '.checkbox-master',
		exclude: '.exclude-toggle',
		allowShiftSelect:true,
		onToggle:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(number)

			$('#checkboxtoggle-1').find('.checkboxtoggle-placeholder').children().text(ui.total);
		},
		onSelect:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(number)

			$('#checkboxtoggle-1').find('.checkboxtoggle-placeholder').children().text(ui.total);
		}
	});
	$('#checkboxtoggle-2').checkboxToggle({
		trigger: '.checkbox-master',
		exclude: '.exclude-toggle',
		allowShiftSelect:true,
		onToggle:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(integer)
		},
		onSelect:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(integer)
		}
	});
	$('table').checkboxToggle({
		trigger: '.checkbox-master',
		exclude: '.exclude-toggle',
		allowShiftSelect:true,
		onToggle:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(integer)

			/* how you can use a indicator class
			if(ui.state){
				$(ui.item).parents('table').find('tbody').children('tr').children('td:first-child').has('label').addClass('tr-selected');
			}else{
				$(ui.item).parents('table').find('tbody').find('.tr-selected').removeClass('tr-selected');
			}
			*/
		},
		onSelect:function(ui){
			//ui.item(element)
			//ui.state(boolean)
			//ui.total(integer)

			/* how you can use a indicator class
			if(ui.state){
				$(ui.item).parents('td').addClass('tr-selected');
			}else{
				$(ui.item).parents('td').removeClass('tr-selected');
			}
			*/
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Notify
	 * Description : Notifications plugin(growl, loader and notification)
	 * File Name   : notify.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	/* Sticky */
	$('#open-growl-1').click(function(e){
		 $.e_notify.growl({
			 title: 'Sticky growl',
			 text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel arcu est. Suspendisse laoreet nisl nec magna feugiat.',
			 image: 'images/users/user-1.jpg',
			 position: 'right-top',
			 delay: 0,
			 time: 2500,
			 speed: 400,
			 effect: 'fade',
			 sticky: true,
			 closable: true,
			 maxOpen:3,
			 className:'',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Auto hide */
	$('#open-growl-2').click(function(e){
		 $.e_notify.growl({
			 title: 'Sticky with auto hide',
			 text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel arcu est. Suspendisse laoreet nisl.',
			 image: 'images/users/user-2.jpg',
			 position: 'right-bottom',
			 delay: 0,
			 time: 5000,
			 speed: 500,
			 effect: 'slidein',
			 sticky: false,
			 closable: false,
			 maxOpen:3,
			 className:'',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Only text */
	$('#open-growl-3').click(function(e){
		 $.e_notify.growl({
			 text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel arcu est. Suspendisse laoreet nisl.',
			 position: 'left-top',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slidein',
			 sticky: true,
			 closable: true,
			 maxOpen:3,
			 className:'',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Max 6 open */
	$('#open-growl-4').click(function(e){
		 $.e_notify.growl({
			 title: 'Sticky with max 6 open',
			 position: 'left-bottom',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slidein',
			 sticky: true,
			 closable: true,
			 maxOpen:6,
			 className:'',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Custom class*/
	$('#open-growl-5').click(function(e){
		 $.e_notify.growl({
			 title: 'Growl with custom class',
			 text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel arcu est. Suspendisse laoreet nisl nec magna feugiat.',
			 image: 'images/users/user-4.jpg',
			 position: 'right-top',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slidein',
			 sticky: true,
			 closable: true,
			 maxOpen:3,
			 className:'growl-white',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Callbacks */
	$('#open-growl-6').click(function(e){
		 $.e_notify.growl({
			 title: 'Sticky with callbacks',
			 text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris vel arcu est. Suspendisse laoreet nisl.',
			 position: 'left-bottom',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slidein',
			 sticky: true,
			 closable: true,
			 maxOpen:3,
			 className:'',
			 onShow: function(){alert('Callback on show...')},
			 onHide: function(){alert('Callback on hide...')}
		});
		e.preventDefault();
	});

	/* Remove all */
	$('#open-growl-7').click(function(e){
		 $.e_notify.clear({
			 type: 'growl',
			 beforeClear: function(){alert('You can use a callback before removing...')},
			 afterClear: function(){alert('You can use a callback after removing...')}
		});
		e.preventDefault();
	});

    /* LOADER */

    /* Basic */

	/* No opacity $ other position */
	$('#open-loader-2').click(function(e){
		$.e_notify.loader({
			 image: 'images/loaders/type4/light/56.gif',
			 position: 'right-bottom',
			 imageSize: '56 | 19',
			 delay: 0,
			 time: 2000,
			 speed: 200,
			 opacity: 0.0,
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

    /* Callbacks */
	$('#open-loader-3').click(function(e){
		$.e_notify.loader({
			 image: 'images/loaders/type2/light/32.gif',
			 position: 'center',
			 imageSize: '32 | 32',
			 delay: 0,
			 time: 2000,
			 speed: 200,
			 opacity: 0.5,
			 onShow: function(){alert('Callback on show...')},
			 onHide: function(){alert('Callback on hide...')}
		});
		e.preventDefault();
	});

	/* NOTIFICATION */

	/* Top */
	$('#open-notification-1').click(function(e){
		 $.e_notify.notification({
			 text: 'This notification message, has the custom class called "notification-warning".',
			 position: 'top',
			 target: '',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slide',
			 sticky: true,
			 closable: true,
			 className:'notification-warning',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Bottom */
	$('#open-notification-2').click(function(e){
		 $.e_notify.notification({
			 text: 'This notification message, has the custom class called "notification-info".',
			 position: 'bottom',
			 target: ' ',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slide',
			 sticky: true,
			 closable: true,
			 className:'notification-info',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Target */
	$('#open-notification-3').click(function(e){
		 $.e_notify.notification({
			 text: 'Yes you can add a notification to a target!',
			 position: 'top',
			 target: '#hidden-target',
			 delay: 0,
			 time: 2500,
			 speed: 500,
			 effect: 'slide',
			 sticky: true,
			 closable: true,
			 className:'notification-success',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Auto hide */
	$('#open-notification-4').click(function(e){
		 $.e_notify.notification({
			 text: 'This notification message, has the custom class called "notification-error". This message will hide after 5000 ms.',
			 position: 'top',
			 target: '',
			 delay: 0,
			 time: 5000,
			 speed: 500,
			 effect: 'slide',
			 sticky: false,
			 closable: true,
			 className:'notification-error',
			 onShow: function(){},
			 onHide: function(){}
		});
		e.preventDefault();
	});

	/* Callbacks */
	$('#open-notification-5').click(function(e){
		 $.e_notify.notification({
			 text: 'This notification message, has the custom class called "notification-error". This mesaage will hide after 5000 ms.',
			 position: 'top',
			 target: '',
			 delay: 0,
			 time: 2000,
			 speed: 500,
			 effect: 'slide',
			 sticky: false,
			 closable: true,
			 className:'notification-error',
			 onShow: function(){alert('Callback on show...')},
			 onHide: function(){alert('Callback on hide...')}
		});
		e.preventDefault();
	});

	/* Remove all */
	$('#open-notification-6').click(function(e){
		 $.e_notify.clear({
			 type: 'notification',
			 beforeClear: function(){alert('You can use a callback before removing...')},
			 afterClear: function(){alert('You can use a callback after removing...')}
		});
		e.preventDefault();
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : jQuery UI sortable
	 * Description : Used to create portlets
	 * File Name   : prettyphoto.js
	 * Plugin Url  : http://jqueryui.com/sortable/
	 * Updated     : --/--/----
	 * Dependency  : jQuery UI
	 * Developer   : Brandon
	**/

	 // sidebar
	 $('.sidebar .portlets').sortable({
	 	axis: "y",
		revert: true,
		handle: '.portlet-header' ,
		cancel:'.btn',
		zIndex: 2000
	 });
	 // regular
	 $('.portlets-col').sortable({
	 	connectWith: '.portlets-col',
		revert: true,
		handle: '.portlet-header' ,
		cancel:'.btn',
		placeholder: 'portlet-placeholder',
		forcePlaceholderSize: true,
		forceHelperSize: true,
		zIndex: 2000
	 });

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Password Strength Indicator
	 * Description : Password strength meter
	 * File Name   : jquery.pwstrength.js
	 * Plugin Url  : matoilic.github.com/jquery.pwstrength
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Richard
	**/

	$('.password').pwstrength();

	$('.password').keyup(function(e){
		if($(this).val().length != 0){
			$('#pwindicator').show();
		}else{
			$('#pwindicator').hide();
		}
	});

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Auto expand
	 * Description : Auto expand a textarea on focus
	 * File Name   : autoexpand.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('.auto-expand').autoExpand({
		speed: 200,
		height:150,
		keepOpen: 'present',
		onFocus:function(ui){
			//ui.item(element)
		},
		onBlur:function(ui){
			//ui.item(element)
		}
	});

    /* BOOTSTRAP ----------------------------------------------------------- */

	/**
	 * Name        : Bootbox
	 * Description : Bootstrap alert/confirm/prompt box
	 * File Name   : bootbox.js
	 * Plugin Url  : http://bootboxjs.com/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core, bootstrap
	 * Developer   : Richard
	**/

	$('#bootbox-alert-trigger').click(function(){
		bootbox.alert("Hello world!", function() {});
	});

	$('#bootbox-confirm-trigger').click(function(){
		bootbox.confirm("Are you sure?", function(result) { });
	});

	$('#bootbox-prompt-trigger').click(function(){
		bootbox.prompt("What is your name?", function(result) {});
	});

	/* THIRD PARTY ----------------------------------------------------------- */

	/**
	 * Name        : Flot
	 * Description : Plotting library
	 * File Name   : flot.js
	 * Plugin Url  : http://www.flotcharts.org/
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Brandon
	**/

	/* chart 1 */
	if($('#chart-bars-1').length){

		var d11 = [];
		for (var i = 0; i <= 24; i += 1) {
			d11.push([i, parseInt(Math.random() * 30)]);
		}

		var d12 = [];
		for (var i = 0; i <= 24; i += 1) {
			d12.push([i, parseInt(Math.random() * 30)]);
		}

		$.plot("#chart-bars-1",
		[d11, d12],
			{
			series: {
				bars: {
					show: true,
					barWidth: 0.7,
					align: "center",
					lineWidth: 1.0,
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
			grid: {
				show: true,
				aboveData: true,
				color: '#bbbbbb',
				borderWidth:1.0
			},
			shadowSize: 0.1,
			colors: ['#999','#777']
		});
	}

	/* chart 2 */
	if($('#chart-mixed-1').length){

		var d1 = [];
		for (var i = 1; i <= 14; i += 1)
		d1.push([i, parseInt(Math.random() * 18000)]);

		var d2 = [];
		for (var i = 1; i <= 14; i += 1)
		d2.push([i, parseInt(Math.random() * 22800)]);

		var d3 = [];
		for (var i = 1; i <= 14; i += 1)
		d3.push([i, parseInt(Math.random() * 14600)]);

		var d4 = [];
		for (var i = 1; i <= 14; i += 1)
		d4.push([i, parseInt(Math.random() * 20900)]);

		$.plot("#chart-mixed-1",
			[
				{
					label: "ThemeForest",
					data: d1,
					lines: { show: true, fill: 0.4 },
					color: "#bbb",
					hoverable: true
				},
				{
					label: "CodeCanyon",
					data: d2,
					lines: { show: true, lineWidth: 4 },
					color: "#666"
				},
				{
					label: "GaphicRiver",
					data: d3,
					lines: { show: true, lineWidth: 2 },
					color: "#ccc"
				},
				{
					label: "PhotoDune",
					data: d4,
					lines: { show: true, lineWidth: 2 },
					color: "#999"
				}
			],
			{
				series	:	{ lines: { show: true }, points: { show: true }, curvedLines: { active: true } },
				grid	:	{ hoverable: true, clickable: false, color: '#333', borderWidth:1.0, borderColor:'#bbb'},
				legend	:	{ show: true },
				yaxis	:	{ position: "left"}
			}
		);
	}

	/* chart 3 */
	if($('#chart-rt-1').length){

		var container = $("#chart-rt-1");

		// Determine how many data points to keep based on the placeholder's initial size;
		// this gives us a nice high-res plot while avoiding more than one point per pixel.

		var maximum = container.outerWidth() / 2 || 300;

		var data = [];

		function getRandomData() {

			if (data.length) {
				data = data.slice(1);
			}

			while (data.length < maximum) {
				var previous = data.length ? data[data.length - 1] : 50;
				var y = previous + Math.random() * 10 - 5;
				data.push(y < 0 ? 0 : y > 100 ? 100 : y);
			}

			// zip the generated y values with the x values

			var res = [];
			for (var i = 0; i < data.length; ++i) {
				res.push([i, data[i]])
			}

			return res;
		}

		series = [{
			data: getRandomData(),
			lines: {
				fill: true
			}
		}];

		var plot = $.plot(container, series, {
			grid: {
				borderWidth: 1,
				minBorderMargin: 0,
				labelMargin: 10,
				color: '#bbbbbb',
			},
			xaxis: {
				tickFormatter: function() {
					return "";
				}
			},
			yaxis: {
				min: 0,
				max: 110
			},
			legend: {
				show: true
			},
			colors: ['#999']
		});

		// Create the demo X and Y axis labels

		//var yaxisLabel = $('<div class="axisLabel yaxisLabel"></div>')
			//.text("Response Time (ms)")
			//.appendTo(container);

		// Since CSS transforms use the top-left corner of the label as the transform origin,
		// we need to center the y-axis label by shifting it down by half its width.
		// Subtract 20 to factor the chart's bottom margin into the centering.

		//yaxisLabel.css("margin-top", yaxisLabel.width() / 2 - 20);

		// Update the random dataset at 25FPS for a smoothly-animating chart

		setInterval(function updateRandom() {
			series[0].data = getRandomData();
			plot.setData(series);
			plot.draw();
		}, 40);
	}

	/* CUSTOM/PREMIUM PLUGIN ----------------------------------------------- */

	/**
	 * Name        : Tiny Context menu
	 * Description : Right click menu
	 * File Name   : tinycontextmenu.js
	 * Plugin Url  :
	 * Updated     : --/--/----
	 * Dependency  : jQuery core
	 * Developer   : Mark
	**/

	$('#tiny-context-menu').tinyContextMenu({
		container: '#tcm-demo-1',
		offsetX:0,
		offsetY:0,
		onShow:function(ui){
			//ui.container(element)
		},
		onHide:function(ui){
			//ui.container(element)
		}
	});

});

</script>
