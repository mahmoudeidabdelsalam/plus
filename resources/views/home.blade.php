{{--
  Template Name: Home
--}}

@extends('layouts.app')

@section('content')

  <section class="banner-home">
    <div class="container">
      <div class="row align-content-center">
        <div class="col-md-5 col-12">
          <h2 id="headline" class="headline"><?= the_field('banner_headline'); ?></h2>
          <p class="subheadline"><?= the_field('banner_sub_headline'); ?></p>
          <a class="link-app" href="<?= the_field('banner_link'); ?>">
            <img src="<?= the_field('banner_link_image'); ?>" alt="<?= the_field('banner_headline'); ?>">
          </a>
        </div>
        <div class="col-md-7 col-12">
          <img class="img-fluid" src="<?= the_field('banner_image'); ?>" alt="<?= the_field('banner_headline'); ?>">
        </div>
      </div>
    </div>
  </section>

  <section class="benefits-home">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-12">
          <h2 class="headline text-center"><?= the_field('benefits_headline'); ?></h2>
          <p class="subheadline text-center"><?= the_field('benefits_sub_headline'); ?></p>
        </div>
      </div>
      <div class="row align-content-center list-items">
        <div class="col-md-5 col-12 align-self-center">
          <ul class="list-unstyled">
            <?php
              if( have_rows('benefits_list') ):
                while ( have_rows('benefits_list') ) : the_row(); 
            ?>
              <li class="item-list">
                <img src="<?= the_sub_field('list_icon'); ?>" alt="<?= the_sub_field('list_headline'); ?>">
                <p><span><?= the_sub_field('list_headline'); ?></span> <span><?= the_sub_field('list_subheadline'); ?></span></p>
              </li>
            <?php
                endwhile;
              endif;
            ?>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <img class="img-fluid" src="<?= the_field('benefits_image'); ?>" alt="<?= the_field('benefits_headline'); ?>">
        </div>
      </div>
    </div>
  </section>

  <section class="features-home">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-12">
          <h2 class="headline text-center"><?= the_field('features_headline'); ?></h2>
          <p class="subheadline text-center"><?= the_field('features_sub_headline'); ?></p>
        </div>
      </div>
      <div class="row align-content-center list-items">
        <?php
          if( have_rows('features_list') ):
            while ( have_rows('features_list') ) : the_row(); 
        ?>
          <div class="item-list col-md-4 col-12 text-center">
            <img src="<?= the_sub_field('features_list_image'); ?>" alt="<?= the_sub_field('features_list_headline'); ?>">
            <p><span><?= the_sub_field('features_list_headline'); ?></span> <span><?= the_sub_field('features_list_subheadline'); ?></span></p>
          </div>
        <?php
            endwhile;
          endif;
        ?>
      </div>
    </div>
  </section>

  <section class="footer-home" style="<?= the_field('background_color_footer'); ?>">
    <div class="overlay" style="background-image: url('<?= the_field('background_image_footer'); ?>')"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-12">
          <h2 class="headline text-center"><?= the_field('headline_footer'); ?></h2>
          <p class="subheadline text-center"><?= the_field('sub_headline_footer'); ?></p>
          <p class="text-center">
            <a class="link-app" href="<?= the_field('link_app_footer'); ?>">
              <img src="<?= the_field('image_app_footer'); ?>" alt="<?= the_field('headline_footer'); ?>">
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <style>
    section.features-home {
      min-height: 713px;
      background: #EFF6FA;
      padding: 70px 0;
    }
    section.banner-home .row {
      min-height: 600px;
    }
    section.benefits-home {
      background: #FBFBFB;
      padding: 70px 0;
    }
    section.benefits-home h1 {
      font-weight: 500;
      font-size: 37px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3E6FFF;
    }
    section.benefits-home .list-items {
      min-height: 900px;
    }
    section.banner-home #headline span {
      display: block;
      font-family: Roboto;
      font-style: normal;
      font-weight: bold;
      font-size: 64px;
      line-height: 75px;
      letter-spacing: 0.114083px;
      text-transform: capitalize;
      color: #282F39;
    }
    section.banner-home #headline span:nth-child(2) {
      color: #1f6dfb;
    }
    section.banner-home .subheadline {
      font-weight: 300;
      font-size: 24px;
      line-height: 31px;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3D4552;
    }
    section.benefits-home .list-items ul li {
      display: flex;
      align-items: center;
    }
    section.benefits-home .list-items ul li p span:nth-child(1) {
      display: block;
      font-weight: 500;
      font-size: 20px;
      line-height: 127.69%;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #282F39;
    }
    section.benefits-home .list-items ul li img {
      margin-right: 20px;
    }
    section.benefits-home .list-items ul li p {
      margin: 0;
    }
    section.benefits-home .list-items ul li {
      margin-bottom: 80px;
    }
    section.benefits-home .list-items ul li p span:nth-child(2) {
      font-weight: 300;
      font-size: 16px;
      line-height: 127.69%;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3D4552;
    }    
    section.benefits-home h2.headline.text-center,
    section.features-home h2.headline.text-center {
      font-weight: 500;
      font-size: 30px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3E6FFF;
    }   
    section.features-home .list-items {
      margin-top: 100px;
    } 
    section.features-home .list-items img {
        margin-bottom: 50px;
        height: 100px;
        width: auto;
    }    
    section.features-home .list-items ul li p span:nth-child(1) {
      font-weight: 500;
      font-size: 25px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #282F39;
    }
    section.features-home .list-items ul li p span:nth-child(2) {
      font-style: normal;
      font-weight: 300;
      font-size: 20px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3D4552;
    } 
    section.footer-home {
      padding: 70px 0;
      position: relative;
    }
    section.footer-home h2 {
      font-family: Roboto;
      font-style: normal;
      font-weight: 500;
      font-size: 30px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #EFF6FA;
    }
    section.footer-home p.subheadline {
      font-weight: 300;
      font-size: 20px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #EFF6FA;
    }
    .overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
    }
  </style>
  <script>
    jQuery(function($) {
      var headline = $('#headline').text().trim().split(' ');
      var text = "";
      var i;
      for (i = 0; i < headline.length; i++) {
        text += "<span>" + headline[i] + "</span>";
      }
      document.getElementById("headline").innerHTML = text;
    });
  </script>
@endsection
