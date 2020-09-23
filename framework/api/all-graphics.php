<?php
function all_graphics($data){

  $data=$data->get_params('GET');
  extract($data);

  $per_page     = !empty($per_page) ? $per_page : 10;
  $page         = !empty($page) ? $page : true;
  $searchText   = !empty($searchText) ? $searchText : false;
  $category_id  = !empty($category) ? $category : 0;
  $post_id      = !empty($post_id) ? $post_id : false;
  
  if($post_id) {
    
    $args = array(
      'post_type' => 'graphics',
      'p'         => $post_id
    );
    $posts = new WP_Query( $args );


  } elseif($searchText != false) {
      
    if($category_id != 25)  {
      // $posts = SmartSearch($searchText, $category_id, $page, $per_page);
      $SmartSearch = SmartSearch($searchText, $category_id, $page, $per_page);

      


      if ($SmartSearch) {
        $result_posts = $SmartSearch;
        $status_search = false;
      } else {
        $args = array(
          'post_type'       => 'graphics',
          'post_status'     => 'publish',    
          'meta_key'        => 'download_counter',
          'orderby'         => 'meta_value_num',
          'paged'           => $paged,
          'posts_per_page'  => $per_page,
        );

        if ( $category_id != false):
          $args['tax_query'] = array(
            array(
              'taxonomy' => 'graphics-category',
              'field'    => "term_id",
              'terms'    => $category_id,
            ),
          );
        endif;

        $result_posts = new WP_Query( $args );

        $status_search = true;
      }

      $posts = $result_posts;

    }

  } else {

    $args = array(
      'post_type'        => 'graphics',
      'posts_per_page'   => $per_page,
      'paged'            => $page ,
      'post_status'      => 'publish',
    );

    
    if ( $category_id != false):
      $args['tax_query'] = array(
        array(
          'taxonomy' => 'graphics-category',
          'field'    => "term_id",
          'terms'    => $category_id,
        ),
      );
    endif;

    $posts = new WP_Query( $args );

  }


  if($category_id == 25 && $searchText) {

    $results = GetIconsSearch($searchText, $category_id);

    
     if($results && $searchText != '' && $searchText != false) {
      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
        "status" => true,
        "data" => $results,
      ];  
    } else {

      $icons = [];
      $args = array(
        'post_type'       => 'graphics',
        'post_status'     => 'publish',    
        'meta_key'        => 'download_counter',
        'orderby'         => 'meta_value_num',
        'paged'           => $paged,
        'posts_per_page'  => $per_page,
      );

      if ( $category_id != false):
        $args['tax_query'] = array(
          array(
            'taxonomy' => 'graphics-category',
            'field'    => "term_id",
            'terms'    => $category_id,
          ),
        );
      endif;

      $result_posts = new WP_Query( $args );

      if ( $result_posts->have_posts() ) {
        foreach( $result_posts->posts as $post ):

          $collocations = get_field('collocation_icons' , $post->ID);

          if($collocations) {
            foreach ($collocations as $key => $value) {
              $icons[]  = [
                'links'  => $value['file_icon']['url'],
                'id'    => $post->ID,
                'title' => $value['file_icon']['title'],
              ];
            }
          }
        endforeach;
      }


      $result = [
        "success" => false,
        "code" => 404,
        "message" => 'Successfully retrieved',
        'message' => 'icon Not Found',
        "status" => false,
        "data" => $icons,
      ];  
    }

  } else {
    if ( $posts->have_posts() ) {
      foreach( $posts->posts as &$post ):

          $terms =  wp_get_post_terms($post->ID , 'graphics-category');
          if(!empty($terms)){
            foreach ($terms as $term) {
              $post->Category = $term->name;
            }
          }
          
          $file = get_field('file_graphics' , $post->ID);
          $ext = pathinfo($file, PATHINFO_EXTENSION);


          $collocations = get_field('collocation_icons' , $post->ID);

          $link_author = get_field('link_author' , $post->ID);
          $text_author = get_field('text_author' , $post->ID);

          $post_tags = wp_get_post_terms($post->ID, 'graphics-tag');;
          $tags = [];
          if ( $post_tags ) {
            foreach( $post_tags as $tag ) {
              $tags[] =  $tag->name; 
            }
          }

          $post->Type = $ext;
          $post->Id           = $post->ID;
          $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
          $post->Content = get_field('file_graphics' , $post->ID);
          $post->PreviewImage = get_the_post_thumbnail_url($post->ID, 'full' );
          $post->Collocations = $collocations;
          $post->Tags = $tags;
          $post->AuthorLink = $link_author;
          $post->AuthorName = $text_author;


        unset($post->ID, $post->post_name, $post->post_type, $post->post_excerpt);
        formatPost($post);
      endforeach;
        

      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
        "status" => $status_search,
        "data" => $posts->posts,
      ];  
    } else {
      $result = [
        'success' => 'false',
        'code' => 404,
        'message' => 'Graphics Not Found',
      ];
    }
  }



  return $result;

}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'graphics/GetGraphics/',array(
    'methods' => 'GET',
    'callback' => 'all_graphics',
    'args' => array(
      'per_page' => array(
        'validate_callback' => function($param, $request, $key){
          return true;
        }
      ),
      'page' => array(
        'validate_callback' => function($param, $request, $key){
          return is_numeric($param);
        }
      ),
      'category' => array(
        'validate_callback' => function($param, $request, $key){
          return is_numeric($param);
        }
      ),
      'searchText'  => array(
        'validate_callback' => function($param, $request, $key){
          return true;
        }
      ),
      'post_id'  => array(
        'validate_callback' => function($param, $request, $key){
          return true;
        }
      ),
    )
  ));
});
