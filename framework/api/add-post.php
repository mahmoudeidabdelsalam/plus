<?php
function action_posts($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;
  $title = !empty($title) ? $title : "test";

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
        'post_type' => 'post',
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => $user->ID,
      ));


      $args = array(
        'post_type'   => 'post',
        'post_status' => 'publish',
        'post__in'    => array($post_id)
      );

      $posts = get_posts($args);

      if (!is_wp_error($posts)) {
        $message = 'successfully Login';
      }
      
      foreach( $posts as &$post ):
        $post->Id           = $post->ID;
        $post->Name         = htmlspecialchars_decode( get_the_title($post->ID) );
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
    )
  ));
});
