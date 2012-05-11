<?php
/**
 * Template Name: List All posts(as Blog)
 *
 * Simple Way 的page模板文件，一个页面使用次模板后它会像Blog首页一样输出
 * 每篇文章，而不是此page对应的文章内容
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php
			/* Run the specific loop to output all the posts.
			 */
			 get_template_part( 'loop', 'blog' );
			?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
