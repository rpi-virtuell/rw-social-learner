;( function( $, window, document, undefined ) {
    
    var $window = $(window),
        $document = $(document);
    
    $document.ready(function(){
        
        /*--------------------------------------------------------------------------------------------------------
        1.0 - Header menu
        --------------------------------------------------------------------------------------------------------*/
        
        $.fn.jRMenuMore = function(widthfix) {
            return false;
        }
        
        /**
         * jRMenuMore to allow menu to have a More option for responsiveness
         * Credit to http://blog.sodhanalibrary.com/2014/02/jrmenumore-jquery-plugin-for-responsive.html
         *
         * uses resize.js for better resizing
         *
         **/
        $.fn.jRMenuMore2 = function ( widthfix ) {
            $( this ).each( function () {
                $( this ).addClass( "horizontal-responsive-menu" );
                alignMenu( this );
                var robj = this;

                $( '#right-panel' ).resize( function () {
                    $( robj ).append( $( $( $( robj ).children( "li.hideshow" ) ).children( "ul" ) ).html() );
                    $( robj ).children( "li.hideshow" ).remove();
                    alignMenu( robj );
                } );

                function alignMenu( obj ) {
                    var w = 0;
                    var mw = $( obj ).width() - widthfix;
                    var i = -1;
                    var menuhtml = '';
                    jQuery.each( $( obj ).children(), function () {
                        i++;
                        w += $( this ).outerWidth( true );
                        if ( mw < w ) {
                            menuhtml += $( '<div>' ).append( $( this ).clone() ).html();
                            $( this ).remove();
                        }
                    } );
                    $( obj ).append(
                        '<li class="hideshow">' +
                        '<a href="#"><i class="fa fa-ellipsis-h"></i></a><ul>' +
                        menuhtml + '</ul></li>' );
                    $( obj ).children( "li.hideshow ul" ).css( "top",
                        $( obj ).children( "li.hideshow" ).outerHeight( true ) + "px" );

                    $( obj ).find( "li.hideshow > a" ).click( function ( e ) {
                        e.preventDefault();
                        $( this ).parent( 'li.hideshow' ).children( "ul" ).toggle();
                        $( this ).parent( 'li.hideshow' ).parent( "ul" ).toggleClass( 'open' );
                    } );


                    $( document ).on( 'click', function ( event ) {
                        if ( event.originalEvent && $( event.target )[0].parentNode.parentNode.classList[0] != 'hideshow' && $( event.target )[0].parentNode.classList[0] != 'hideshow' ) {
                            $( 'li.hideshow' ).each( function () {
                                if ( $( this ).parent( "ul" ).hasClass( 'open' ) ) {
                                    $( 'li.hideshow' ).children( "ul" ).hide();
                                    $( 'li.hideshow' ).parent( "ul" ).removeClass( 'open' );
                                }
                            } );

                        }
                    } );

                    if ( $( obj ).find( "li.hideshow" ).find( "li" ).length > 0 ) {
                        $( obj ).find( "li.hideshow" ).show();
                    } else {
                        $( obj ).find( "li.hideshow" ).hide();
                    }
                }
            } );
        }
        
        if(!$('body').hasClass('is-mobile')) {
           $("#item-nav").find("#nav-bar-filter").jRMenuMore2(60);  
           $("#header-menu > ul").jRMenuMore2(120);
        }
  
        /*--------------------------------------------------------------------------------------------------------
        1.1 - Search
        --------------------------------------------------------------------------------------------------------*/
        
        var $search_form = $('#titlebar-search').find('form');
        
        $('#search-open').click(function(e){
            e.preventDefault();
            $search_form.fadeIn();
            setTimeout(function(){
                $search_form.find('#s').focus();
            }, 301);
        });  
        
        $('#search-close').click(function(e){
            e.preventDefault();
            $search_form.fadeOut();
        });
        
        
//        $('#header-menu > ul').fn.func = null;
//
//         $('#header-menu > ul').prototype.func = null;
//        
        
//        $(document).on('click', function(event){
//            if (event.originalEvent && $(event.target)[0].id != 'titlebar-search') {
//                $search_form.fadeOut();
//            }
//        });
        
        $(document).click(function (e)
        {
            var container = $("#titlebar-search");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $search_form.fadeOut();
            }
        });
        
        if($('body').hasClass('left-menu-open')){
            $('.right-col').addClass('hide');
        }

        $('#left-menu-toggle').click(function(e){
            e.preventDefault();
            if($('body').hasClass('left-menu-open')){
                setTimeout(function(){
                    $('.right-col').toggleClass('hide');
                }, 500);    
            } else {
                $('.right-col').toggleClass('hide');
            }
        });
        
        /*--------------------------------------------------------------------------------------------------------
        1.2 - Site title
        --------------------------------------------------------------------------------------------------------*/     
        $(".site-title a, .mobile-site-title").html(function(index, curHTML) {
            if(!$(this).has('img')) {
                curHTML = curHTML.trim();
                var text = curHTML.split(/[\s-]/),
                    newtext = '<span class="colored">' + text.pop() + '</span>';
                return text.join(' ').concat(' ' + newtext);
            }
         }); 
        
    });
}( jQuery, document, window ) );