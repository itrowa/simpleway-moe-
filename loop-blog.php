<?php
/**
 * 这是一个page template 的loop文件, 创建自TwentyTen的loop-index.php,
 * 本文件中的loop代码会导致调用此页面的输出像
 * 普通的Blog首页一样按时间顺序输出文章.
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @note: this file is copied from loop.php
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> OLDER POSTS', 'twentyten' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'NEWER POSTS <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
	</div><!-- #nav-above -->
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
	/* Start the Loop.
	 *
	 * In Twenty Ten we use the same loop in multiple contexts.
	 * It is broken into three main parts: when we're displaying
	 * posts that are in the gallery category, when we're displaying
	 * posts in the asides category, and finally all other posts.
	 *
	 * Additionally, we sometimes check for whether we are on an
	 * archive page, a search page, etc., allowing for small differences
	 * in the loop on each template without actually duplicating
	 * the rest of the loop that is shared.
	 *
	 * Without further ado, the loop:
	 */ ?>
<?php
	/* 
	 * Show one all site the posts.
	 * The First one will displayed as full content and other are excerpted 
	 * post. Aim is ignore
	 * WP setting "How many post will" be shown at the front page.
	 */

// 必须使用如下代码, 分页功能才能正常工作,若直接使用query_posts, 分页功能会抽
// http://chenshian.com/display-special-category-entries-in-wordpress/ 
$limit = get_option('posts_per_page');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
query_posts('showposts=' . $limit=10 . '&paged=' . $paged);
$db_query -> is_archive = true;
$db_query -> is_home = false;
?>
<?php
	$latest_one = 1;
	while ( have_posts() ) : the_post();
?>

<?php /* How to display front page posts. */ ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="entry-meta">
				<?php twentyten_posted_on(); ?>
			</div><!-- .entry-meta -->

			<div class="entry-content">
			<?php if ($latest_one == 1) : //显示第一篇文章 ?>
				<?php 
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); 
					$latest_one = 0;      //第一篇文章显示完了, 不需要再显示content了
				?> 
			<?php else : ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->

			<div class="entry-utility">
				<?php if ( count( get_the_category() ) ) : ?>
					<span class="cat-links">
						<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ):
				?>
					<span class="tag-links">
						<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'twentyten' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
					</span>
					<span class="meta-sep">|</span>
				<?php endif; ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php //if (  $wp_query->max_num_pages > 1 ) : ?>   <?php //@note: 不知道为啥加上这个条件分页就抽了 ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> OLDER POSTS', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'NEWER POSTS <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php //endif; ?>
