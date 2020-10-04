<?php
/**
 * API Add New Item
 * @param type POST 
 * @param string $email
 * @param string $password
 * @param string $title
 * @param string $image
 * @param string $file 
 * @param string $term
 * @param string $child
 * @param array  $tags -> ex. (tag 1, tgs, new)
 * @param string $author
 * @param string $author_link
 * @param string $author_name
 * @param array  $collocations -> ex. (https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png, https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png, https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png)
 */
function action_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $email        = !empty($email) ? $email : false;
  $password     = !empty($password) ? $password : false;
  $title        = !empty($title) ? $title : false;
  $image        = !empty($image) ? $image : false;
  $file         = !empty($file) ? $file : false;
  $term         = !empty($term) ? $term : false;
  $child        = !empty($term_child) ? $term_child : false;
  $tags         = !empty($tag) ? $tag : false;
  $author       = !empty($author) ? $author : false;
  $author_link  = !empty($author_link) ? $author_link : false;
  $author_name  = !empty($author_name) ? $author_name : false;
  $collocations = !empty($collocations) ? $collocations : false;
  $premium      = !empty($premium) ? $premium : 0;


  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $password) {

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  $user->user_email;
    }

    $user = get_user_by( 'email', $email );
    $author_id = get_user_by( 'email', $author );

    if (in_array($email, $emails) && wp_check_password( $password, $user->data->user_pass, $user->ID)) { 

      $post = array(
        'post_type' => 'graphics',
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => $author_id->ID
      );

      $post_id = wp_insert_post( $post );

      wp_set_post_terms( $post_id, array(intval($term), intval($child)), 'graphics-category' );

      wp_set_post_terms( $post_id, $tags, 'graphics-tag' );

      if ($post_id) {
        
        if($file) {
          $file_id = Generate_Featured_Image($file);
          update_field( 'field_5d43723a031b2', $file_id, $post_id );
          update_field( 'field_5f2a851e12e12e12e42d3b1a', $author_name, $post_id );
          update_field( 'field_5f2a8523de123312231233b19', $author_link, $post_id );
          update_field( 'field_5f12425532747726bc859e', $premium, $post_id );
        }
        
        if($image) {
          $attach_id = Generate_Featured_Image($image);
          set_post_thumbnail( $post_id, $attach_id );
        }


        $files = [];
        $collocations = explode(', ', $collocations);

        foreach ($collocations as $key => $value) {
          $thumb_id = Generate_Featured_Image($value);

          $files[] = array(
            'file_icon' => $thumb_id,
          );
        }

        
        $counter = 0;
        foreach ($files as $key => $value) {
          $counter++;
          $file = array_values($value);

          $row = array(
            'field_5f16c8145cae6'   => $file[0],
          );

          add_row('field_5f16c8095cae5', $row, $post_id);
        }
                
      }

      $args = array(
        'post_type'   => 'graphics',
        'post_status' => 'publish',
        'post__in'    => array($post_id)
      );

      $posts = get_posts($args);

      if (!is_wp_error($posts)) {
        $message = 'successfully Login';
      }

      add_action('acf/save_post', 'my_acf_save_post');
      function my_acf_save_post( $posts ) {
        wp_update_post($post_id);
      }

      foreach( $posts as &$post ):
        $terms =  wp_get_post_terms($post->ID , 'graphics-category');
        if(!empty($terms)){
          $post->Category= $terms[0]->name;
        }

        $tags =  wp_get_post_terms($post->ID, 'graphics-tag');

        if(!empty($tags)){
          $post->Tags= $tags[0]->name;
        }
        
        $file = get_field('file_graphics' , $post->ID);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        $post->Type = $ext;
        $post->Id           = $post->ID;
        $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
        $post->Content = get_field('file_graphics' , $post->ID);
        $post->PreviewImage = get_the_post_thumbnail_url($post->ID, 'full' );
        $post->Link = get_the_permalink($post->ID);
        $post->Author = $author_name;
        $post->AuthorLink = $author_link;
        $post->collocations = get_field('collocation_icons' , $post->ID);
        unset($post->ID, $post->post_name, $post->post_type, $post->post_excerpt);
        formatPost($post);
      endforeach;
      
    } else { 
      $message = 'Try to login again (login or password) error';
    } 
    $result = [
      'success' => true,
      'code' => 200,
      'message' => $message,
      "data" => $posts,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'login or password not fund',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'post/add',array(
    'methods' => 'POST',
    'callback' => 'action_posts',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),  
      'title' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'image' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'file' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'term' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'term_child' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'tag' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'author' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'author_link' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'author_name' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'collocations' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'premium' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});



/**
 * API Update Items
 * @param Type POST
 * @param string $post_id
 * @param string $email
 * @param string $password
 * @param string $title
 * @param string $image
 * @param string $file 
 * @param string $term
 * @param string $child
 * @param array  $tags -> ex. (tag 1, tgs, new)
 * @param string $author
 * @param string $author_link
 * @param string $author_name
 * @param array  $collocations -> ex. (https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png, https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png, https://plus.premast.com/app/themes/plus/dist/images/logo-plus.png)
 */
