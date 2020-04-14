<?php
function get_graphics_category($data){
  
  $data=$data->get_params('GET');
  extract($data);

  $term_id = !empty($term_id) ? $term_id : 0;


  $categories = get_terms(
    [
      'taxonomy' => 'graphics-category',
      'hide_empty' => false,
      'parent' => 0,
      'orderby'  => 'include',
    ]
  );

  if ( !empty($categories) ) {

    $categories_parent = [];

    foreach ($categories as $category) {
        $categories_parent[] = [
          'id' => $category->term_id,
          'name' => html_entity_decode( $category->name ),
        ];
      }
   
    $categories_result =  $categories_parent;

    $result = [
      'success' => true,
      'code' => 200,
      'message' => 'successfully retrieved',
      'data' => $categories_result,
    ];
    return $result;
  } else {
    $result = [
      'success' => 'false',
      'code' => 404,
      'message' => 'no terms found',
    ];
    return $result;
  }
}


add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'graphics/GetCategory/',array(
    'methods' => 'GET',
    'callback' => 'get_graphics_category',
    'args' => array(
      'term_id' => array(
        'validate_callback' => function($param,$request,$key){
          return is_numeric($param);
        }
      ),
    )
  ));
});
