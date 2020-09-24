<?php
/**
 * function api log download
 * @param String $item_id, $item_title, $user
 * @param Type POST
 */
function log_download($data){

  $data=$data->get_params('POST');
  extract($data);

  $item_id    = !empty($item_id) ? $item_id : false;
  $item_title = !empty($item_title) ? $item_title : false;
  $user       = !empty($user) ? $user : false;

  $post = get_post($item_id);

  if($item_id == 'unsplash') {
    $title = "unsplash id-" . $item_title;
  } else {
    $title = $post->post_title;
  }



  if($item_id) {
    $counter = get_field('download_counter', $item_id);
    if($counter) {
      update_field( 'field_5f5f74c83892b', $counter + 1, $item_id );
    } else {
      update_field( 'field_5f5f74c83892b', 1, $item_id );
    }

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
      'item_title' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),    
      'user' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),                 
    )
  ));
});

/**
 * function api Get log download
 * @param String $item_id, $per_page, $page
 * @param Type GET
 */

function get_log_download($data){

  $data=$data->get_params('GET');
  extract($data);

  $item_id      = !empty($item_id) ? $item_id : false;
  $per_page     = !empty($per_page) ? $per_page : 10;
  $page         = !empty($page) ? $page : true;
  $token      = !empty($token) ? $token : false;
  $permission = TokenPermission();
  

  if($token == $permission) {
    $return = true;
  } else {
    $result = [
      'code' => 401,
      'message' => 'Sorry, you are not allowed to do that.',
    ];    
    return $result;
    die();
  }



  
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
      'token' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});


/**
 * function api log Search
 * @param String $keyword, $term_id, $user, $results
 * @param Type POST
 */
function log_search($data){

  $data=$data->get_params('POST');
  extract($data);

            
  $keyword = !empty($keyword) ? $keyword : false;
  $term_id = !empty($term_id) ? $term_id : false;
  $user    = !empty($user) ? $user : false;
  $results = !empty($results) ? $results : false;

 
  $args = array(
    'post_type' => 'log_search',
    'post_title' => $keyword,
    'post_status' => 'publish',
  );

  $post_id = wp_insert_post( $args );

  $date = get_the_date('r', $post_id);
  $category = get_term_by('id', $term_id, 'graphics-category');
 


  if($post_id) {
    update_field( 'field_5f58eb79c6f46', $date, $post_id );
    update_field( 'field_5f58eb85c6f47', $category->name, $post_id );
    update_field( 'field_5f58eb90c6f48', $results, $post_id );
    update_field( 'field_5f58eb9cc6f49', $user, $post_id );
  }


  $result = [
    'success' => true,
    'code' => 200,
    'message' => 'log search success',
    'data' => $post_id,
  ];
  return $result;

}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'log/search',array(
    'methods' => 'POST',
    'callback' => 'log_search',
    'args' => array(
      'keyword' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),    
      'term_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),    
      'user' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),                 
    )
  ));
});


/**
 * function api Get log Search
 * @param String $keyword, $term_id, $user, $results
 * @param Type POST
 */
function get_log_search($data){

  $data=$data->get_params('GET');
  extract($data);

            
  $keyword    = !empty($keyword) ? $keyword : false;
  $category   = !empty($category) ? $category : false;
  $page       = !empty($page) ? $page : false;
  $per_page   = !empty($per_page) ? $per_page : 10;
  $token      = !empty($token) ? $token : false;
  $permission = TokenPermission();
  

  if($token == $permission) {
    $return = true;
  } else {
    $result = [
      'code' => 401,
      'message' => 'Sorry, you are not allowed to do that.',
    ];    
    return $result;
    die();
  }




  if($keyword && empty($category)) {
    $args = array(
      'post_type'         => 'log_search',
      'post_status'       => 'publish',
      'posts_per_page'    => $per_page,
      'paged'             => $page,
      's'                 => $keyword,
    );

    $posts = get_posts( $args );

    $title = [];
    $date = [];
    $category = [];
    $result = [];
    $user = [];
    
    foreach ($posts as $post) {
      $title  = $post->post_title;
      $date[] = get_the_date('r', $post->ID);
      $category[] = get_field('category', $post->ID);
      $result[] = get_field('results', $post->ID);
      $user[] = get_field('user', $post->ID);
    }

    $results = [
      'keyword' => $title,
      'date' => $date,
      'category' => $category,
      'results' => $result,
      'user' => $user,
      'counter' => count($posts),
    ];

  } elseif($category) {

    $results = [];

    $args = array(
      'post_type'       => 'log_search',
      'post_status'     => 'publish',
      'posts_per_page'   => $per_page,
      'paged'            => $page,
      'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'category',
            'value' => '"' . $category . '"',
            'compare' => 'LIKE'
        ),
        array(
            'key' => 'category',
            'value' => $category,
            'compare' => '='
        )
      ),
    );

    $posts = get_posts( $args );

    $title = [];
    $date = [];
    $category = [];
    $result = [];
    $user = [];
    
    foreach ($posts as $post) {
      $title  = $post->post_title;        
      $date = get_the_date('r', $post->ID);
      $category = get_field('category', $post->ID);
      $result = get_field('results', $post->ID);
      $user = get_field('user', $post->ID);

      $results[] = [
        'keyword' => $title,
        'date' => $date,
        'category' => $category,
        'results' => $result,
        'user' => $user,
      ];
    }

  } else {

    $results = [];

    $args = array(
      'post_type'       => 'log_search',
      'post_status'     => 'publish',
      'posts_per_page'   => $per_page,
      'paged'            => $page,
    );

    $posts = get_posts( $args );

    $title = [];
    $date = [];
    $category = [];
    $result = [];
    $user = [];
    
    foreach ($posts as $post) {
      $title  = $post->post_title;        
      $date = get_the_date('r', $post->ID);
      $category = get_field('category', $post->ID);
      $result = get_field('results', $post->ID);
      $user = get_field('user', $post->ID);

      $results[] = [
        'keyword' => $title,
        'date' => $date,
        'category' => $category,
        'results' => $result,
        'user' => $user,
      ];
    }

  }


  $result = [
    'success' => true,
    'code' => 200,
    'message' => 'log search success',
    'data' => $results,
  ];

  return $result;

}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'log/get_search',array(
    'methods' => 'GET',
    'callback' => 'get_log_search',
    'args' => array(
      'keyword' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'category' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),  
      'page' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),    
      'per_page' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'token' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),                 
    ),
  ));
});