function get_api_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $post_id      = !empty($post_id) ? $post_id : false;
  $email        = !empty($email) ? $email : false;
  $password     = !empty($password) ? $password : false;
  $title        = !empty($title) ? $title : false;
  $image        = !empty($image) ? $image : false;
  $file         = !empty($file) ? $file : false;
  $term         = !empty($term) ? $term : false;
  $child        = !empty($term_child) ? $term_child : false;
  $tags         = !empty($tag) ? $tag : false;
  $author       = !empty($author) ? $author : false;
  $author_link  = !empty($author_link) ? $author_link : false;
  $author_name  = !empty($author_name) ? $author_name : false;
  $collocations = !empty($collocations) ? $collocations : false;
  $premium = !empty($premium) ? $premium : 0;

  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $password) {

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  $user->user_email;
    }

    $user = get_user_by( 'email', $email );
    $author_id = get_user_by( 'email', $author );

    if (in_array($email, $emails) && wp_check_password( $password, $user->data->user_pass, $user->ID)) { 
      

      $graphics = wp_update_post(array (
        'post_type' => 'graphics',
        'ID'           => $post_id,
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => $author_id->ID
      ));


      if ($graphics) {



        wp_set_post_terms( $graphics, array(intval($term), intval($child)), 'graphics-category' );

        wp_set_post_terms( $graphics, $tags, 'graphics-tag' );


        if($file) {
          $file_id = Generate_Featured_Image($file);
          update_field( 'field_5d43723a031b2', $file_id, $graphics );
          update_field( 'field_5f2a851e12e12e12e42d3b1a', $author_name, $graphics );
          update_field( 'field_5f2a8523de123312231233b19', $author_link, $graphics );
          update_field( 'field_5f12425532747726bc859e', $premium, $post_id );
        }
        
        if($image) {
          $attach_id = Generate_Featured_Image($image);
          set_post_thumbnail( $graphics, $attach_id );
        }


        $files = [];
        $collocations = explode(', ', $collocations);

        foreach ($collocations as $key => $value) {
          $thumb_id = Generate_Featured_Image($value);

          $files[] = array(
            'file_icon' => $thumb_id,
          );
        }

        
        $counter = 0;
        foreach ($files as $key => $value) {
          $counter++;
          $file = array_values($value);

          $row = array(
            'field_5f16c8145cae6'   => $file[0],
          );

          add_row('field_5f16c8095cae5', $row, $graphics);
        }
      }


        

      $args = array(
        'post_type'   => 'graphics',
        'post_status' => 'publish',
        'post__in'    => array($graphics)
      );

      $posts = get_posts($args);

      if (!is_wp_error($posts)) {
        $message = 'successfully Login';
      }
      
      add_action('acf/save_post', 'my_acf_save_post');
      function my_acf_save_post( $posts ) {
        wp_update_post($graphics);
      }


      foreach( $posts as &$post ):
        $terms =  wp_get_post_terms($post->ID , 'graphics-category');
        if(!empty($terms)){
          $post->Category= $terms[0]->name;
        }

        $tags =  wp_get_post_terms($post->ID, 'graphics-tag');

        if(!empty($tags)){
          $post->Tags= $tags[0]->name;
        }
        
        $file = get_field('file_graphics' , $post->ID);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        $post->Type = $ext;
        $post->Id           = $post->ID;
        $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
        $post->Content = get_field('file_graphics' , $post->ID);
        $post->PreviewImage = get_the_post_thumbnail_url($post->ID, 'full' );
        $post->Link = get_the_permalink($post->ID);
        $post->Author = $author_name;
        $post->AuthorLink = $author_link;
        $post->collocations = get_field('collocation_icons' , $post->ID);
        unset($post->ID, $post->post_name, $post->post_type, $post->post_excerpt);
        formatPost($post);
      endforeach;
      
    } else { 
      $message = 'Try to login again (login or password) error';
    } 
    $result = [
      'success' => true,
      'code' => 200,
      'message' => $message,
      "data" => $posts,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'login or password not fund',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'post/add_update',array(
    'methods' => 'POST',
    'callback' => 'get_api_posts',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),  
      'title' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'image' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'file' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'term' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'term_child' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'tag' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
      'author' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'author_link' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'author_name' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'collocations' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'post_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'premium' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});



/**
 * API Delete Items
 * @param Type POST
 * @param string $post_id
 * @param string $email
 * @param string $password
 */
function delete_api_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;
  $post_id = !empty($post_id) ? $post_id : "post_id";


  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $password) {

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  $user->user_email;
    }

    $user = get_user_by( 'email', $email );

    if (in_array($email, $emails) && wp_check_password( $password, $user->data->user_pass, $user->ID)) { 
     
      wp_delete_post($post_id);
      $message = 'delete item success';
      
    } else { 
      $message = 'Try to login again (login or password) error';
    } 
    $result = [
      'success' => true,
      'code' => 200,
      'message' => $message,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'login or password not fund',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'post/add_delete',array(
    'methods' => 'POST',
    'callback' => 'delete_api_posts',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),  
      'post_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});



function edit_premium_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;
  $post_id = !empty($post_id) ? $post_id : "post_id";


  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $password) {

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  $user->user_email;
    }

    $user = get_user_by( 'email', $email );

    if (in_array($email, $emails) && wp_check_password( $password, $user->data->user_pass, $user->ID)) { 
     
      $graphics = wp_update_post(array (
        'post_type' => 'graphics',
        'ID'           => $post_id,
        'post_status' => 'publish',
      ));


      if ($graphics) {
        update_field( 'field_5f12425532747726bc859e', $premium, $post_id );
      }

      $message = 'edit item success';

    } else { 
      $message = 'Try to login again (login or password) error';
    } 
    $result = [
      'success' => true,
      'code' => 200,
      'message' => $message,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'login or password not fund',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'post/premium',array(
    'methods' => 'POST',
    'callback' => 'edit_premium_posts',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),  
      'post_id' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'premium' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});
