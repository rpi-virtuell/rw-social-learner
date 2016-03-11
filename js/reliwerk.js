;( function( $, window, document, undefined ) {
    var $window = $(window),
        $document = $(document);
    
    $document.ready(function(){

		/*******
		 * Fix Layout for BuddyPress Group Menu
		 *******/

		var navbar = jQuery('#item-nav .item-list-tabs ul')[0];
		navbar.id='nav-bar-filter';

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
		//Security Question
	//	$('div.security-question-section h4').html('Zur Sicherheit noch ein kleiner Test');
		
		
		//Activity Veröffentlichungsbutton 
		/* im main theme gefixed
		function showActivtyPostButton(){
			
			$('#buddypress form#whats-new-form textarea').css('margin-bottom','-20px');
			$('input#aw-whats-new-submit').val('Veröffentlichen');
			$('div#whats-new-options').show();
		
		}
		
		$('#whats-new-textarea').on('click', showActivtyPostButton);
		$('#whats-new').on('keyup', showActivtyPostButton);
		$('#whats-new').on('change', showActivtyPostButton);
		*/
    });
}( jQuery, document, window ) );