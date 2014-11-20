<?php get_header(); ?>
<div id="inner-wrap">
<div id="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); 
		$format = get_post_format();
		if ( false === $format ){$format= 'standard';}
?>

<article class="main <?php if ($format != 'gallery') { echo($format); }?>">		
<h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h1>
<div class="time"><?php the_time('F j, Y'); ?></div>

<div class="postbody">
<?php if ( has_post_format( 'video' )) { echo the_field('video'); }?>
<?php the_content(); ?>
</div>

<div><?php the_tags( 'Tagged: ', ', ' ); ?></div>


                    <?php
                    // Find connected pages
                    $connected = new WP_Query( array(
                      'connected_type' => 'posts_to_pages',
                      'connected_items' => get_queried_object(),
                      'nopaging' => true,
                    ) );

                    // Display connected pages
                    if ( $connected->have_posts() ) :
                    		
                    		$count = $connected->post_count;
							if ($count = 1) {
								$plural = 'false';
							} else  {
								$plural = 'true';
							} 
                    ?>
					<div class="feat-projects">
                    <h3><?php if ($plural = 'true') { echo('Projects');} else {echo('Project');} ?> featured in this post:</h3>
                    <div class="feat-thumbnails-horizontal">
                    	<?php while ( $connected->have_posts() ) : $connected->the_post(); 
	                    			$attachment_id = get_field('picture');
									$size = "thumbnail"; // (thumbnail, medium, large, full or custom size)
									$image = wp_get_attachment_image_src( $attachment_id, $size );
                    	?>
                            <a href="<?php the_permalink(); ?>" class="feat-thumbnail wp-post-image">
	                            <img src="<?php echo $image[0]; ?>" />
	                            <span class="feat-thumbnail-title"><?php the_title();?></span>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </div>
					</div>
                    <?php 
                    // Prevent weirdness
                    wp_reset_postdata();
                    endif;
                    ?>

<?php related_posts(); ?>

</article>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.','wrench'); ?></p>
<?php endif; ?>
</div>
<div id="sidebar">
</div>
</div>

<?php get_footer(); ?>