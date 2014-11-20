<?php get_header(); ?>
<div id="inner-wrap">
<div id="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article class="main page;?>">		
<h1><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h1>
<?php the_content(); ?>
</article>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.','wrench'); ?></p>
<?php endif; ?>
</div>
<div id="sidebar">
</div>
</div>

<?php get_footer(); ?>