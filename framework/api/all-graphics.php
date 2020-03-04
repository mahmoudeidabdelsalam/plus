<?php
function all_graphics($data){

  $data=$data->get_params('GET');
  extract($data);

  $per_page = !empty($per_page) ? $per_page : 10;
  $page = !empty($page) ? $page : true;
  $searchText = !empty($searchText) ? $searchText : false;
  
  $args = array(
    'post_type'        => 'graphics',
    'posts_per_page'   => $per_page,
    'paged'            => $page ,
    'post_status'      => 'publish',
  );

  if($searchText != false) {
    $args['s'] = $searchText;
  }

  $posts = new WP_Query( $args );

  
  if ( $posts->have_posts() ) {
    foreach( $posts->posts as &$post ):
      $terms =  wp_get_post_terms($post->ID , 'graphics-category');
      if(!empty($terms)){
        $post->Category= $terms[0]->name;
      }
      $post->Id           = $post->ID;
      $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
      $post->Content = get_field('file_graphics' , $post->ID);
      $post->PreviewImage = get_the_post_thumbnail_url($post->ID, 'full' );
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
  
  return $result;

}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'graphics/GetGraphics/',array(
    'methods' => 'GET',
    'callback' => 'all_graphics',
    'args' => array(
      'per_page' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'page' => array(
        'validate_callback' => function($param,$request,$key){
          return is_numeric($param);
        }
      ),
      'searchText'  => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});
