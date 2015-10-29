;( function( $, window, document, undefined ) {
    
    var $window = $(window),
        $document = $(document);
    
    $document.ready(function(){
        
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
		
    });
}( jQuery, document, window ) );