<?php
/**
 * @package WordPress
 * @subpackage Basis_Theme xHTML5
 */

get_header();

	if ( have_posts() ) { ?>
	
		<?php /* If this is a category archive */ if ( is_category() ) { ?>
			<h2><?php _e( 'Archiv f&uuml;r die Kategorie', FB_BASIS_TEXTDOMAIN ); ?> <?php single_cat_title(); ?></h2>
		<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
			<h2><?php _e( 'Tagesarchiv', FB_BASIS_TEXTDOMAIN ); ?> <?php the_time('F jS, Y'); ?></h2>
		<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
			<h2><?php _e( 'Monatsarchiv', FB_BASIS_TEXTDOMAIN ); ?> <?php the_time('F, Y'); ?></h2>
		<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
			<h2><?php _e( 'Jahresarchiv', FB_BASIS_TEXTDOMAIN ); ?> <?php the_time('Y'); ?></h2>
		<?php /* If this is a search */ } elseif ( is_search() ) { ?>
			<h2><?php echo sprintf( __( 'Die Suche ergab %s Artikel', FB_BASIS_TEXTDOMAIN ), $wp_query->found_posts ); ?></h2>
		<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
			<h2><?php _e( 'Archiv zum Autor', FB_BASIS_TEXTDOMAIN ); ?> <?php echo $wp_query->queried_object->display_name; ?></h2>
		<?php /* If this is an tag archive */ } elseif ( is_tag() ) { ?>
			<h2><?php _e( 'Archiv f&uuml;r das Schlagwort', FB_BASIS_TEXTDOMAIN ); ?> <?php single_tag_title(); ?></h2>
		<?php /* If this is a paged archive */ } elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) { ?>
			<h2><?php _e( 'Archiv', FB_BASIS_TEXTDOMAIN ); ?></h2>
		<?php } ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<header>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				</header>
				
				<section class="story">
				<?php if ( is_archive() || is_search() ) { ?>
					<?php the_excerpt() ?>
					
					<p class="textright"><a href="<?php the_permalink() ?>" rel="bookmark"><?php _e( '... weiterlesen &raquo;', FB_BASIS_TEXTDOMAIN ); ?></a></p>
					<footer>
						<p><?php _e( 'Aktualisiert am', FB_BASIS_TEXTDOMAIN ); ?> <time datetime="<?php the_modified_date('Y-m-d'); ?>"><?php the_modified_date(); ?></time> <?php edit_post_link( __( 'Editieren', FB_BASIS_TEXTDOMAIN ),' &middot; ', ''); ?></p>
					</footer>
				<?php } else { ?>
					<?php the_content( the_title( '', '', false ) . ' ' . __( 'weiterlesen &raquo;', FB_BASIS_TEXTDOMAIN ) ); ?>
					<section>
						<?php wp_link_pages(); ?>
					</section>
				<?php } ?>
				</section>
			
			</article>
		
		<?php
		endwhile;
		
		if ( $wp_query->max_num_pages > 1 ) {
			echo '<nav class="meta-nav">' . "\n";
			echo '<h3>' . __( 'Posts Links Navigation', FB_BASIS_TEXTDOMAIN ) . '</h3>' . "\n";
			echo '<p>';
			next_posts_link( __( '<span>&larr;</span> Older posts', FB_BASIS_TEXTDOMAIN ) );
			echo ' &middot; ';
			previous_posts_link( __( 'Newer posts <span>&rarr;</span>', FB_BASIS_TEXTDOMAIN ) );
			echo '</p>' . "\n";
			echo '</nav>' . "\n";
		}
	
	} else {
	?>
		
		<section>
			<p><?php _e( 'Nichts gefunden, was den Suchkriterien entspricht.', FB_BASIS_TEXTDOMAIN ); ?></p>
		</section>
		
	<?php
	} // end else
	?>

<?php get_footer(); ?>