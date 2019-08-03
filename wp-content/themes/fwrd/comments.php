<?php

/**
 * @from WordPress TwentyThirteen
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

<?php	if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php
			printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'fwrd' ),
				number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
		?></h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 60,
				) );
			?>
		</ol><!-- .comment-list -->

<?php		if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : ?>
		<nav class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text section-heading"><?php esc_html_e( 'Comment navigation', 'fwrd' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'fwrd' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'fwrd' ) ); ?></div>
		</nav><!-- .comment-navigation -->
<?php		endif; // Check for comment navigation ?>

<?php		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'fwrd' ); ?></p>
<?php		endif; ?>

<?php	endif; // have_comments() ?>
<?php
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	
	$args = array(
		'id_form'           => 'commentform',
		'id_submit'         => 'submit',
		'title_reply'       => esc_html__( 'Leave a Reply', 'fwrd'),
		'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'fwrd'),
		'cancel_reply_link' => esc_html__( 'Cancel Reply', 'fwrd'),
		'label_submit'      => esc_html__( 'Post Comment', 'fwrd'),

		'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' . esc_html__('Comment', 'fwrd') . '</textarea></p>',

		'must_log_in' => '<p class="must-log-in">' .
			wp_kses( sprintf(
			  __( 'You must be <a href="%s">logged in</a> to post a comment.', 'fwrd' ),
			  esc_url(wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) )
			),iron_get_allowed_html()) . '</p>',

		'logged_in_as' => '<p class="logged-in-as">' .
			wp_kses( sprintf(
			__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'fwrd' ),
			  admin_url( 'profile.php' ),
			  $user_identity,
			  esc_url(wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ))
			),iron_get_allowed_html()) . '</p>',

		'comment_notes_before' => '<p class="comment-notes">' .
			esc_html__( 'Your email address will not be published.', 'fwrd') . ( $req ? " " . esc_html__('Required fields are marked *.', 'fwrd') : ''  ) .
			'</p>',

		'comment_notes_after' => '<p class="form-allowed-tags">' .
			wp_kses( sprintf(
			  __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'fwrd' ),
			  ' <code>' . allowed_tags() . '</code>'
			),iron_get_allowed_html()) . '</p>',

		'fields' => apply_filters( 'comment_form_default_fields', array(

			'author' =>
			  '<p class="comment-form-author">' .
			  '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			  '" size="30"' . $aria_req . ' placeholder="'. esc_html__( 'Name', 'fwrd' ) . ( $req ? ' *' : '' ) .'"/></p>',

			'email' =>
			  '<p class="comment-form-email">' .
			  '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			  '" size="30"' . $aria_req . ' placeholder="' . esc_html__( 'Email', 'fwrd' ) . ( $req ? ' *' : '' ) . '" /></p>'
			)
		  ),
		);
?>
	<?php comment_form($args); ?>

</div>