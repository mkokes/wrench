<?php
 
// Enqueue Stylesheets
function wrench_load_styles(){
	wp_register_style( 'normalize', get_template_directory_uri() . '/css/normalize.css', array(), '2.1.3', 'all' );  
	wp_enqueue_style( 'normalize' );
	wp_register_style( 'mainstyle', get_template_directory_uri() . '/style.css', array(), '0.1', 'all' );  
	wp_enqueue_style( 'mainstyle' );
}
add_action('wp_enqueue_scripts', 'wrench_load_styles');

// Enqueue javascript used by the theme
function wrench_load_js(){
	wp_register_script( 'html5',  get_template_directory_uri() . '/js/html5shiv.js',false,'2.7.1',false );
	wp_enqueue_script('html5');
	wp_register_script( 'wrench',  get_template_directory_uri() . '/js/wrench.js', array('jquery','jquery-ui-core','jquery-ui-tabs','jquery-effects-core','jquery-effects-blind'),'1.0',false );
	wp_enqueue_script('wrench');
	wp_register_script( 'gearing',  get_template_directory_uri() . '/js/gearing.js', array('jquery','jquery-ui-core','jquery-ui-slider','jquery-effects-core','jquery-effects-blind'),'0.1',false );
	wp_enqueue_script('gearing');
}
add_action('wp_enqueue_scripts', 'wrench_load_js');

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails' );

//Add Custom Menu Support
if ( function_exists( 'register_nav_menus' ) ) {
	function wrench_register_my_menus() {
		register_nav_menus(array(
			'main-menu' => __( 'Main Menu','wrench' ),
		));	
	}
		register_nav_menus(array(
			'oc-menu' => __( 'Off Canvas Menu','wrench' ),
		));	
	add_action( 'init', 'wrench_register_my_menus' );
}

//Add Widgetized Areas
if (function_exists('register_sidebar')) {
function wrench_widgets_init() {

		register_sidebar( array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'before_widget' => '<aside class="widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'wrench_widgets_init' );
}
//Change the appearance of the excerpt ending
function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

//Format Support
add_theme_support('post-formats', array(
    'image',
    'gallery',
    'video',
    'status'
));

// Add default posts and comments RSS feed links to head
add_theme_support('automatic-feed-links');

//add a filter to wrap videos
add_filter( 'embed_oembed_html', 'wrench_css_oembed_filter', 10, 4 ) ;
 
function wrench_css_oembed_filter($html, $url, $attr, $post_ID) {
    $return = '<div class="video-wrapper"><figure class="video-container">'.$html.'</figure></div>';
    return $return;
}
//add a filter to wrap galleries
add_shortcode('gallery', 'wrench_gallery_shortcode');   
 
function wrench_gallery_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div class='gallery-wrapper'><div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $link ) && 'file' === $link )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( ! empty( $link ) && 'none' === $link )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n </div>\n";

	return $output;
}

// This theme uses post thumbnails
add_theme_support('post-thumbnails');

// Custom Thumbnail Sizes
    // Create a new image size for YARPP Thumbails
    add_image_size('yarpp-thumbnail', 140, 140, true);
    // Create a new image size for story thumbnails 
    add_image_size('frontpage', 975, 450, true);
    
