<?php

  /**
  * Function Name: reset Format Post and unset def &$post global - formatPost();
  * @return ( remove def &$post global )
  */
  function formatPost(&$post){
    unset($post->post_title, $post->post_author, $post->post_date, $post->post_modified, $post->post_date_gmt, $post->post_content, $post->comment_status, $post->ping_status, $post->post_password, $post->to_ping, $post->pinged, $post->post_modified_gmt, $post->post_content_filtered, $post->post_parent, $post->guid, $post->post_mime_type);
    unset($post->comment_count, $post->comment_count, $post->filter, $post->menu_order, $post->post_status);

}

 function Generate_Featured_Image( $image_url ){

  // Add Featured Image to Post
    $image_url        = $image_url; // Define the image URL here
    $image_name       = 'wp-item.png';
    $upload_dir       = wp_upload_dir(); // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name ); // Create image file name

    // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    // Create the image  file on the server
    file_put_contents( $file, $image_data );

    // Check image file type
    $wp_filetype = wp_check_filetype( $filename, null );

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file );

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );

    // And finally assign featured image to post
    return $attach_id;
}

function Get_ids_posts_search($searchText) {
  $args = array(
    'post_type'        => 'graphics',
    'post_status'      => 'publish',
    's' =>  $searchText,
    'posts_per_page'   => -1,
  );

  $posts = get_posts($args);

  $ids = [];
  foreach ($posts as $post) {
    $ids[] = $post->ID;
  }

  return $ids;
}

function Get_ids_posts_tag($searchText) {
  $args = array(
    'post_type'        => 'graphics',
    'post_status'      => 'publish',
    'posts_per_page'   => -1,
  );

  $tags = get_terms('graphics-tag', array('name__like' => $searchText));

  if($tags) {
    foreach ($tags as $tag) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'graphics-tag',
          'field'    => "term_id",
          'terms'    => $tag->term_id,
        ),
      );
    }

    $posts = get_posts($args);

    $ids = [];
    foreach ($posts as $post) {
      $ids[] = $post->ID;
    }
    
    return $ids;
  } else {
    return $tags;
  }

}



function Get_icons_search($searchText, $term_id) {

  $args = array(
    'post_type'        => 'graphics',
    'post_status'      => 'publish',
    'posts_per_page'   => -1,
  );

  $args['tax_query'] = array(
    array(
      'taxonomy' => 'graphics-category',
      'field'    => "term_id",
      'terms'    => $term_id,
    ),
  );

  $posts = new WP_Query( $args );

  

  $icons = [];

  if ( $posts->have_posts() ) {
    foreach( $posts->posts as $post ):

      $collocations = get_field('collocation_icons' , $post->ID);

      if($collocations) {
        foreach ($collocations as $key => $value) {
          if($searchText )
          if (strpos($value['file_icon']['title'], $searchText ) !== false) {
            $icons[] = $value['file_icon']['url'];
          }
        }
      }
    endforeach;
  }

  $arrayName = [];

  foreach ($icons as $key => $value) {
    $arrayName[] = array('links' => $value, );
  }

  
  return $arrayName;
}