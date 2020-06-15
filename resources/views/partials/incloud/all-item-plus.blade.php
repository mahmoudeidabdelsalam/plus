@php 
  $link = get_the_permalink();
  $status   = isset($_GET['item']) ? $_GET['item'] : 'all';
  global $current_user;
  wp_get_current_user();
@endphp
<!-- Actual search box -->
  <div class="form-group has-search">
    <form action="{{$link}}">
      <input  class="search-inputs"  name="refine"  value="{{ get_search_query() }}" type="text" placeholder="{{ _e('search...','premast') }}" autocomplete="off" spellcheck="false" maxlength="100"">
      <button type="submit"><span class="fa fa-search form-control-feedback"></span></button>
    </form>
  </div>
  
  
<ul class="nav nav-tabs" id="myTabPlus" role="tablist">
  <li class="nav-item all">
    <a class="nav-link active" id="term-all-tab" data-toggle="tab" href="#tabs-all" role="tab" aria-controls="tabs-all" aria-selected="true">{{ _e('all', 'premast') }}</a>
  </li>
  @php 
    $terms = get_terms( 'graphics-category', array( 'hide_empty' =>  1, 'parent' =>0 ));
    $counter = 0;
  @endphp  
  @foreach($terms as $term) 
    @php 
      $counter++; 
    @endphp
    <li class="nav-item">
      <a class="nav-link" id="term-{{ $term->term_id }}-tab" data-toggle="tab" href="#tabs-{{ $term->term_id }}" role="tab" aria-controls="tabs-{{ $term->term_id }}" aria-selected="true">{{ $term->name }}</a>
    </li>
  @endforeach
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade  show active" id="tabs-all" role="tabpanel" aria-labelledby="tabs-all-tab">
    @php
      if ($status == 'all') {
        $status = 'publish';
      } elseif ($status == 'pending') {
        $status = 'pending';
      } elseif ($status == 'rejected') {
        $status = 'rejected';
      }

      $alls = array(
        'post_type' => 'graphics',
        'posts_per_page' => 20,
        'paged' => $paged,
        'post_author' => $current_user->ID,
        'post_status' => $status,
      );
      $all = new \WP_Query( $alls );
    @endphp
      <div class="row">
        @if($all->have_posts())
          @while($all->have_posts()) 
          @php 
            $all->the_post(); 
            $file = get_field('file_graphics');
            $category =  wp_get_post_terms(get_the_ID() , 'graphics-category');
            $tags =  wp_get_post_terms(get_the_ID() , 'graphics-tag');

            if ($category) {
              $term_id = $category[0]->term_id;
            } else {
              $term_id = 0;
            }

            if ($tags) {
              $tag_id = [];
              foreach ($tags as $tag) {
                $tag_id[] = $tag->name;
              }
            } else {
              $tag_id = 0;
            }

            @endphp
            <div class="col-md-3 col-sm-4 co-xs-12 col-12 plus-all-item" id="{{ the_ID() }}">
              <div class="card">
                <div class="img-top-card">
                  <img src="{{ Utilities::global_thumbnails(get_the_ID(),'full')}}" class="card-img-top" alt="...">
                </div>
                <div class="card-event">
                  <a herf="#" class="item-deleted item-event" data-deleted="{{ the_ID() }}" data-nonce="<?php echo wp_create_nonce('testdel') ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                  <a herf="#" class="item-edit item-event" data-toggle="modal" data-target="#exampleModal{{ the_ID() }}"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                </div>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal{{ the_ID() }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{ the_ID() }}" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit item</h5>
                  </div>
                  <div class="modal-body add-item-plus">
                    <span class="register-message"></span>
                    <span class="loading" style="display:none;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
                    <form class="edit-item" action="#" method="POST" name="edit_post">
                      <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                          <label for="frontend-button" class="label-cover">
                            <span class="images-files"></span>
                            <img class="profile-pic" src="{{ Utilities::global_thumbnails(get_the_ID(),'full')}}">
                            <span>{{ _e('Cover image', 'premast') }}</span>
                          </label>
                          <div class="upload-form">
                            <div class="form-group">
                              <input type="file" id="frontend-button" value=""  class="files-thumbnail form-control"/>
                              <input name="thumbnail" value="" id="thumbnails" hidden required/>
                            </div>
                          </div>

                          <label class="custom-download-label" for="upload_item">
                            <span class="name-files">{{ _e('Upload file', 'premast') }}</span>
                          </label>

                          <div class="upload-response"></div>
                          <div class="custom-file d-none">
                            <input type="file" id="upload_item" value="<?= $file; ?>" class="custom-file-input files-download"/>                
                            <input name="file_url" value="" id="file_item" hidden required/>
                          </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-12">
                          <div class="row">
                            <div class="col-12">
                              <input type="text" value="{{ the_title() }}" name="title" id="title" class="form-control"  placeholder="Name" required>
                            </div>
                          
                            <div class="col-12">
                              <?php 
                                wp_dropdown_categories( array(
                                  'taxonomy'    => 'graphics-category',
                                  'name'        => 'main_scat'.get_the_ID().'', 
                                  'multiple'    => false,
                                  'selected'    => $term_id,
                                  'walker'      => new Willy_Walker_CategoryDropdown(),
                                  'hide_empty'  => false,
                                ));
                              ?>
                            </div>
                            <div class="col-12 multiple-select">
                              <?php 
                              // dd($tag_id);
                                wp_dropdown_categories( array(
                                  'taxonomy'     => 'graphics-tag',
                                  'name'         => 'tags'.get_the_ID().'', 
                                  'multiple'     => true,
                                  'selected'    => $tag_id,
                                  'walker'       => new Willy_Walker_CategoryDropdown(),
                                  'hide_empty'   => false,
                                  'value_field'  => 'name',
                                ));
                              ?>
                            </div>
                          </div>
                          <input type="hidden" name="post_id" value="{{ the_ID() }}" />
                          <input type="hidden" name="action" value="edit_post" />
                        </div>
                        <div class="col-md-12 col-sm-12 col-12 custom-button">
                          <button type="submit" id="submit{{ the_ID() }}" name="submit">{{ _e('Save changes', 'premast') }}</button>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            Cancel
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>


            <script>
              jQuery(function($) {
                $('#submit<?= the_ID(); ?>').on('click',function(e){
                  e.preventDefault();
                  var post_id     = "<?= the_ID(); ?>";
                  var thumbnail   = $('#exampleModal<?= the_ID(); ?> input[name="thumbnail"]').val();
                  var file_url    = $('#exampleModal<?= the_ID(); ?>  input[name="file_url"]').val();
                  var title       = $('#exampleModal<?= the_ID(); ?>  input[name="title"]').val();
                  var tags        = $('#exampleModal<?= the_ID(); ?>  #tags<?= the_ID(); ?>').val();
                  var main_scat   = $('#exampleModal<?= the_ID(); ?>  [name="main_scat<?= the_ID(); ?>"]').val();

                  $.ajax({
                    type:"POST",
                    url:"<?= admin_url('admin-ajax.php'); ?>",
                    data: {
                      action: "graphics_edit_front_end",
                      thumbnail   : thumbnail,
                      file_url    : file_url,
                      title       : title,
                      main_scat   : main_scat,
                      tags        : tags,
                      post_id : post_id,
                    },
                    beforeSend: function(results) {
                      $('.loading').show();
                    },
                    success: function(results){
                      $('.register-message').html(results).show();
                      $('.register-message').css('color', 'green');
                      $('.loading').hide();
                    },
                    error: function(results) {
                      $('.register-message').html('plz try again later').show();
                      $('.register-message').css('color', 'red');
                      $('.loading').hide();
                    }
                  });
                });
              });
            </script>


          @endwhile
        @else 
              <div class="alert alert-info">Oops, Item Not Found.</div>          
        @endif
      </div>

    <div class="col-12 pt-5 pb-5">
      <nav aria-label="Page navigation example">{{ premast_base_pagination(array(), $all) }}</nav>
    </div>
  </div>
  @php
    $counter = 0;
  @endphp
  @foreach($terms as $term) 
    @php
      $counter++; 
    @endphp
    <div class="tab-pane fade" id="tabs-{{ $term->term_id }}" role="tabpanel" aria-labelledby="tabs-{{ $term->term_id }}-tab">
      @php
        $args = array(
          'post_type' => 'graphics',
          'posts_per_page' => 20,
          'paged' => $paged,
          'post_author' => $current_user->ID,
          'post_status' => $status,
          'tax_query' => array(
            array(
              'taxonomy' => 'graphics-category',
              'field' => 'term_id',
              'terms' => $term->term_id
            )
          )
        );
        $query = new \WP_Query( $args );
      @endphp
        <div class="row">
          @if($query->have_posts())
            @while($query->have_posts()) @php($query->the_post())
              <div class="col-md-3 col-sm-4 co-xs-12 col-12 plus-all-item" id="{{ the_ID() }}">
                <div class="card">
                  <div class="img-top-card">
                    <img src="{{ Utilities::global_thumbnails(get_the_ID(),'full')}}" class="card-img-top" alt="...">
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">{{ the_title() }}</h5>
                  </div>
                  <div class="card-event">
                    <a herf="#" class="item-deleted item-event" data-deleted="{{ the_ID() }}" data-nonce="<?php echo wp_create_nonce('testdel') ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> <span class="text-white">{{ _e('Delete', 'premast') }}</span></a> 
                  </div>
                </div>
              </div>
            @endwhile
          @else 
              <div class="alert alert-info">Oops, Item Not Found.</div>  
          @endif
        </div>

      <div class="col-12 pt-5 pb-5">
        <nav aria-label="Page navigation example">{{ premast_base_pagination(array(), $query) }}</nav>
      </div>
    </div>
  @endforeach
