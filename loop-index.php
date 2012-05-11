<?php
/**
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
	 * 本页面使用两个WordPress Loop。
	 * 详细见http://codex.wordpress.org/The_Loop >> Multiple Loops in Action 部分
	 * 创建一个新的$my_query并且输出第一篇文章，记录下此文章的ID号
	 */
	$latest_post = new WP_Query('posts_per_page=1');
	while ( $latest_post -> have_posts() ) : $latest_post -> the_post();
	$do_not_duplicate = $post -> ID;
?>

<?php /* How to display front page posts. */ ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="entry-meta">
				<?php twentyten_posted_on(); ?>
			</div><!-- .entry-meta -->

			<div class="entry-content">
				<?php 
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); 
					$show_latest = 0;      //第一篇文章显示完了, 不需要再显示content了
				?> 
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

<?php endwhile; //结束$my_query的第一个loop输出 ?>

		<div id="post-extend">
			<h3>阅读更多文章</h3>
			<ul>
<?php
	/*
	 * 这是普通的WordPress Loop，现在我们使用它，并且要排除已经在上一个
	 * Loop中出现了的那篇文章
	 */
	while (have_posts()) : the_post();
					if ($post -> ID == $do_not_duplicate) 
						continue;
?>
			<li><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></li>

		<?php comments_template( '', true ); ?>

<?php endwhile; // 结束WordPress默认Loop. Whew. ?>
		
			</ul>
				<p class="go-more"><a rel="bookmark" title="进入Blog阅读更多文章" href="<?php site_url(); ?>/blog">阅读更多	&rarr;</a></p>

		</div> <!-- #post-extended -->

		<div id="post-navigation">
			<h3>快速搜索文章</h3>
				<?php get_search_form(); ?>
			
			<h3> 按日期查找文章 </h3>
				<select name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
				    <option value=""><?php echo esc_attr( __( 'Select Month' ) ); ?></option> 
					<?php
						/*
 						 * see http://codex.wordpress.org/Template_Tags/wp_get_archives 
						 */
					wp_get_archives( 'type=monthly&format=option&show_post_count=1' ); ?>
				</select>
				
			<h3> 按照分类浏览 </h3>
				<ul>
				<?php
					/*
					 * see http://codex.wordpress.org/Function_Reference/wp_list_categories
					 */
					wp_list_categories('hierarchical=0&title_li=');
				?>
				</ul>
				<p class="meta-words">此外还有个完整版的档案库，<a title="进入档案馆" href="<?php site_url(); ?>/archive">进入	&rarr;</a></p>
		</div>
