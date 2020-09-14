<?php 
function get_graphics_search($data){

  $data=$data->get_params('GET');
  extract($data);

  $per_page     = !empty($per_page) ? $per_page : 10;
  $page         = !empty($page)     ? $page     : true;
  $searchText   = !empty($searchText)  ? $searchText : false;
  $category_id  = !empty($category) ? $category : 0;

  if($category_id == 25) {
    
    $results = GetIconsSearch($searchText, $category_id);

     if($results && $searchText != '' && $searchText != false) {
      $result = [
        "success" => true,
        "code" => 200,
        "message" => 'Successfully retrieved',
        "data" => $results,
      ];  
    } else {
      $result = [
        "success" => false,
        "code" => 404,
        "message" => 'Successfully retrieved',
        'message' => 'icon Not Found',
      ];  
    }
  } else {
    $posts = SmartSearch($searchText, $category_id, $page, $per_page);

    if ( $posts) {
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
  register_rest_route('wp/api/' ,'get_graphics/search/',array(
    'methods' => 'GET',
    'callback' => 'get_graphics_search',
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
    )
  ));
});
