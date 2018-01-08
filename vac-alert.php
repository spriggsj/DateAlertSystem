<?php
/*
*Plugin Name: VAC alert Post
*Plugin URI:
*Description: Custom Posts For Valley Animal Center Alert
*Version 1.0
*Author: Jason Spriggs
*/

add_action('init', 'js_alert_post');

function js_alert_post(){
	register_post_type('vac_alert_post', 
		[
		'labels' => [
		'name' => 'Alerts',
		'singular_name' => 'Alert',
		'add_new_item' => 'Add New Alert',
		'edit_item' => 'Edit item',
        'new_item' => 'New item',
        'view_item' => 'View item',
        'search_item' => 'Search Alert',
        'not_found' => 'No item found',
        'not_found_in_trash' => 'No items found in trash',
        'parent_item_colon' => 'parent item'
		],
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-editor-textcolor',
			'rewrite' => array('slug' => 'alert'),
			'publicly_queryable' => true,
			'query_var' => true,
			'supports' => [
				'title','editor', 'custom-fields', //ie  editor etc
				],
				'taxonomies' => ['category'],
		]
	

		);
} 

add_action('admin_init', 'alert_post');

function alert_post(){
	add_meta_box ('alert_posts_meta', 
		'Vac alert Info',
		'vac_alert_post',
		'normal',
		'high'
		);
}

?>
<?php

add_filter('template_include', 'include_vac_alert_function', 1);

function include_vac_alert_function($template_path){ // Checks to see if is custom post
   
    return $template_path; // Return template path

} 

/* Adding plugin specific stylesheet   */
add_action('wp_enqueue_scripts','register_my_scripts');

function register_my_scripts(){
wp_enqueue_style( 'style', plugins_url( 'css/style.css' , __FILE__ ) );
}

function get_post_id_by_meta_key($key) {
  global $wpdb;
  $meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape($key)."'");
  if (is_array($meta) && !empty($meta) && isset($meta[0])) {
    $meta = $meta[0];
    } 
  if (is_object($meta)) {
    return $meta->post_id;
    }
  else {
    return false;
    }
  }

$postID_start = get_post_id_by_meta_key('StartDate');
$postID_end = get_post_id_by_meta_key('EndDate');


$today = date("m/d/Y");
$mystartdate = get_post_meta($postID_start,"StartDate",true);
if ( $mystartdate == "" )
     $mystartdate = "01/09/2018";

$myenddate = get_post_meta($postID_end, 'EndDate', true);
if ( $myenddate == "" )
     $myenddate = "01/09/2018";

$starttime = explode( "/", $mystartdate );
$todaytime = explode ("/", $today );
$endtime = explode ("/", $myenddate );


$testtime1 = mktime( 0, 0, 0, $starttime[0], $starttime[1], $starttime[2] );   
$new_today = mktime( 0, 0, 0, $todaytime[0], $todaytime[1], $todaytime[2] );
$testtime2 = mktime( 0, 0, 0, $endtime[0], $endtime[1], $endtime[2] );
   
  

function vac_loop_shortcode( $atts ) {
    $output = '';
    $vac_loop_atts = shortcode_atts(
      [
        'type' => 'vac_alert_post',
      ], $atts

      );
    $post_type = $vac_loop_atts['type'];
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'order' => 'date',
        'posts_per_page' => 1

      );
      // testing the value of start date and end date

    $the_query = new WP_Query($args);

     while ($the_query->have_posts()) : $the_query->the_post();
      $post_id = get_the_ID();
        $output .= '<div class="container">';
          $output .= '<div class="row">';
            $output .= '<div class="col-xs-12">';

            	$output .= '<div class="custom-alert">';
                	$output .= '<h3>';
                			$output .=  get_the_title();
                	$output .= '</h3>';
                $output .=	"<div class='alert-content'>";
                  $output .= "<p>";
                		$output .= get_the_excerpt();
                	$output .= "</p>";
                $output .= "</div>";

              $output .= "</div>";
            $output .= "</div>";
          $output .= "</div>";
        $output .= "</div>";
      endwhile;

      return $output;
      wp_reset_postdata();

    }

    add_shortcode('vac-loop', 'vac_loop_shortcode');
