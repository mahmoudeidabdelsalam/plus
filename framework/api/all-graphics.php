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

      $ids_search = Get_ids_posts_search($searchText);
      $ids_tags   = Get_ids_posts_tag($searchText);

      $results = array_merge($ids_search, $ids_tags);


      if($results) {
        $args = array(
          'post_type'        => 'graphics',
          'post_status'      => 'publish',
          'post__in'         => $results,
          'posts_per_page'   => -1,
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
      } else {
        $args = array(
          'post_type'       => 'graphics',
          'post_status'     => 'publish',
          's'               => $searchText,
        );
      }

      $posts = new WP_Query( $args );
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
    $results = Get_icons_search($searchText, $category_id);
    if($results && $searchText != '' && $searchText != false) {
      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
        "data" => $results,
      ];  
    } else {
      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
        'message' => 'Graphics Not Found',
      ];  
    }
  } else {
    if ( $posts->have_posts() ) {
      foreach( $posts->posts as &$post ):

          $terms =  wp_get_post_terms($post->ID , 'graphics-category');
          if(!empty($terms)){
            $post->Category= $terms[0]->name;
          }
          
          $file = get_field('file_graphics' , $post->ID);
          $ext = pathinfo($file, PATHINFO_EXTENSION);


          $collocations = get_field('collocation_icons' , $post->ID);

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


        unset($post->ID, $post->post_name, $post->post_type, $post->post_excerpt);
        formatPost($post);
      endforeach;
        

      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
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
