<?php
function get_graphics_category($data){
  
  $data=$data->get_params('GET');
  extract($data);

  $term_id = !empty($term_id) ? $term_id : 0;


  if($term_id) {
    $categories = get_terms(
      [
        'taxonomy' => 'graphics-category',
        'hide_empty' => false,
        'parent' => 0,
        'orderby'  => 'include',
        'include' => $term_id
      ]
    );
  } else {
    $categories = get_terms(
      [
        'taxonomy' => 'graphics-category',
        'hide_empty' => false,
        'parent' => 0,
        'orderby'  => 'include',
      ]
    );
  }


  if ( !empty($categories) ) {

    $categories_parent = [];

    foreach ($categories as $category) {
        $icon = get_field('icon_term', 'graphics-category_' . $category->term_id);
        $icon_hover = get_field('icon_term_hover', 'graphics-category_' . $category->term_id);
        $column_number = get_field('column_number', 'graphics-category_' . $category->term_id);
        $pre_page = get_field('pre_page', 'graphics-category_' . $category->term_id);

        $sources = get_field('select_sources_items', 'graphics-category_' . $category->term_id);

        if ($icon) {
          $icon = $icon;
        }else {
          $icon = get_theme_file_uri().'/dist/images/upload.png';
        }

        if ($icon_hover) {
          $icon_hover = $icon_hover;
        }else {
          $icon_hover = get_theme_file_uri().'/dist/images/upload.png';
        }

        $categories_parent[] = [
          'id' => $category->term_id,
          'name' => html_entity_decode( $category->name ),
          'icon' => $icon,
          'icon_hover' => $icon_hover,
          'column' => ($column_number)? $column_number:"2",
          'pre_page' => ($pre_page)? $pre_page:"10",
          'sources' => ($sources)? $sources:"default",
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
