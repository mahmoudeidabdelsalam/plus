{{--
  Template Name: Sign Up
--}}


@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp

    <div class="custom-header">
    <div class="elementor-background-overlay" style="background-image:url('{{ the_field('header_section_image', 'option') }}')"></div>
      @include('partials.page-header')
    </div>

    <div class="container-fluid">
      <div class="row align-items-center">
    
        <div class="col-md-5 col-12 hv-100" style="background-image: linear-gradient(150deg, #56ecf2 0%, #4242e3 100%);">
          <div class="elementor-background-overlay" style="background-image: url('https://premast.com/app/uploads/2019/04/16952-Converted-01.png');"></div>
          <div class="col-12 description">
            <h4 class="text-white title-description">Welcome to premast</h4>
            <p class="text-white text-description mb-3">Join us and enjoy with this benefits</p> 
            <p class="text-white min-description">* Recieve a 20% off discount in your E-mail</p>
            <p class="text-white min-description">* Downloads hunderds of powerpoint slides and graphics for free</p>
            <p class="text-white min-description">* Discover amazing new products daily</p>
          </div>
        </div>

        <div class="col-md-7 col-12 hv-100">
          <a class="navbar-brand" href="https://premast.com/" title="Premast">
            <img class="img-fluid" src=" https://premast.com/app/uploads/2019/04/1-copy-4.png " alt="Premast" title="Premast">
            <span class="sr-only"> Premast </span>
          </a>
          <br>
          <h5 class="modal-title" id="SignupUserLabel">Create a New Premast Account</h5>
          @include('partials.content-page')
        </div>
        
      </div>
    </div>

    <style>
      .hv-100 {
        height: 100vh;
        padding: 100px 15px;
        min-height: 600px;
      }
      .form-group.submit-button button {
        padding: 10px 20px;
        color: #fff;
      }      
    </style>
  @endwhile
@endsection
