{{--
  Template Name: Sign Up
--}}


@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp

  @if ( is_user_logged_in() ) 
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-6 col-12 modal-forms">
          <div id="formBlock">
            <img src="{{ get_theme_file_uri().'/resources/assets/images' }}/thanks.png" alt="icon thanks">
            <h3>Your account has been registered!</h3>
            <p>Thanks for using premast plus. <br> Now you can access our plugin anytime</p>
            <a class="link-app" href="<?= the_field('link_app_install', 'option'); ?>">
              <img src="<?= the_field('img_app_install', 'option'); ?>" alt="install app">
            </a>
          </div>
        </div>
      </div>
    </div>
  @else 
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-6 col-12 modal-forms">
          <div id="formBlock">
            <h3>Create New Account</h3>
            <p>Make stunning PowerPoint designs faster than ever.</p>
            @include('partials.content-page')
          </div>
        </div>
      </div>
    </div>
  @endif


  <style>
    .modal-forms {
      height: 100vh;
      min-height: 600px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .modal-forms h2 {
      display: none;
    }

    .modal-forms label {
      display: none;
    }

    .modal-forms #formBlock {
      background: #FFFFFF;
      box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.2);
      border-radius: 4px;
      width: 100%;
      padding: 30px;
      text-align: center;
    }

    div#reg_forms {
      width: 100%;
    }

    .modal-forms #formBlock input {
        background: #F9F9F9;
        border: 1px solid #BEC6D2;
        box-sizing: border-box;
        border-radius: 4px;
        height: 40px;
    }

    .modal-forms #formBlock input[type="submit"] {
      background: #1E6DFB;
      border-radius: 4px;
      color: #fff;
      width: 100% !important;
      margin: 0 !important;
      height: 40px;
    }

    div#formBlock h3 {
      font-family: Roboto;
      font-style: normal;
      font-weight: bold;
      font-size: 30px;
      line-height: 36px;
      color: #000000;
      margin: 30px 0;
    }
  </style>

  @endwhile
@endsection
