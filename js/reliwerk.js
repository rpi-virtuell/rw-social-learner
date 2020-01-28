;( function( $, window, document, undefined ) {
    var $window = $(window),
        $document = $(document);
    
    $document.ready(function(){

		/*******
		 * Fix Layout for BuddyPress Group Menu
		 *******/

		var navbar = jQuery('#item-nav .item-list-tabs ul')[0];
		if(navbar) navbar.id='nav-bar-filter';

		/*******
		* Fix Layout for BuddyPress Group Email Subscription
		*******/

        $('#primary div.group-subscription-div').remove();
		var stat = $('.group-subscription-status').html();
		$('div.group-subscription-div a.group-subscription-options-link').remove();
		$('div.group-subscription-div').html('<hr>E-Mail Benachrichtigung: '+stat);
        $('.group-subscription-options').remove();
		$('div.group-subscription-div').css('display','block');


		/***
		* link course title to course start site
		***/
		
		if($('.widget_course_return a') && $('.course-lessons-widgets h3 a') ){
				
			var courselink = $('.widget_course_return a').attr('href');
			$('.course-lessons-widgets h3 a').attr('href', courselink);
			
		}

		/***
		 *  workaround to show styled radio buttons in chrome
		 ***/
		if($.browser.chrome){
			$('input[type="checkbox"]').parent().css('font-weight', '300');
			$('input[type="radio"]').parent().css('font-weight', '300');
			setTimeout (
				function(){
					$('input[type="checkbox"]').parent().css('font-weight', 'inherit');
					$('input[type="radio"]').parent().css('font-weight', 'inherit');
				}, 500
			);
		}

		//knopf zum registrieren in der Kopfzeile erzeugen
		if($('.register.screen-reader-shortcut').length<1){
			$('.login').parent().append('<a href="https://konto.rpi-virtuell.de/registrieren/" class="register screen-reader-shortcut">Registrieren</a>');
		}

	});
}( jQuery, document, window ) );

/*
 * Added for multiple select on activity pages
 */
jQuery( document ).ready(function($) {

	$('#whats-new-post-in').on('change', function () {

		if($('#whats-new-post-in').val()>0){
			$('#activity-visibility').hide();
		}else{
			$('#activity-visibility').show();
		}


	});

	$('#whats-new-post-in-box span').html('An');

	if ( undefined !== $.cookie('bp-activity-filter') && $('#activity-filter-select').length ) {
		var sel = $.cookie('bp-activity-filter');
		var selArr = sel.split( ',');
		for ( i=0; i < selArr.length; i++ ) {
			$('#activity-filter-by option[value="' + selArr[i] + '"]').prop('selected', true);
		}
	}
	$('#activity-filter-by').SumoSelect( { csvDispCount:2});

});

function ajax_update_privacy_hints_red(slug){
	jQuery.ajax({
		url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
		data: {
			'action': 'rw_update_privacy_hints_red',
			'have_red_privacy_hint' : slug
		},
		success:function(data) {
			// This outputs the result of the ajax request
			console.log(data);
			jQuery('.bp-template-notice.privacy').remove();
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	});
}

