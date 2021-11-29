<script type="text/javascript">
/*
 * ************************************************************* *
 * Name       : Main(custom)                                     *
 * Date       : March 2012                                       *
 * Owner      : CreativeMilk                                     *
 * Url        : www.creativemilk.net                             *
 * Version    : 1.0                                              *
 * Updated    : 2014-03-09 21:40:27 UTC+02:00                    *
 * Developer  : Mark                                             *
 * Dependency : see below                                        *
 * Lib        : 1.7+                                             *
 * Licence    : NOT free                                         *
 * This is part of a themeforest file                            *
 * ************************************************************* *
 */ 
 
$(document).ready(function($){ 

	/**
	* Check for localStorage support.
	**/
	var storage = !!function() {
	  var result,
		  uid = +new Date;
	  try {
		localStorage.setItem(uid, uid);
		result = localStorage.getItem(uid) == uid;
		localStorage.removeItem(uid);
		return result;
	  } catch(e) {}
	}() && localStorage;

	/**
	* Check for touch support and set right click events.
	**/
	if(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch){
		var clickEvent = 'touchstart';
		var useTap     = true;		
	}else{
			clickEvent = 'click';
			useTap     = false;	
	}
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : SwitchClass
	 * Description : A basic function which let you switch between 2 classes.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	 * 
	 * @param: target   | object  | target object.
	 * @param: classOne | string  | first class name.
	 * @param: classTwo | string  | second class name.
	 * @param: delay    | integer | delay in millisecs.
	**/
		
	function switchClass(target, classOne, classTwo, delay){
		var delay = !isNaN(delay) ? delay : 0;
		setTimeout(function(){
			if(target.hasClass(classOne)){
				target.removeClass(classOne).addClass(classTwo);	
			}else if(target.hasClass(classTwo)){
				target.removeClass(classTwo).addClass(classOne);
			}
		},delay)
	}

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Activity layout switch(index.html)
	 * Description : Switch between 2 layout types.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/

	$('body').on(clickEvent, '#as-min-trigger',function(e){
		$('.activity-stream-sub').slideUp(200,function(){
			$('.activity-stream-item .avatar').fadeOut(200,function(){
				$('.activity-stream-content').animate({marginLeft: 0},200);
			});
		});
		$(this).addClass('active').next().removeClass('active');
		
		e.preventDefault();	
	});
	
	$('body').on(clickEvent, '#as-plus-trigger',function(e){
		$('.activity-stream-sub').slideDown(200,function(){
			$('.activity-stream-content').animate({marginLeft: '60px'},1,function(){
				$('.activity-stream-item .avatar').fadeIn(200)
			});
		});
		$(this).addClass('active').prev().removeClass('active');
		
		e.preventDefault();	
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Toggle Main Footer
	 * Description : Hide/show the main footer
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '#toggle-footer', function(e){
		if($(this).children().hasClass('fa-chevron-up')){
			$(this).animate({marginTop: '-1px'},400,function(){
				$(this).parents('#footer-main').animate({bottom: 0},400);
			});
		}else{
			$('#footer-main').animate({bottom: '-40px'},400, function(){
			   $('#toggle-footer').animate({marginTop: '-40px'},400);
		   }); 
		}  
		switchClass($(this).children(), 'fa-chevron-up', 'fa-chevron-down', 400);
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Responsive support timeline
	 * Description : Go from 2 columns to 1 column if the res is to low.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	function responsiveTimeline(){
		if($(window).width() < $('.timeline').attr('data-breakpoint')){
			$('.timeline').addClass('one-col-timeline');
		}else{
			$('.timeline').removeClass('one-col-timeline');
		}
	};
	
	$(window).resize(function(){
		responsiveTimeline();	
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Bootstrap responsive bootstrap menu helper
	 * Description : Transform a extended bootstrap dropdown menu(mega menu) into a responsive menu
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	function megaMenuResponsive(){	
		var resClass = 'ext-responsivemode';
		$('.ext-megamenu.col-2').each(function(){
			if($(window).width() < 321){
				$(this).addClass(resClass);	
			}else{
				$(this).removeClass(resClass);	
			}
		});
		$('.ext-megamenu.col-3').each(function(){
			if($(window).width() < 482){
				$(this).addClass(resClass);	
			}else{
				$(this).removeClass(resClass);	
			}
		});
		$('.ext-megamenu.col-4').each(function(){
			if($(window).width() < 643){
				$(this).addClass(resClass);	
			}else{
				$(this).removeClass(resClass);	
			}
		});
	}
	
	$(window).resize(function(){
		megaMenuResponsive();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle sidebar modules
	 * Description : Hide/show sidebar modules
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '[data-module-toggle=true]',function(e){
		
		var module = $(this).next();
		
		if(module.is(':hidden')){
			module.show();
		}else{
			module.hide();
		}	
	    switchClass($(this).find('.fa.pull-right'), 'fa-caret-left', 'fa-caret-down', 0);
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle Sidebar
	 * Description : Hide/show the right sidebar
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '[data-toggle-sidebar]',function(e){
		
		var sidebar      = $(this).attr('data-toggle-sidebar');
		var icon         = $(this).children();
		var leftSidebar  = 'hide-left-sidebar';
		
	
		if($('body').hasClass(leftSidebar)){
			$('body').removeClass(leftSidebar);
		}else{
			$('body').addClass(leftSidebar);
		}	
		
		// use if you want to use icons inside the trigger				
		switchClass($(this).children('.fa'), 'fa-chevron-left', 'fa-chevron-right', 0);
				
		// reinitialize
		if($('.scrollbar-y').length){
			$('.scrollbar-y').tinyscrollbar_update('relative');
		}
		if($('.scrollbar-x').length){
			$('.scrollbar-x').tinyscrollbar_update('top');// relative is buggy!
		}
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : ToDo
	 * Description : Add/remove line through class
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Brandon	 
	**/
	
	$('.sidebar-todo-day').on(clickEvent, 'label',function(e){
		if($(this).find('input').is(':checked')){
			$(this).addClass('line-through');
		}else{
			$(this).removeClass('line-through');
		}
	});

	/* --------------------------------------------------------------------- */
	   
	/**
	 * Name        : Bootstrap dropdown
	 * Description : Allow inside clicking
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/

	$('.dropdown-menu:not(.datepicker, .rt-table .dropdown-menu, .wysihtml5-toolbar .dropdown-menu, .bootstrap-timepicker-widget)').on(clickEvent,'*',function(e) {
		e.stopPropagation();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Hide indicator dots
	 * Description : Hide indicator dots if they are present in dropdown buttons
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.dropdown .btn, .dropup .btn').on(clickEvent,function(e){
		$(this).find('.indicator-dot').fadeOut()	
	});	 
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle responsive Menu
	 * Description : Hide/show the apdative menu located in the header
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '#responsive-menu-trigger',function(e){
		if($('body').hasClass('sidebar-main-open')){
			$('body').removeClass('sidebar-main-open');
		}else{
			$('body').addClass('sidebar-main-open');
		}
		
		switchClass($(this).children(), 'fa-bars', 'fa-times', 0);
		
		if($('.scrollbar-x').length){
			$('.scrollbar-x').tinyscrollbar_update('top');// relative is buggy!
		}
		
		e.preventDefault();
	});
	
	// reset layout(savety)
	$(window).resize(function(){
		$('body').removeClass('sidebar-main-open');
		$('#responsive-menu-trigger i').removeClass('fa-times').addClass('fa-bars');
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Timeline Delete
	 * Description : Remove timeline messages
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	// delete all timeline 
	$('.timeline').on(clickEvent,'.delete-master',function(e){
		var tm = $(this).parents('.timeline');
		bootbox.confirm("Delete all activity?", function(e) {
			if(e === true ){
				tm.find('.timeline-row').slideUp(400,function(){
					$(this).remove();
					tm.find('.timeline-note').fadeIn(400);
				});
			}
		});
	});
	
	// delete single timeline
	$('.timeline').on(clickEvent,'.delete',function(e){
		var tm    = $(this).parents('.timeline');
		var total = tm.find('.timeline-row').length;
		
		$(this).parents('.timeline-row').slideUp(400,function(){
			$(this).remove();
			if(total == 1){
				tm.find('.timeline-note').fadeIn(400);
			}
		});
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle timeline content
	 * Description : Hide/show the content
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.timeline').on(clickEvent,'.toggle',function(e){
		$(this).parents('.timeline-row').find('.timeline-content').animate({height: 'toggle'});
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Delete comments dropdown
	 * Description : Delete the comments in the bootstrap extended dropdown
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	 *
	 * @param: t | object  | target object.
	 * @param: c | object  | parents object.
	 * @param: m | boolean | master delete.
	**/
	
	function deleteMsg(t, c, m){
		var num       = t.parents(c).children('div').length;
		var indicator = t.parents('.dropdown, .dropup').find('.indicator-dot');	
		if(m === true){
			t.parents('.dropdown, .dropup').find(c).children('div').slideUp(400, function(){
				$(this).remove();
			});				
		}else{
			t.closest('div').slideUp(400, function(){
				$(this).remove();
			});				
		}
		if(num == 1 || m === true){
		    indicator.remove();
			setTimeout(function(){
				t.parents('.dropdown, .dropup').find(c).children('span').slideDown(400);
			},300);
		}else{
			indicator.text(num-1);
		}
	}
	
	// delete single comments
    $('.ext-dropdown-inbox, .ext-dropdown-comments').on(clickEvent,'.delete',function(e){
		deleteMsg($(this), '.ext-dropdown-inbox-content, .ext-dropdown-comments-content', false);
		e.preventDefault();
	});	
	
	// delete all comments
    $('.ext-dropdown-inbox, .ext-dropdown-comments').on(clickEvent,'.delete-master *, .delete-master',function(e){
		deleteMsg($(this), '.ext-dropdown-inbox-content, .ext-dropdown-comments-content', true);
		e.preventDefault();	
	});	

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Clear Localstorage
	 * Description : Remove all localstorage keys
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Richard	 
	**/
	
    $('body').on(clickEvent, '#empty-localstorage-trigger', function(e){
		bootbox.dialog({
			message: "This will delete all of the local storage settings. The page will be reloaded once you have pressed 'ok'!",
			title: "Clear local Storage",
			buttons: {
				danger: {
					label: "Cancel",
					className: "btn-default",
					callback: function(){}
				},
				main: {
					label: "Ok",
					className: "btn-primary",
					callback: function() {
						if(localStorage){
							localStorage.clear();
							alert('Local storage has been cleared! Reloading the page...');
							location.reload(); 
						}
					}
				}
			}
		});
	});
	
	/* --------------------------------------------------------------------- */
		
	/**
	 * Name        : Blink icon
	 * Description : Flash an icon(chat) with the use of the animation css file
	 * Url         : http://daneden.me/animate/
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core, animation.css
	 * Developer   : Mark	 
	**/
	
	$('.chat-blink-icon').addClass('animated flash'); 

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Shortcut menu
	 * Description : GoTo url select menu.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Richard	 
	**/
	
	$('.shortcut-menu').change(function(e){
		if($(this).val().length > 4){
			location.href = $(this).val();
		}
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Table toggle
	 * Description : Show/hide collapsable content in a table
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Richard	 
	**/
	
	$('body').on(clickEvent, '.table-toggle-tr',function(e){		
		/*
		// uncomment if you only want one tr open
		$(this).parents('table').find('.table-collapsible').hide();
		*/
		$(this).parents('tr').next('.table-collapsible, .table-collapsible-open').slideToggle(200);
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle chat
	 * Description : Toggle hidden content inide the chat.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.ext-dropdown-chat-info-toggle').on(clickEvent,function(e){
		var chat = $(this).parents('.ext-dropdown-chat').find('.ext-dropdown-chat-info');
		if(chat.is(':hidden')){
			chat.show();
		}else{
			chat.hide();
		}
		
		e.preventDefault();
	});
		
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Scroll to top
	 * Description : Animated scroll to the top.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.hr-totop span',function(e){
		$('html, body').animate({scrollTop:0}, 'slow');
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Pull-center class
	 * Description : Adding option to center the dropdowns with the 
	 *               .pull-center class. Notice that we added a resize event
	                 this to make it work with the megamenu responsive code.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	 
	function centerClass(){
		$('.pull-center.dropdown-menu').each(function(){
			var width = $(this).outerWidth() /2;
			$(this).css({marginRight:'-'+width+'px'});
		});
	}
	
	centerClass();
	
	$(window).resize(function(){
		centerClass();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Collapsable profile
	 * Description : Show/hide the colapsable profile menu.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '#sidebar-profile-trigger', function(e){
		var listBox = $(this).next('.sidebar-profile-list');
		if(listBox.is(':hidden')){
			listBox.slideDown();
		}else{
			listBox.slideUp();
		}
		switchClass($(this).children(), 'fa-caret-down', 'fa-caret-left', 0);
		
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Cmanager
	 * Description : Several code pieces used for the cmanager.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	// hide/show the sidebar
	$('body').on(clickEvent, '#cmanager-sidebar-trigger', function(e){
		var gall = $(this).parents('.cmanager');
		if(gall.hasClass('cmanager-sidebar-hide')){
			gall.removeClass('cmanager-sidebar-hide');
		}else{
			gall.addClass('cmanager-sidebar-hide');
		}
		switchClass($(this).children(), 'fa-caret-left', 'fa-caret-right', 0);
			
		e.preventDefault();
	});
	
	// show the upload part
	$('body').on(clickEvent, '.cmanager-addnew-trigger', function(e){
		var gall = $(this).parents('.cmanager');
		if(gall.hasClass('cmanager-addnew-show')){
			gall.removeClass('cmanager-addnew-show');
		}else{
			gall.addClass('cmanager-addnew-show');
		}
		e.preventDefault();
	});
	
    // disable/enable text input by an checkbox(upload part)
	$('body').on(clickEvent, '#cmanager-enable-name-trigger', function(e){
		if($(this).is(':checked')){
			$(this).parent().prev('input').attr('disabled', 'disabled');
		}else{
			$(this).parent().prev('input').removeAttr('disabled');
		}
	});
	
	// show/hide a checkbox per image
	$('body').on(clickEvent, '#cmanager-check-trigger', function(e){	
		var gall   = $(this).parents('.cmanager');
		var actTab = gall.find('.cmanager-window').find('.tab-pane.active');
		if(actTab.find('.cmanager-list-thumb').length){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				actTab.removeClass('cmanager-ctrls-show').find('[type=checkbox]').attr('checked', false);
			}else{
				$(this).addClass('active');
				actTab.addClass('cmanager-ctrls-show');
			}
		}
		
		e.preventDefault();
	});
	
	// set grid view
	$('body').on(clickEvent, '#cmanager-grid-trigger', function(e){	
		$(this).parents('.cmanager').addClass('cmanager-grid-view');
		$('#cmanager-list-trigger').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});	
	
	// set list view
	$('body').on(clickEvent, '#cmanager-list-trigger', function(e){
		$(this).parents('.cmanager').removeClass('cmanager-grid-view');
		$('#cmanager-grid-trigger').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	
	// show/hide a checkbox per image
	$('body').on(clickEvent, '#cmanager-delete-trigger', function(e){	
		$(this)
		.parents('.cmanager')
		.find('.cmanager-window')
		.find('.tab-pane.active')
		.find(':checked')
		.parents('li')
		.fadeOut(500,function(){	
			$(this).remove();
		});
		
		e.preventDefault();
	});	

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Portlets
	 * Description : Toggle content portlets
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	// hide/show the sidebar
	$('body').on(clickEvent, '.portlet-header .btn', function(e){
		if($(this).children().hasClass('fa-minus')){
			$(this).parents('.portlet').find('.portlet-content').slideUp(200,function(){
				$(this).parents('.portlet').addClass('portlet-closed');
			});
		}else{
			$(this).parents('.portlet').removeClass('portlet-closed').find('.portlet-content').slideDown(200);
		}
		switchClass($(this).children(), 'fa-plus', 'fa-minus', 0);
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Toggle sidebar planning
	 * Description : Minimize planning sidebar
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/

	$('body').on(clickEvent, '#planning-sidebar-trigger', function(e){
		if($(this).children().hasClass('fa-caret-left')){
			$(this).parents('.planning-timeline').addClass('planning-sidebar-close');	
		}else{
			$(this).parents('.planning-timeline').removeClass('planning-sidebar-close');		
		}		
		switchClass($(this).children(), 'fa-caret-right', 'fa-caret-left', 0);
		
		// reset scrollbar
		$('.scrollbar-x').tinyscrollbar_update('top');// relative is buggy!
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Draggable planning items
	 * Description : Allow planning bars to be dragged
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core/ jQueryUI
	 * Developer   : Mark	 
	**/
	
	$(".planning-timebar.draggable").draggable({
		axis: 'x',
		grid: [ 25 ],//100 for a full block
		handle: '',
		cursor: 'move',
		containment: "parent",
		start: function(){},
		stop: function(){}
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : FAQ
	 * Description : Toggle content faqs
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.faq-group > header', function(e){
		 var content = $(this).next('.faq-content');
		 if(content.is(':hidden')){
			 content.slideDown();
		 }else{
			 content.slideUp();
		 }
		 switchClass($(this).children('.fa'), 'fa-plus', 'fa-minus', 0);
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Delete comments
	 * Description : Delete a comment(inc reply)
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.comment-delete', function(e){
		$('#reply-form').hide().insertBefore('.comments > ul');
		$(this).closest('.comment').slideUp(400,function(){
			$(this).remove();
		});
		
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle comments
	 * Description : Toggle comments from full to minimal
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.comment-toggle', function(e){
		var comment = $(this).parents('.comment-content').children('.comment-msg, .comment-actions');
		if($(this).children().hasClass('fa-minus')){
			comment.slideUp(100);
		}else{
			comment.slideDown(100);
		}
		switchClass($(this).children(), 'fa-plus', 'fa-minus', 0);
		
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Show comments reply form
	 * Description : Show the comments reply form under each comment
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.comment-reply', function(e){
		$('#reply-form')
		.hide()
		.insertAfter($(this).closest('.comment-content'))
		.find('textarea')
		.val('')
		.end()
		.show();
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle comments
	 * Description : Show full or mini comments
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '#toggle-comments', function(e){
		var trigger  = $(this).children();
		var comments = $('.comment-msg, .comment-actions');
		
		if($(this).children().hasClass('fa-minus')){
			comments.slideUp(400);
		}else{
			comments.slideDown(400);
		}
		switchClass($('.comment-toggle').children(), 'fa-plus', 'fa-minus', 0);
		
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Close comments reply form
	 * Description : Hide the comments reply form.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '#cmanager-check-trigger', function(e){
		$('#reply-form').hide();
		e.preventDefault();
	});
		
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Vote comments
	 * Description : Basic vote up/down comments
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.vote-up, .vote-down',function(e){
		var span = $(this).parent().children('.vote-result').children();
		var val  = span.text();
		if($(e.target).is('.vote-up * ')){
			span.text(parseInt(val) + 1);
		}else if($(e.target).is('.vote-down *')){
			if(val != 0){
				span.text(parseInt(val) - 1);
			}
		}
		
		//run your ajax code here...
			
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Clear value input
	 * Description : HReset the text input back to default
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.clear-input').on(clickEvent,function(e){
		$(this).parents('.input-group').find('.form-control').val('');
		
		e.preventDefault();
	});	
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Flag replace
	 * Description : Switch between flags(dropdown)
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.ext-flags a').on(clickEvent,function(e){
		// notice we change the icon size by using javascript replace
		var img = $(this).children('img').attr('src').replace('24','16');
		$(this).parents('.dropdown, .dropup').children('.dropdown-toggle').children('img').attr('src',img);
		$(this).parents('ul').find('.active').removeClass('active');
		$(this).parent('li').addClass('active');
		
		e.preventDefault();
	});
	
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Active icon
	 * Description : Set an active icon(action indicator)(dropdown)
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('.ext-active-icon a').on(clickEvent,function(e){	
		$(this).parents('ul').find('.fa-check').remove();
		$(this).prepend('<i class="fa fa-check"></i>');
		$(this).parents('ul').find('.active').removeClass('active');
		$(this).parent('li').addClass('active');
		
		e.preventDefault();
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Lightbox indicator
	 * Description : Ads an indicator to every lightbox item
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core 
	 * Developer   : Mark	 
	**/
	
	$('.lightbox, .lightbox-group-1').append('<span class="lightbox-indicator"><span><i class="fa fa-search"></i></span></span>');

	$('.lightbox, .lightbox-group-1').on('mouseenter',this, function(){
		$(this).addClass('relative').children('.lightbox-indicator').stop(true,true).fadeTo(200,0.8);		
	});
	
	$('.lightbox, .lightbox-group-1').on('mouseleave', this, function(){
		$(this).addClass('relative').children('.lightbox-indicator').stop(true,true).fadeTo(200,0.0);		
	});
		
	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Toggle widgets
	 * Description : Basic toggle content widgets
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core 
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.toggle-widget',function(e){
		var widget = $(this).parents('.widget').children('div');
		if(widget.is(':hidden')){
			widget.slideDown();	
		}else{
			widget.slideUp();
		}
		switchClass($(this).children(), 'fa-plus', 'fa-minus', 0);
		
		e.preventDefault();
	});	

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Flyout search
	 * Description : Show/hide a hidden search field
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.search-flyout .btn',function(e){
		var form = $(this).parent();
		if(form.hasClass('open')){
			form.removeClass('open').stop(true,true).animate({width: '35px'},200);
		}else{
			form.addClass('open').stop(true,true).animate({width: '200px'},200);
		}
		
		e.preventDefault();
	});	

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Dailpad
	 * Description : Add values to a input field by pressing a button
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Mark	 
	**/
	
	$('body').on(clickEvent, '.dialpad-key',function(e){
		var input  = $(this).parents('.dialpad').find('.form-control');
		var newVal = $(this).children('strong').text();
		var extVal = input.val();
		input.val(extVal+''+newVal);
		
		e.preventDefault();	
	});
	// reset display
	$('.dialpad-display a').click(function(e){
		$(this).prev().val('');
		
		e.preventDefault();	
	});

	/* --------------------------------------------------------------------- */

	/**
	 * Name        : Project active class
	 * Description : Add a class to the project once it's set to 'finished'
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : jQuery core
	 * Developer   : Richard	 
	**/
	
	$('body').on(clickEvent, '.check-project',function(e){
		if($(this).is(':checked')){
			$(this).parents('.project-block').addClass('project-done');
		}else{
			$(this).parents('.project-block').removeClass('project-done');
		}
	});

	/* --------------------------------------------------------------------- */
	
	/**
	 * Name        : Avoid console errors
	 * Description : Avoid console errors in browsers that lack a console.
	 * Url         :
	 * Version     : 1.0	
	 * Updated     :
	 * Dependency  : 
	 * Developer   : Mark	 
	**/
	
	if (!(window.console && console.log)) {
		(function() {
			var noop = function() {};
			var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
			var length = methods.length;
			var console = window.console = {};
			while (length--) {
				console[methods[length]] = noop;
			}
		}());
	}

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
	
	// This plugin is called at the top of the plugin.js file,
	// here it will stop as this is the last js code.
	$.nanoGress.end({
		delay: 2000,
		onEnd: function(){}
	});		
});
$(window).resize(function(){ });

</script>