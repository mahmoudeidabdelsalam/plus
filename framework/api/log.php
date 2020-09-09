<?php
function log_download($data){

  $data=$data->get_params('POST');
  extract($data);

  $item_id = !empty($item_id) ? $item_id : false;
  $item_title = !empty($item_title) ? $item_title : false;
  $user = !empty($user) ? $user : false;

  $post = get_post($item_id);

  if($item_id == 'unsplash') {
    $title = "unsplash id-" . $item_title;
  } else {
    $title = $post->post_title;
  }
  
  $args = array(
    'post_type' => 'log_download',
    'post_title' => $title,
    'post_status' => 'publish',
  );
  $post_id = wp_insert_post( $args );
  
  if($post_id) {
    update_field( 'field_5f581d9d03166', $item_id, $post_id );
    update_field( 'field_5f58a8690316a', $item_title, $post_id );
    update_field( 'field_5f58a8780316b', $user, $post_id );
  }


  $result = [
    'success' => true,
    'code' => 200,
    'message' => 'log download success',
    'data' => $post_id,
  ];
  return $result;

}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'log/download',array(
    'methods' => 'POST',
    'callback' => 'log_download',
    'args' => array(
      'item_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),     
    )
  ));
});



function get_log_download($data){

  $data=$data->get_params('GET');
  extract($data);

  $item_id = !empty($item_id) ? $item_id : false;
  $per_page     = !empty($per_page) ? $per_page : 10;
  $page         = !empty($page) ? $page : true;

  
  if($item_id ) {
    $args = array(
      'post_type' => 'log_download',
      'meta_query' => array(
          'relation' => 'OR',
          array(
              'key' => 'plus_item',
              'value' => '"' . $item_id . '"',
              'compare' => 'LIKE'
          ),
          array(
              'key' => 'plus_item',
              'value' => $item_id,
              'compare' => '='
          )
      )
    );
    $posts = get_posts( $args );


    if ( $posts ):
      $file = [];
      $date = [];
      $category = [];
      $collection = [];
      $user = [];
      $file = [];

      foreach( $posts as $post ):
        $file[] = get_field('file_name', $post->ID);
        $date[] = get_the_date('r', $post->ID);
        $terms =  wp_get_post_terms($item_id , 'graphics-category');
        if(!empty($terms)){
          $category[] = $terms[0]->name;
        }

        if(get_field('collocation_icons', $item_id)) {
          $collection[] = get_field('file_name', $post->ID);
        } else {
          $collection[] = null;
        }

        $user[] = get_field('user', $post->ID);

      endforeach;

      $results = [
        'file' => $file,
        'date' => $date,
        'category' => $category,
        'collection' => $collection,
        'user' => $user,
        'counter' => count($posts),
      ];


      $result = [
        'success' => true,
        'code' => 200,
        'message' => 'log success',
        'data' => $results,
      ];
    else:
      $result = [
        'success' => false,
        'code' => 404,
        'message' => 'log error item id is missing',
      ];
    endif;
  } else {

    $args = array(
      'post_type'        => 'graphics',
      'posts_per_page'   => $per_page,
      'paged'            => $page,
      'post_status'      => 'publish',
    );

    $posts = new WP_Query( $args );

      if ( $posts->have_posts() ) {
        foreach( $posts->posts as &$post ):


        
          $args = array(
            'post_type' => 'log_download',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'plus_item',
                    'value' => '"' . $post->ID . '"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'plus_item',
                    'value' => $post->ID,
                    'compare' => '='
                )
            )
          );
          $items = get_posts( $args );

          

          $results = [];
          if ( $items ):

            $file = [];
            $date = [];
            $category = [];
            $collection = [];
            $user = [];
            $file = [];

            foreach( $items as $item ):
              $file = $item->post_title;
              $date[] = get_the_date('r', $item->ID);
              $terms =  wp_get_post_terms($post->ID , 'graphics-category');
              if(!empty($terms)){
                $category = $terms[0]->name;
              }

              if(get_field('collocation_icons', $post->ID)) {
                $collection[] = get_field('file_name', $item->ID);
              } else {
                $collection[] = null;
              }

              $user[] = get_field('user', $item->ID);

            endforeach;

            $results = [
              'ID' => $post->ID,
              'File' => $file,
              'Date' => $date,
              'Category' => $category,
              'Collection' => $collection,
              'User' => $user,
              'counter' => count($items),
            ];

            $post->results = $results;

          else:

            $post->results = [
              'ID' => $post->ID,
              'counter' => 0
            ];
          endif;
           
          

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
  register_rest_route('wp/api/' ,'log/get_download',array(
    'methods' => 'GET',
    'callback' => 'get_log_download',
    'args' => array(
      'item_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),    
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
    )
  ));
});
