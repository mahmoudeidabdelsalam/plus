<?php
function action_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;
  $title = !empty($title) ? $title : "test";
  $image = !empty($image) ? $image : false;
  $file = !empty($file) ? $file : false;
  $term = !empty($term) ? $term : false;
  $tag = !empty($tag) ? $tag : false;

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
      
      $post_id = wp_insert_post(array (
        'post_type' => 'graphics',
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => $user->ID,
        'tax_input' => array( 'graphics-category' => array($term), 'graphics-tag' => $tag)
      ));


      if ($post_id) {
        
        if($file) {
          $file_id = Generate_Featured_Image($file);
          update_field( 'field_5d43723a031b2', $file_id, $post_id );
        }
        
        if($image) {
          $attach_id = Generate_Featured_Image($image);
          set_post_thumbnail( $post_id, $attach_id );
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
        
        $file = get_field('file_graphics' , $post->ID);
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        $post->Type = $ext;
        $post->Id           = $post->ID;
        $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
        $post->Content = get_field('file_graphics' , $post->ID);
        $post->PreviewImage = get_the_post_thumbnail_url($post->ID, 'full' );
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
      'tag' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),   
    )
  ));
});
