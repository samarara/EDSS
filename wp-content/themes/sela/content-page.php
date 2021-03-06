<?php
/**
 * The template used for displaying page content.
 *
 * @package Sela
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<!--hide title on home page-->
	<?php if (is_page('Home')){ ?>
		 
	<?php } 
	else { ?>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->
	<?php } ?>
	
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sela' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', 'sela' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>

</article><!-- #post-## -->
