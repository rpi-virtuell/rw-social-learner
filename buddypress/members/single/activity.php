<?php
/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<style>
    form#whats-new-form {
        position: inherit;
    }
   
</style>
<div class="tabitems no-ajax no-hidden" id="subnav" role="navigation"  style="display:inline;">
    <ul class="no-hidden">

        <?php bp_get_options_nav(); ?>
        <li id="activity-filter-select" style="float: right; z-index: 10000; padding-right: 0px;">
            <label for="activity-filter-by" style="display:block!important;"><?php _e( 'Activity Filter:', 'buddypress' ); ?></label>
            <select id="activity-filter-by" class="activity-filter-by SumoUnder" multiple="multiple" >
                <option value="-1"><?php _e( '&mdash; Everything &mdash;', 'buddypress' ); ?></option>

                <?php bp_activity_show_filters(); ?>

                <?php

                /**
                 * Fires inside the select input for member activity filter options.
                 *
                 * @since 1.2.0
                 */
                do_action( 'bp_member_activity_filter_options' ); ?>

            </select>
        </li>
    </ul>
</div><!-- .item-list-tabs -->

<?php

/**
 * Fires before the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_post_form' ); ?>

<?php

if(bp_current_action()){
	if ( is_user_logged_in() && !bp_is_my_profile() ){
		do_action( 'bp_rw_before_friends_activity_post_form' );
	}
	if ( is_user_logged_in() && bp_is_my_profile() ){
		do_action( 'bp_rw_before_activity_post_form' );
	}
}

if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) ){
	bp_get_template_part( 'activity/post-form' );
}


/**
 * Fires after the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_post_form' );

/**
 * Fires before the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity">

    <?php bp_get_template_part( 'activity/activity-loop' ) ?>

</div><!-- .activity -->

<?php

/**
 * Fires after the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_content' ); ?>