</div>

<style>
  .modal-content {
    background: #fff;
  }
  .custom-button {
    align-items: center;
    display: flex;
    justify-content: flex-end;
  }
  .custom-button button {
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 24px;
    text-align: center;
    letter-spacing: 0.04px;
    color: #FFFFFF;
  }
  .custom-button button.close {
    color: #333;
    margin-left: 15px;
    background: transparent;
    opacity: 1;
  }
  .files-thumbnail {
    display: none;
  }  
  .modal-content select, .add-item-plus span.select2 {
    width: 100%;
    margin: 10px 0;
    height: 40px;
    border: 1px solid #ccc !important;
    background-color: #fff !important;
    border-radius: 4px !important;
    padding: 0px 10px 10px;
  }
  .custom-button button {
    background: #2F80ED;
    border-radius: 7px;
    color: #fff;
    padding: 5px 20px;
    float: right;
  }
  img.profile-pic {
    width: 100%;
  }
  label.label-cover span {
    position: absolute;
    background: #FFFFFF;
    border-radius: 25px;
    width: 95%;
    border: 1px solid #ccc;
    font-size: 16px;
    line-height: 24px;
    text-align: center;
    letter-spacing: 0.04px;
    color: #282F39;
    padding: 4px;
    z-index: 9;
  }
  label.label-cover:after {
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
    border: 1px solid #E8E8E8;
    box-sizing: border-box;
    border-radius: 4px;
    content: "";
    position: absolute;
    height: 100%;
    width: 100%;
    z-index: 3;
  }
  label.label-cover {
    position: relative;
  }  
  span.select2-selection.select2-selection--multiple {
    outline: none !important;
    box-shadow: none !important;
  }
  span.register-message {
    position: absolute;
    top: 10px;
  }
  span.loading {
    position: absolute;
    top: -54px;
    right: 8px;
    color: #2f7fed;
    font-size: 45px;
  }
</style>

<script>
  jQuery(function($) {
                
    $('.modal').on('shown.bs.modal', function (e) {
      $('select').select2({
        theme: 'bootstrap4',
      });
      $('select[id*="tags"]').select2({
        theme: 'bootstrap4',
        tags: true,
        tokenSeparators: [',', ' '],
        placeholder: "selected tag...",
      });
    })

    $('.item-deleted').on('click', function(e){
      var post = $(this).attr('data-deleted'); // get post id from hidded field
      var nonce = $(this).attr('data-nonce'); // get nonce from hidded field
      
      $.ajax({
        url: "<?php echo admin_url('admin-ajax.php'); ?>", // in backend you should pass the ajax url using this variable
        type: 'POST',
        data: { 
          action : 'ajaxtestdel', 
          postid: post, 
          ajaxsecurity: nonce 
        },
        success: function(data){
          if(data === 'success') {
            $('#' + post).remove()
          }
        }
      });
    });
  });
</script>
