<?php get_header(); ?>
<div id="inner-wrap">
<div id="site-title"><h1><a href="<?php echo site_url();?>"><?php bloginfo('name'); ?></a></h1></div>
<div id="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); 
		$format = get_post_format();
		if ( false === $format ){$format= 'standard';}
?>

<article class="main <?php echo($format);?>">		
<h2><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
<div class="time"><?php the_time('F j, Y'); ?></div>

<?php if ( has_post_format( 'video' )) { echo the_field('video'); }
else if ( has_post_format( 'image' )) {?> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('frontpage');?></a> <?php } 
else if ( has_post_format( 'gallery' )) {?> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('frontpage');?></a> <?php } else {?> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('frontpage');?></a> <?php the_excerpt();} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title();?>" class="button">Read More...</a>
</article>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.','toybox'); ?></p>
<?php endif; ?>
</div>
<div id="nav-below"><p><?php posts_nav_link('&#8734;','Go Back','Load More Posts'); ?></p></div>
</div>
<?php get_footer(); ?>