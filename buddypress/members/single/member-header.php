<?php

/**
 * BuddyPress - User Header
 *
 * @package Boss
 */

?>

<?php do_action( 'bp_before_member_header' ); ?>

<?php
	//output cover photo.
	echo buddyboss_cover_photo("user",bp_displayed_user_id());
?>

<?php $cover_class = 'big'; if(esc_attr( get_option( 'boss_cover_profile_size' ) ) == 200 ) { $cover_class = 'small'; } ?>

<div id="item-header-cover" class="table <?php echo $cover_class; ?>">
    
    <div class="table-cell">
        
        <div class="table cover-content">
            
            <div class="table-cell">
                <div id="item-header-avatar">
                    <a href="<?php bp_displayed_user_link(); ?>">

                        <?php bp_displayed_user_avatar( 'type=full' ); ?>

                    </a>
                </div><!-- #item-header-avatar -->

                <div id="item-header-content">
                    <div class="basic">
                        <h1><?php echo bp_get_displayed_user_fullname(); ?></h1><span class="sep"><?php _e(', ','social-learner'); ?></span>
                        <h2 class="user-nicename">@<?php bp_displayed_user_username(); ?></h2>
						<?php 
						$address_field = get_option( 'boss_misc_profile_field_address' );
						if( $address_field ){
							$address = bp_get_profile_field_data( array( 'field'=>$address_field ) );
							if( $address ){
								?>
								<span class="location"><?php echo stripslashes( $address );?></span>
								<?php 
							}
						}
						?>
                    </div>
                    <!-- Socials -->
                    <div class="btn-group social">

                    <?php foreach(buddyboss_get_user_social_array() as $social => $name):
                    $url = buddyboss_get_user_social(bp_displayed_user_id() , $social );
                    ?>

                    <?php if(!empty($url)): ?>			
                    <a class="btn" href="<?php echo $url; ?>" title="<?php echo esc_attr($name); ?>"><i class="alt-social-icon alt-<?php echo $social; ?>"></i> </a>
                    <?php endif; ?>

                    <?php endforeach; ?>
			
                    </div>
		    
                    <?php do_action( 'bp_before_member_header_meta' ); ?>    

                </div><!-- #item-header-content -->
            </div>
            
            <div class="table-cell">
		
                <?php
                $showing = null;
                //if bp-followers activated then show it.
                if(function_exists("bp_follow_add_follow_button")) {
                    $showing = "follows";
                    $followers  = bp_follow_total_follow_counts(array("user_id"=>bp_displayed_user_id()));
		    
		        } elseif (function_exists("bp_add_friend_button")) {
                    $showing = "friends";
                }

                ?>
		
                <div id="item-statistics">
                    <div class="numbers">
                    
                         <?php  if($GLOBALS['badgeos']): ?>
                         <span>
                            <p><?php $points = badgeos_get_users_points(bp_displayed_user_id()); echo number_format($points); ?></p>
                            <p><?php printf( _n( 'Point', 'Points', $points, 'social-learner' ) ); ?></p>
                         </span>
                         <?php  endif; ?>

                        <?php  if($showing == "follows"): ?>
			            <span>
                            <p><?php echo (int) $followers["following"]; ?></p>
                            <p><?php _e("Following","boss"); ?></p>
                        </span>
                        <span>
                            <p><?php echo (int) $followers["followers"]; ?></p>
                            <p><?php _e("Followers","boss"); ?></p>
                        </span>
                        <?php  endif; ?>

                         <?php  if($showing == "friends"): ?>
                         <span>
                            <p><?php echo (int) friends_get_total_friend_count(); ?></p>
                            <p><?php _e("Friends","boss"); ?></p>
                         </span>
                         <?php  endif; ?>
			
                    </div>
                    
                    <div id="item-buttons" class="profile">
                       
                        <?php 
                            if($showing == "follows"){ 
                                remove_action( 'bp_member_header_actions', 'bp_follow_add_profile_follow_button' );
                            }elseif($showing == "friends"){ 
                                if(!bp_is_friend(bp_displayed_user_id())) {
                                    remove_action( 'bp_member_header_actions', 'bp_add_friend_button', 5 ); 
                                } elseif(bp_is_active( 'messages' )) {
                                    remove_action( 'bp_member_header_actions', 'bp_send_private_message_button',  20 );
                                } else {
                                    remove_action( 'bp_member_header_actions', 'bp_send_public_message_button',  20 );
                                } 
                            }else{
                                remove_action( 'bp_member_header_actions', 'bp_send_public_message_button',  20 );
                            } 
                        ?>
                        
                        <?php
                        ob_start();
                        do_action( 'bp_member_header_actions' );
                        $action_output = ob_get_contents();
                        ob_end_clean();
                        ?>

                        <div id="main-button" class="<?php if(!empty($action_output)) { echo 'primary-btn'; }?>">
                        <?php 
                            if($showing == "follows") {
                                bp_follow_add_follow_button();
                            }elseif($showing == "friends") {
                                if(!bp_is_friend(bp_displayed_user_id())) {
                                    bp_add_friend_button();
                                } elseif(bp_is_active( 'messages' )) {
                                    bp_send_private_message_button();
                                } else {
                                    bp_send_public_message_button();
                                } 
                            }else {
                                bp_send_public_message_button();
                            } 
                        ?>
                        </div>
			
                        <?php
                        if(!empty($action_output)): //only show if output exists	
                        ?>
			
                        <!-- more items -->
                        <span class="single-member-more-actions">
                            <button class="more-items-btn btn"><i class="fa fa-ellipsis-h"></i></button>

                            <!--popup-->
                            <div class="pop">
                                <div class="inner">
                                   <?php echo $action_output; ?>
                                </div>
                            </div>
                        </span>
			
			             <?php endif; ?>
			
                    </div><!-- #item-buttons -->
                    
                </div><!-- #item-statistics -->
                
            </div>
            
        </div>

    </div>
    <?php boss_edu_profile_achievements(); ?>
    
</div><!-- #item-header-cover -->


<?php
/***
 * If you'd like to show specific profile fields here use:
 * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
 */
 do_action( 'bp_profile_header_meta' );

 ?>

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>
