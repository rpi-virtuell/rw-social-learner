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
			$('input[type="radio"]').parent().css('font-weight', '300');
			setTimeout (
				function(){
					$('input[type="radio"]').parent().css('font-weight', 'inherit');
				}, 500
			);
		}


	});
}( jQuery, document, window ) );

/*
 * Added for multiple select on activity pages
 */
jQuery( document ).ready(function() {
	var jq = jQuery;
	if ( undefined !== jq.cookie('bp-activity-filter') && jq('#activity-filter-select').length ) {
		var sel = jq.cookie('bp-activity-filter');
		var selArr = sel.split( ',');
		for ( i=0; i < selArr.length; i++ ) {
			jq('#activity-filter-by option[value="' + selArr[i] + '"]').prop('selected', true);
		}
	}
	jq('.activity-filter-by').SumoSelect( { csvDispCount:2});
});

