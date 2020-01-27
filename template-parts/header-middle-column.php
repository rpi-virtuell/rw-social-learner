<?php
global $rtl;
$boxed	= 'fluid';

if ( $boxed == 'boxed' ) {
    // <!-- Custom menu -->
    $buddypanel_menu = wp_nav_menu( array(
        'theme_location' => 'left-panel-menu',
        'items_wrap'	 => '%3$s',
        'fallback_cb'	 => '',
        'container'		 => false,
        'echo'			 => false,
        'walker'		 => new BuddybossWalker
    ) );
}

$titlebar_menu = wp_nav_menu( array(
    'theme_location' => 'header-menu',
    'items_wrap'	 => '%3$s',
    'fallback_cb'	 => '',
    'echo'			 => false,
    'container'		 => false,
    'walker'		 => new BuddybossWalker
) );
/*
if ( ( isset($buddypanel_menu) && !empty( $buddypanel_menu ) )
        && (isset($titlebar_menu) && !empty( $titlebar_menu ) )
    ):
    ?>
    <!-- Navigation -->
    <div class="header-navigation">
         <div id="header-menu">
             <ul>
             <?php echo $buddypanel_menu.$titlebar_menu; ?>
             </ul>
        </div>
        <a href="#" class="responsive_btn"><i class="fa fa-align-justify"></i></a>
    </div>
<?php else: ?>
    <div class="header-navigation">
        <p></p>
    </div>
<?php endif;*/ ?>

<!-- search form -->
<!--                        <div id="header-search" class="search-form">-->
<!--<div id="titlebar-search">

	<div id="header-search" class="search-form">
		<form role="search" method="get" action="https://gruppen.rpi-virtuell.de/">
			<div class="search-form-inner" style="width: 100px;">
				<label class="screen-reader-text" for="s">Suche nach:</label>
				<input type="text" value="" name="s" class="ui-autocomplete-input" autocomplete="off">
				<button id="search-open" type="submit" class="searchsubmit header-button"><i class="fa fa-search"></i></button>
				
			</div>
		</form>
	</div> 
    

</div>-->
<style>
   .site-header .header-inner .left-col{
	   min-width: 30%; 
	   max-width: 72%; 
   }

	#titlebar-search
	{
		display:flow-root;
		height: 71px;
		border:0;
	}
	
	#titlebar-search .search-wrap{
		width: 100%;
		width: -moz-available;          /* For Mozzila */
		width: -webkit-fill-available;  /* For Chrome */
		width: stretch;                 /* Unprefixed */
		height: 71px;
		border:0;
		display: flow-root;
	}
	#titlebar-search .search-wrap input{
		font-size:200%;
		border:0;
		height:70px;
	}
	#titlebar-search .searchsubmit{
		height:74px;
		width:70px;
		font-size: 24px;
		background-color: #734F89;
		color:#fff;
		border:0;
		border-radius:0;
	}
	
	body.activity:not(.bp-user) .item-list-tabs.activity-type-tabs ul li a span{
		display:none;
	}
<?php	if( ! is_user_logged_in()) : ?>
	.searchform {display:none; }	
<?php	endif; ?>

</style>
<form role="search" method="get" class="searchform" action="https://gruppen.rpi-virtuell.de/">
<div id="titlebar-search" class="header-navigation">
    <div class="search-wrap">
        <label class="screen-reader-text" for="s">Suche nach:</label>
        <input type="text" value="" name="s" class="ui-autocomplete-input" autocomplete="off">
        <button type="submit" class="searchsubmit"><i class="fa fa-search"></i></button>
    </div>
</div>
</form>		

