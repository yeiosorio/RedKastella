<?php 
function theme_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
    
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment_wrap">
			<div class="gravatar">
				<?php echo get_avatar($comment,$size='80',$default='http://s.gravatar.com/avatar/e18c83a4de25393a9465e613f15b86e0'); ?>
            </div>
			<div class='comment_content'>
				<footer class="comment_meta">
					<?php printf( '<cite class="comment_author">%s</cite>', get_comment_author_link()); ?><?php edit_comment_link(__('(Edit)', 'wt_front' ),'  ','') ?>
					<span class="comment_time"><a><?php echo get_comment_date(); ?></a></span>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				</footer>
				<div class='comment_text'>
					<?php comment_text() ?>
<?php if ($comment->comment_approved == '0') : ?>
					<span class="unapproved"><?php _e('Your comment is awaiting moderation.', 'wt_front') ?></span>
<?php endif; ?>
				</div>
			</div>
		</article>
<?php
}
?>

<section id="comments" role="complementary"><?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'wt_front'); ?></p>
<?php
		return;
	endif;
	
if ( have_comments() ) : ?>
	<div class="wt_title wt_heading_3"><h3 id="commentsTitle"><span><?php
	printf( _n( 'Comment (%1$s)', 'Comments (%1$s)', get_comments_number()),
	number_format_i18n( get_comments_number() ), '' );
	?></span></h3></div>

	<ul class="commentList">
		<?php
			wp_list_comments( array( 'callback' => 'theme_comments' ) );
		?>
	</ul>


<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<div class="comments_navigation">
		<div class="nav_previous"><?php previous_comments_link(); ?></div>
		<div class="nav_next"><?php next_comments_link(); ?></div>
	</div>
<?php endif; // check for comment navigation ?>


<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
	/*<p class="nocomments"><?php _e( 'Comments are closed.', 'wt_front' ); ?></p>*/
?>
	
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<?php if ( comments_open() ) :// Comment Form ?>

	<div id="respond">
		<div class="wt_title wt_heading_3"><h3><span><?php comment_form_title( __('Leave a Reply', 'wt_front'), __('Leave a Reply to %s', 'wt_front') ); ?></span></h3></div>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment'),wp_login_url( get_permalink() )); ?></p>
<?php else : ?>
   		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" role="form">
        
	<?php if ( is_user_logged_in() ) : ?>
                <p class="logged"><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( get_permalink()  ) )?></p>
    <?php else : ?>	
                <div class="commentform row-fluid">
                   <input type="text" name="author" class="required col-lg-4 col-md-4" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" data-minlength="3" placeholder="Name" />
                    <input type="text" name="email" class="required email col-lg-4 col-md-4" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" placeholder="E-mail" />
                    <input type="text" name="url" class="url col-lg-4 col-md-4" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" placeholder="Website" />
                </div>
    <?php endif; ?>

			<div class="row-fluid">
                <p><textarea class="text_area required" name="comment" id="comment" cols="73" rows="8" tabindex="4" data-minlength="5" placeholder="Message"></textarea></p>
            </div>
			<p>                                
			    <a id="submit" href="#" onclick="jQuery('#commentform').submit();return false;" class="btn btn-primary dark"><span><?php _e('Post Comment', 'wt_front');?></span></a><?php comment_id_fields(); ?>  
                <?php cancel_comment_reply_link('Cancel Reply'); ?>
                <?php do_action('comment_form', $post->ID); ?>
            </p>
            
			<?php wp_enqueue_script( 'wt-validate' ); ?>
            <?php wp_enqueue_script( 'wt-validate-translation' ); ?>
             <script type="text/javascript">
                 jQuery(document).ready(function($) { jQuery("#commentform").validate(); });		
             </script>
             
		</form>
<?php endif; // If registration required and not logged in ?>
	</div><!--/respond-->
<?php endif; ?>
</section>