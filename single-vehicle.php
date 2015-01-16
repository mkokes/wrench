<?php get_header(); ?>
<!DOCTYPE html>

<html>
<head>
    <title></title>
</head>

<body>
    <div id="inner-wrap">
        <div id="content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); 
                    $attachment_id = get_field('picture');
                    $size = "full"; // (thumbnail, medium, large, full or custom size)
                    $image = wp_get_attachment_image_src( $attachment_id, $size );
					$images = get_field('gallery');
                    $type = get_field( 'vehicle-type' ); 
                    $year = get_field( 'year' );
                    $make = get_field('make');
                    $model = get_field('model');
            ?>

            <article class="main vehicle">
                <h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h1>
				<div class="vmaininfo">
                <div class="vimage">
                <a href="<?php echo $image[0]; ?>" rel="lightbox[feat-thumb]">
                	<img src="<?php echo $image[0]; ?>" />
                </a>
                </div>
                <div class="vdesc"><?php the_field('vehicle_description');?></div>
				</div>
				
				<div class="vbotwrap">
				<div id="tabs" class="vselect">
					<ul>
						<li><a href="#tabs-1">Specs</a></li>
						<li><a href="#tabs-2">Posts</a></li>
						<li><a href="#tabs-3">Gallery</a></li>
					</ul>
                <div id="tabs-1" class="vspecs">
                    <h2>Model</h2>
                    <table class="3col">
                        <tr>
                            <th>Year</th>
                            <th>Make</th>
                            <th>Model</th>
                        </tr>
                        <tr>
                            <td><?php the_field('year');?></td>
                            <td><?php the_field('make');?></td>
                            <td><?php the_field('model');?></td>
                        </tr>
                    </table>

                    <h2>Engine</h2>

                    <table>
                        <tr>
                            <th>Engine Manufacturer</th>
                            <th>Engine Model</th>
                        </tr>
                        <tr>
                            <td><?php the_field('enman');?></td>
                            <td><?php the_field('enmod');?></td>
                        </tr>
                    </table><?php if( have_rows('engine_part') ):?>
                    <table>
                        <tr>
                            <th>Engine Part Type</th>
                            <th>Engine Part Name</th>
                            <th>Engine Part Notes</th>
                        </tr><?php while( have_rows('engine_part') ): the_row();
                                // vars
                                $type = get_sub_field('entype');
                                $name = get_sub_field('enname');
                                $url = get_sub_field('enurl');
                                $desc = get_sub_field('endesc');
                                ?>
                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?><?php if( have_rows('fspart') ):?>

                    <h2>Fuel System</h2>

                    <table>
                        <tr>
                            <th>Fuel System Part Type</th>
                            <th>Fuel System Part Name</th>
                            <th>Fuel System Part Notes</th>
                        </tr><?php while( have_rows('fspart') ): the_row();
                                // vars
                                $type = get_sub_field('fstype');
                                $name = get_sub_field('fsname');
                                $url = get_sub_field('fsurl');
                                $desc = get_sub_field('fsdesc');
                                ?>

                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?><?php if( have_rows('expart') ):?>

                    <h2>Exhaust System</h2>

                    <table>
                        <tr>
                            <th>Exhaust Part Type</th>
                            <th>Exhaust Part Name</th>
                            <th>Exhaust Part Notes</th>
                        </tr><?php while( have_rows('expart') ): the_row();
                                // vars
                                $type = get_sub_field('extype');
                                $name = get_sub_field('exname');
                                $url = get_sub_field('exurl');
                                $desc = get_sub_field('exdesc');
                                ?>

                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?><?php if( have_rows('trpart') ):?>

                    <h2>Transmission</h2>

                    <table>
                        <tr>
                            <th>Transmission Part Type</th>
                            <th>Transmission Part Name</th>
                            <th>Transmission Part Notes</th>
                        </tr>
                        <?php while( have_rows('trpart') ): the_row();
                               	$type = get_sub_field('trtype');
                                $name = get_sub_field('trname');
                                $url = get_sub_field('trurl');
                                $desc = get_sub_field('trdesc');?>
                                
                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?><?php if( have_rows('igpart') ):?>

                    <h2>Ignition System</h2>

                    <table>
                        <tr>
                            <th>Ignition System Part Type</th>
                            <th>Ignition System Part Name</th>
                            <th>Ignition System Part Notes</th>
                        </tr><?php while( have_rows('igpart') ): the_row();
                                // vars
                                $type = get_sub_field('igtype');
                                $name = get_sub_field('igname');
                                $url = get_sub_field('igurl');
                                $desc = get_sub_field('igdesc');
                                ?>

                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?><?php if( have_rows('acpart') ):?>

                    <h2>Additional Components</h2>

                    <table>
                        <tr>
                            <th>Part Type</th>
                            <th>Part Name</th>
                            <th>Part Notes</th>
                        </tr><?php while( have_rows('acpart') ): the_row();
                                // vars
                                $type = get_sub_field('actype');
                                $name = get_sub_field('acname');
                                $url = get_sub_field('acurl');
                                $desc = get_sub_field('acdesc');
                                ?>

                        <tr>
                            <td><?php echo $type;?></td><?php if($url):?>
                            <td>
                                <a href="<?php echo $url;?>"><?php echo $name;?></a>
                            </td><?php else:?>
                            <td><?php echo $name;?></td><?php endif;?>
                            <td><?php echo $desc;?></td>
                        </tr><?php endwhile;?>
                    </table><?php endif;?>

                    <div class="twocol cm">
                        <h2>Custom Modifications</h2>

                        <table>
                            <tr>
                                <th>Modification</th>
                                <th>Description</th>
                            </tr><?php if( have_rows('cm') ): while( have_rows('cm') ): the_row();
                                    // vars
                                    $name = get_sub_field('cmname');
                                    $desc = get_sub_field('cmdesc');?>

                            <tr>
                                <td><?php echo $name;?></td>
                                <td><?php echo $desc;?></td>
                            </tr><?php endwhile;endif;?>
                        </table>
                    </div>
                </div>

                <div id="tabs-2" class="vposts">
                    <?php
                    // Find connected pages
                    $connected = new WP_Query( array(
                      'connected_type' => 'posts_to_pages',
                      'connected_items' => get_queried_object(),
                      'nopaging' => true,
                    ) );

                    // Display connected pages
                    if ( $connected->have_posts() ) :
                    ?>

                    <h2>Recent Blog Posts</h2>
                    <ul>
                    	<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <?php 
                    // Prevent weirdness
                    wp_reset_postdata();
                    endif;
                    ?>
                </div>
                <div id="tabs-3" class="vgallery">
					<h2>Gallery</h2>
					<?php 
					
						$image_ids = get_field('gallery', false, false);
						
						$imagecount = count(get_field('gallery'));
						
						if ($imagecount <= 6) { 
							$gcolumns = $imagecount;
							} else {
								$gcolumns = 6;
							} 
 
						$shortcode = '
 
						[gallery columns="'.$gcolumns.'" ids="' . implode(',', $image_ids) . '"]
						';
						echo do_shortcode( $shortcode );
						
						?>
                </div>
                
                </div>
				</div>
				</div>
            </article>
            <?php endwhile; else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.','wrench'); ?></p>
            <?php endif; ?>
        </div>

        <div id="sidebar"></div>
    </div><?php get_footer(); ?>
</body>
</html>
