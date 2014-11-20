<?php
/*
Template Name: Project Listing
*/
?>

<?php get_header(); ?>
<div id="inner-wrap">
<div id="content">

<?php

// First, initialize how many posts to render per page
$countposts = wp_count_posts('vehicle');
$allposts = $countposts->publish;


// Finally, we'll set the query arguments and instantiate WP_Query
$query_args = array(
  'post_type'  =>  'vehicle',
  'orderby'    =>  'modified',
  'order'      =>  'DESC',
  'showposts' =>	$allposts
);
$vehicle_query = null;
$vehicle_query = new WP_Query ( $query_args );
if( $vehicle_query->have_posts() ) {
  while ($vehicle_query->have_posts()) : $vehicle_query->the_post(); 
		$attachment_id = get_field('picture');
		$size = "full"; // (thumbnail, medium, large, full or custom size)
		$image = wp_get_attachment_image_src( $attachment_id, $size );
?>

<article class="main vehicle">		
<div class="vhalf1">
<a href="<?php the_permalink(); ?>"><img src="<?php echo $image[0]; ?>" /></a> 
</div>

<div class="vhalf2">
<img class="flourish" src="<?php echo get_stylesheet_directory_uri(); ?>/img/top-flourish.png"/>
<h2><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>


                        <ul>
                            <li><?php the_field('year');?></li>
                            <li><?php the_field('make');?></li>
                            <li><?php the_field('model');?></li>
                        </ul>

						<a href="<?php the_permalink(); ?>" class="button">More Info</a>
<img class="flourish" src="<?php echo get_stylesheet_directory_uri(); ?>/img/bot-flourish.png"/>
</div>
</article>



<?php
  endwhile;
}
wp_reset_query();  // Restore global post data stomped by the_post().
?>
</div>
<div id="sidebar">
</div>
<div id="nav-below"><p><?php posts_nav_link('&#8734;','Go Back','Load More Posts'); ?></p></div>
</div>
<?php get_footer(); ?>