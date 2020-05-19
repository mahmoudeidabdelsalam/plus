


  <div class="alert alert-danger col-12" id="errors" style="display:none;">{{ _e('Please provide all required fields', 'premast') }}</div>
  <span id="add-loader" style="display:none;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>

  <p class="register-message m-0" style="display:none"></p>

  <form id="publish_product" action="#" method="POST" name="new_post">
    <div class="row">

      <div class="col-12 p-0">
        @php 
        $terms = get_terms(array(
          'taxonomy' => 'graphics-category',
          'hide_empty' => false,
        ));
        @endphp
          <div class="custom-control custom-head custom-head-inline">
            <label class="custom-control-head">Choose Category</label>
          </div>
        @php $counter = 0; @endphp
        @foreach ($terms as $term)
        @php $counter++; @endphp
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="{{ $term->term_id }}" name="main_scat" value="{{ $term->term_id }}" class="custom-control-input" {{ ($counter == 1)? 'checked':'' }}>
            <label class="custom-control-label" for="{{ $term->term_id }}">{{ $term->name }}</label>
          </div>
        @endforeach
      </div>


      <h2 class="mt-5 mb-3">{{ _e('Add new item', 'premast') }}</h2>

      <div class="add-item-plus col-12">
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <label for="frontend-button" class="label-cover">
              <span class="images-files"></span>
              <img class="profile-pic" src="{{ get_theme_file_uri().'/dist/images/cover-image.png' }}">
              <span>{{ _e('Cover image', 'premast') }}</span>
            </label>
            <div class="upload-form">
              <div class="form-group">
                <input type="file" id="frontend-button"  class="files-thumbnail form-control"/>
                <input name="thumbnail" value="" id="thumbnails" hidden required/>
              </div>
            </div>

            <label class="custom-download-label" for="upload_item">
              <span class="name-files">{{ _e('Upload file', 'premast') }}</span>
            </label>

            <div class="upload-response"></div>
            <div class="custom-file d-none">
              <input type="file" id="upload_item" class="custom-file-input files-download"/>                
              <input name="file_url" value="" id="file_item" hidden required/>
            </div>
          </div>


          <div class="col-md-9 col-sm-12 col-12">
            <div class="row">
              <div class="col">
                <input type="text" name="title" id="title" class="form-control"  placeholder="Name" required>
              </div>
             
            </div>

            <?php 
              wp_dropdown_categories( array(
                'taxonomy'   => 'graphics-tag',
                'name'       => 'tags', 
                'multiple'   => true,
                'walker'     => new Willy_Walker_CategoryDropdown(),
                'hide_empty' => false,
              ));
            ?>
          </div>

          <div class="col-md-12 col-sm-12 col-12">
            <button type="submit" tabindex="6" id="submit" name="submit">{{ _e('Add', 'premast') }} <i class="fa fa-plus" aria-hidden="true"></i></button>
          </div>
        </div>
        <input type="hidden" name="action" value="new_post" />
        <?php wp_nonce_field( 'new-post' ); ?>
      </div>
    </div>
  </form>



<div id="results-items" class="mt-5">
  <div class="row m-0">
    <h2 class="col-12">{{ _e('Added Items list', 'premast') }}</h2>
    <div class="new-item row m-0 col-12 p-0"></div>
    @php 
      $args = array(
        'post_type'        => 'graphics',
        'posts_per_page'   => '5',
        'post_status'      => 'publish',
      );
      $posts = new WP_Query( $args );
    @endphp
    @if ( $posts->have_posts() )
      @while($posts->have_posts()) @php $posts->the_post() @endphp
        @php
          $term_name = wp_get_post_terms(get_the_ID(), 'graphics-category', array("fields" => "names"));
          $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), "full");
        @endphp
      <div class="media" id="{{ the_ID() }}">
        <div class="card">
          <div class="img-top-item">
            <img src="{{ $thumbnail[0] }}" alt="{{ the_title() }}">
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ the_title() }}</h5>
            <h5 class="card-term">{{ $term_name['0'] }}</h5>
            <div class="card-event">
              <a herf="#" class="item-deleted item-event" data-deleted="{{ the_ID() }}" data-nonce="<?php echo wp_create_nonce('testdel') ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a> 
            </div>
          </div>
        </div>
      </div>
      @endwhile
    @endif
  </div>
</div>


<script>
  jQuery(function($) {
    $('select').select2({
      theme: 'bootstrap4',
    });

    $('#tags').select2({
      theme: 'bootstrap4',
      tags: true,
      tokenSeparators: [',', ' '],
      placeholder: "selected tag...",
    });

    
    $('#submit').on('click',function(e){
      e.preventDefault();
      var thumbnail   = $('input[name="thumbnail"]').val();
      var file_url    = $('input[name="file_url"]').val();
      var title       = $('input[name="title"]').val();
      var tags        = $('#tags').val();
      var main_scat   = $('input[name="main_scat"]:checked').val();
          


      if(thumbnail == '' || file_url == '' || title == '' || tags == '' ) {
        $("#errors").show();
      } else {
        $.ajax({
          type:"POST",
          url:"<?php echo admin_url('admin-ajax.php'); ?>",
          data: {
            action: "graphics_add_front_end",
            thumbnail   : thumbnail,
            file_url    : file_url,
            title       : title,
            main_scat   : main_scat,
            tags        : tags,
          },
          beforeSend: function(results) {
            $('#add-loader').show();
          },
          success: function(results){
            location.reload(true);
          },
          error: function(results) {
            $('.register-message').html('plz try again later').show();
            $('#add-loader').hide();
          }
        });
      }
    });

    $('#results-items .new-item').on('click', '.item-deleted', function() {
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