//Better Page Navigation
add_filter('next_posts_link_attributes', 'posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'posts_link_attributes_2');

function posts_link_attributes_1() {
    return 'class="prev-post"';
}
function posts_link_attributes_2() {
    return 'class="next-post"';
}
//Connecting Custom Post Types to Posts
function wrench_connection_types() {
	p2p_register_connection_type( array(
		'name' => 'posts_to_pages',
		'from' => 'vehicle',
		'to' => 'post'
	) );
}
add_action( 'p2p_init', 'wrench_connection_types' );

//Auto Featured Image if not chosen
function wrench_featured() {
	if (is_single()) {
          global $post;
          $already_has_thumb = has_post_thumbnail($post->ID);
              if (!$already_has_thumb)  {
              $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
                          if ($attached_image) {
                                foreach ($attached_image as $attachment_id => $attachment) {
                                set_post_thumbnail($post->ID, $attachment_id);
                                }
                           }
                        }
					}
      }  //end function
add_action('the_post', 'wrench_featured');
add_action('save_post', 'wrench_featured');
add_action('draft_to_publish', 'wrench_featured');
add_action('new_to_publish', 'wrench_featured');
add_action('pending_to_publish', 'wrench_featured');
add_action('future_to_publish', 'wrench_featured');



//Add Vehicles Post Type
add_action('init', 'cptui_register_my_cpt_vehicle');
function cptui_register_my_cpt_vehicle() {
register_post_type('vehicle', array(
'label' => 'Vehicles',
'description' => 'Vehicle which will be featured on this site',
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'page',
'map_meta_cap' => true,
'hierarchical' => false,
'rewrite' => array('slug' => 'vehicle', 'with_front' => true),
'query_var' => true,
'has_archive' => true,
'supports' => array('title','editor','custom-fields','comments','revisions','thumbnail','author'),
'labels' => array (
  'name' => 'Vehicles',
  'singular_name' => 'vehicle',
  'menu_name' => 'Vehicles',
  'add_new' => 'Add vehicle',
  'add_new_item' => 'Add New vehicle',
  'edit' => 'Edit',
  'edit_item' => 'Edit vehicle',
  'new_item' => 'New vehicle',
  'view' => 'View vehicle',
  'view_item' => 'View vehicle',
  'search_items' => 'Search Vehicles',
  'not_found' => 'No Vehicles Found',
  'not_found_in_trash' => 'No Vehicles Found in Trash',
  'parent' => 'Parent vehicle',
)
) ); }
//include ACF in theme
//define( 'ACF_LITE', true );
include_once('include/advanced-custom-fields/acf.php');
include_once('include/acf-repeater/acf-repeater.php');
include_once('include/acf-gallery/acf-gallery.php');
include_once('include/acf-options-page/acf-options-page.php');

// ACF Add Vehicle and Video Fields
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_vehicle',
		'title' => 'Vehicle',
		'fields' => array (
			array (
				'key' => 'field_52e145b500f0f',
				'label' => 'Type of Vehicle?',
				'name' => 'vehicle-type',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'Moped' => 'Moped',
					'Motorcycle' => 'Motorcycle',
					'Automobile' => 'Automobile',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_52e7ed721765a',
				'label' => 'Vehicle Description',
				'name' => 'vehicle_description',
				'type' => 'textarea',
				'instructions' => 'Say something to describe your vehicle',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_52e174f77216f',
				'label' => 'Default Image',
				'name' => 'picture',
				'type' => 'image',
				'instructions' => 'Please select or upload a default image',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_52e174f77213f',
				'label' => 'Gallery',
				'name' => 'gallery',
				'type' => 'gallery',
				'instructions' => 'Please select or upload images you would like to display in a gallery format',
				'save_format' => 'id',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_52e14d9164b76',
				'label' => 'Year',
				'name' => 'year',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => 'ex: 1978',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_52e14d5464b74',
				'label' => 'Make',
				'name' => 'make',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_52e14d8564b75',
				'label' => 'Model',
				'name' => 'model',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_52e67ad060c72',
				'label' => 'Engine Manufacturer',
				'name' => 'enman',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_52e67a9260c71',
				'label' => 'Engine Model',
				'name' => 'enmod',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_52e67a5560c70',
				'label' => 'Aftermarket Engine Components?',
				'name' => 'aftermarket_engine_components?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e67b1860c73',
				'label' => 'Engine Part',
				'name' => 'enpart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e67a5560c70',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e67d1f60c76',
						'label' => 'Engine Part Type',
						'name' => 'entype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67b5760c74',
						'label' => 'Engine Part Name',
						'name' => 'enname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67b6f60c75',
						'label' => 'Engine Part URL',
						'name' => 'enurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e804939f5a1',
						'label' => 'Engine Part Notes',
						'name' => 'endesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e67a0460c6f',
				'label' => 'Fuel Delivery System Info?',
				'name' => 'fuel_delivery_system?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e67de860c77',
				'label' => 'Fuel System Part',
				'name' => 'fspart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e67a0460c6f',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e67f0a60c7a',
						'label' => 'Fuel System Part Type',
						'name' => 'fstype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67ec160c78',
						'label' => 'Fuel System Part Name',
						'name' => 'fsname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67ee760c79',
						'label' => 'Fuel System URL',
						'name' => 'fsurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e804d89f5a3',
						'label' => 'Fuel System Part Note',
						'name' => 'fsdesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e6763960c69',
				'label' => 'Aftermarket Ignition?',
				'name' => 'aftermarket_ignition?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e6768460c6a',
				'label' => 'Ignition Part',
				'name' => 'inpart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e6763960c69',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e676ee60c6c',
						'label' => 'Ignition Part Type',
						'name' => 'igtype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e6769f60c6b',
						'label' => 'Ignition Part Name',
						'name' => 'igname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'Name of Ignition Component',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e6772d60c6e',
						'label' => 'Ignition Part URL',
						'name' => 'igurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e804bd9f5a2',
						'label' => 'Ignition Part Notes',
						'name' => 'indesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e693831e33e',
				'label' => 'Transmission Components?',
				'name' => 'transmission_components?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e693b71e33f',
				'label' => 'Transmission Part',
				'name' => 'trpart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e693831e33e',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e694881e342',
						'label' => 'Transmission Part Type',
						'name' => 'trtype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e694101e340',
						'label' => 'Transmission Part Name',
						'name' => 'trname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e694321e341',
						'label' => 'Transmission Part URL',
						'name' => 'trurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e8059d9f5a6',
						'label' => 'Transmission Part Notes',
						'name' => 'trdesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e681a160c7f',
				'label' => 'Aftermarket Exhaust?',
				'name' => 'aftermarket_exhaust?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e67fae60c7b',
				'label' => 'Exhaust Part',
				'name' => 'expart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e681a160c7f',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e6803960c7e',
						'label' => 'Exhaust Part Type',
						'name' => 'extype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67fd360c7c',
						'label' => 'Exhaust Part Name',
						'name' => 'exname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e67fe760c7d',
						'label' => 'Exhaust Part URL',
						'name' => 'exurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e805759f5a5',
						'label' => 'Exhaust Part Notes',
						'name' => 'exdesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e6901936466',
				'label' => 'Additional Components?',
				'name' => 'additional_components?',
				'type' => 'radio',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e145b500f0f',
							'operator' => '==',
							'value' => 'Moped',
						),
					),
					'allorany' => 'all',
				),
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e6909036467',
				'label' => 'Additional Components',
				'name' => 'acpart',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e6901936466',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e690ea3646a',
						'label' => 'Additional Component Type',
						'name' => 'actype',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e690c036468',
						'label' => 'Additional Component Name',
						'name' => 'acname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e690d836469',
						'label' => 'Additional Component URL',
						'name' => 'acurl',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => 'http:\\partvendor.com\\parturl',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e804f69f5a4',
						'label' => 'Additional Component Notes',
						'name' => 'acdesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
			array (
				'key' => 'field_52e16ee82a42a',
				'label' => 'Custom Modifications?',
				'name' => 'cm?',
				'type' => 'radio',
				'choices' => array (
					'Yes' => 'Yes',
					'No' => 'No',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'No',
				'layout' => 'vertical',
			),
			array (
				'key' => 'field_52e6982ee77ef',
				'label' => 'Custom Modification',
				'name' => 'cm',
				'type' => 'repeater',
				'conditional_logic' => array (
					'status' => 1,
					'rules' => array (
						array (
							'field' => 'field_52e16ee82a42a',
							'operator' => '==',
							'value' => 'Yes',
						),
					),
					'allorany' => 'all',
				),
				'sub_fields' => array (
					array (
						'key' => 'field_52e6985de77f0',
						'label' => 'Custom Modification',
						'name' => 'cmname',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_52e69889e77f1',
						'label' => 'Custom Modification Description',
						'name' => 'cmdesc',
						'type' => 'textarea',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => '',
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add Row',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'vehicle',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'custom_fields',
				4 => 'discussion',
				5 => 'comments',
				6 => 'slug',
				7 => 'author',
				8 => 'format',
				9 => 'categories',
				10 => 'tags',
				11 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_video',
		'title' => 'Video',
		'fields' => array (
			array (
				'key' => 'field_52cdcb9a78a91',
				'label' => 'Video URL',
				'name' => 'video',
				'type' => 'wysiwyg',
				'instructions' => 'Paste just the link not the embed code to your hosted video',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_format',
					'operator' => '==',
					'value' => 'video',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
// Facebook Open Graph Data
//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_fb_in_head() {
	global $post;
	if ( !is_singular()) //if it is not a post or a page
		return;
        echo '<meta property="fb:admins" content="mkokes"/>';
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:description" content="' . get_the_excerpt() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="' . get_bloginfo("name") . '"/>';
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
		echo "
";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );
?>