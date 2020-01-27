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

<div style="clear:both; border:2px solid #41759C; background-color:#ddd; padding:5px 20px; margin:40px 20px 20px; border-radius:4px; font-size:14px;" class="bp-template-notice error">
	
	<h4 style="margin-bottom:10px;">Datenschutzhinweis</h4>
	Bitte beachte, dass Mitteilungen (Updates), die du hier schreibst oder auf die du antwortest auch über Suchmaschinen zusammen 
	mit deinen Antworten gefunden werden können. Wenn du lieber eine private Nachricht versenden möchtest, klicke auf die 
	<b style="color:#734F89; padding: 0px 3px 10px; font-size:20px; border-radius:4px;">...</b> rechts im Kopf dieser Seite und wähle "<b><a href="
					<?php 
						echo bp_custom_get_send_private_message_link(bp_displayed_user_id(),'','');
						?>">Private Nachricht</a></b>".
	
</div>
<?php

/**
 * Fires before the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_post_form' ); ?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
    bp_get_template_part( 'activity/post-form' );

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
