<?php

/**
 * Template Name: BuddyPress - Activity Directory
 *
 * @package BuddyPress
 * @subpackage Theme
 */

get_header( 'buddypress' ); ?>

<?php do_action( 'bp_before_directory_activity_page' ); ?>
<div id="content">
	<div class="padder">
		<?php do_action( 'bp_before_directory_activity' ); ?>

		<div id="buddypress">

			<header class="activity-header page-header">
				<h1 class="entry-title main-title"><?php buddyboss_page_title(); ?></h1>
			</header><!-- .page-header -->

			<?php if ( !is_user_logged_in() ) : ?>

				<h3><?php _e( 'Site Activity', 'buddypress' ); ?></h3>

			<?php endif; ?>

			<?php do_action( 'bp_before_directory_activity_content' ); ?>

			<?php if ( is_user_logged_in() ) : ?>

			

            <div style="clear:both; border:2px solid #41759C; background-color:#ddd; padding:5px 20px; margin:40px 70px 20px; border-radius:4px; font-size:14px;" class="bp-template-notice error">
				
				<h4 style="margin-bottom:10px;">Datenschutzhinweis:</h4>
				Bitte beachte, dass Beiträge, die du auf dieser Pinnwand schreibst oder auf die du antwortest auch über Suchmaschinen auf deiner Profilseite zusammen mit den Antworten gefunden werden können, sofern du die Gruppe der Leseberechtigten nicht einschränkst. 
				Die Pinnwand eignet sich nicht, um <b><a href="
					<?php 
						echo bp_custom_get_send_private_message_link(0,'','');
						?>">
					Private Nachrichten</a></b> zu senden.
				
			</div>

				<?php bp_get_template_part( 'activity/post-form' ); ?>

			<?php endif; ?>

			<?php do_action( 'template_notices' ); ?>

			<div class="item-list-tabs activity-type-tabs" role="navigation">
				<div class="choosen-wrap"><span class="selected-tab"></span></div>
				<ul>
					<?php do_action( 'bp_before_activity_type_tab_all' ); ?>

					<li class="selected" id="activity-all"><a href="<?php bp_activity_directory_permalink(); ?>" title="<?php esc_attr_e( 'The public activity for everyone on this site.', 'boss' ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'boss' ), bp_get_total_member_count() ); ?></a></li>

					<?php if ( is_user_logged_in() ) : ?>

						<?php do_action( 'bp_before_activity_type_tab_friends' ); ?>

						<?php if ( bp_is_active( 'friends' ) ) : ?>

							<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

								<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of my friends only.', 'boss' ); ?>"><?php printf( __( 'My Friends <span>%s</span>', 'boss' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

							<?php endif; ?>

						<?php endif; ?>

						<?php do_action( 'bp_before_activity_type_tab_groups' ); ?>

						<?php if ( bp_is_active( 'groups' ) ) : ?>

							<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

								<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of groups I am a member of.', 'boss' ); ?>"><?php printf( __( 'My Groups <span>%s</span>', 'boss' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

							<?php endif; ?>

						<?php endif; ?>

						<?php do_action( 'bp_before_activity_type_tab_favorites' ); ?>

						<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>

							<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>" title="<?php esc_attr_e( "The activity I've marked as a favorite.", 'boss' ); ?>"><?php printf( __( 'My Favorites <span>%s</span>', 'boss' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

						<?php endif; ?>

						<?php if ( bp_activity_do_mentions() ) : ?>

							<?php do_action( 'bp_before_activity_type_tab_mentions' ); ?>

							<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="<?php esc_attr_e( 'Activity that I have been mentioned in.', 'boss' ); ?>"><?php _e( 'Mentions', 'boss' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'boss' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>

						<?php endif; ?>

					<?php endif; ?>

					<?php do_action( 'bp_activity_type_tabs' ); ?>

					<li class="feed"><a href="<?php bp_sitewide_activity_feed_link(); ?>" title="<?php esc_attr_e( 'RSS Feed', 'boss' ); ?>"><?php _e( 'RSS', 'boss' ); ?></a></li>

					<?php do_action( 'bp_activity_syndication_options' ); ?>

				</ul>
			</div><!-- .item-list-tabs -->
			<div class="no-ajax no-hidden" id="subnav" role="navigation">
				<ul class="no-hidden">
					<?php

					/**
					 * Fires inside the syndication options list, after the RSS option.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_group_activity_syndication_options' ); ?>

					<li id="activity-filter-select" style="float: right; z-index: 10000; padding-right: 70px;">

						<label for="activity-filter-by"><?php _e( 'Show:', 'boss' ); ?></label>
						<select id="activity-filter-by" class="activity-filter-by SumoUnder" multiple="multiple" >
							<option value="-1"><?php _e( 'Everything', 'boss' ); ?></option>
							<option value="activity_update"><?php _e( 'Updates', 'boss' ); ?></option>

							<?php if ( bp_is_active( 'blogs' ) ) : ?>

								<option value="new_blog_post"><?php _e( 'Posts', 'boss' ); ?></option>
								<option value="new_blog_comment"><?php _e( 'Comments', 'boss' ); ?></option>

							<?php endif; ?>

							<?php if ( bp_is_active( 'forums' ) ) : ?>

								<option value="new_forum_topic"><?php _e( 'Forum Topics', 'boss' ); ?></option>
								<option value="new_forum_post"><?php _e( 'Forum Replies', 'boss' ); ?></option>

							<?php endif; ?>

							<?php if ( bp_is_active( 'groups' ) ) : ?>

								<option value="created_group"><?php _e( 'New Groups', 'boss' ); ?></option>
								<option value="joined_group"><?php _e( 'Group Memberships', 'boss' ); ?></option>

							<?php endif; ?>

							<?php if ( bp_is_active( 'friends' ) ) : ?>

								<option value="friendship_accepted,friendship_created"><?php _e( 'Friendships', 'boss' ); ?></option>

							<?php endif; ?>

							<option value="new_member"><?php _e( 'New Members', 'boss' ); ?></option>

							<?php do_action( 'bp_activity_filter_options' ); ?>

						</select>
					</li>
				</ul>
			</div>


			
			<?php do_action( 'bp_before_directory_activity_list' ); ?>

			<div class="activity" role="main">

				<?php bp_get_template_part( 'activity/activity-loop' ); ?>

			</div><!-- .activity -->

			<?php do_action( 'bp_after_directory_activity_list' ); ?>

			<?php do_action( 'bp_directory_activity_content' ); ?>

			<?php do_action( 'bp_after_directory_activity_content' ); ?>

			<?php do_action( 'bp_after_directory_activity' ); ?>


		</div>
	</div><!-- .padder -->
</div><!-- #content -->