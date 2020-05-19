{{--
  Template Name: Home
--}}

@extends('layouts.app')

@section('content')

  <section class="banner-home mt-3" style="background-image: url('<?= the_field('banner_image'); ?>')">
    <div class="container">
      <div class="row align-content-center">
        <div class="col-md-12 col-12 text-center">
          <h2 id="headline" class="headline"><?= the_field('banner_headline'); ?> <img class="srat-icon" src="{{ get_theme_file_uri().'/resources/assets/images/' }}star.png" alt="<?= the_field('banner_headline'); ?>"></h2>
          <p class="subheadline"><?= the_field('banner_sub_headline'); ?></p>
          <a class="link-app" href="<?= the_field('banner_link'); ?>">
            <img src="<?= the_field('banner_link_image'); ?>" alt="<?= the_field('banner_headline'); ?>">
          </a>
        </div>

        <div class="col-md-12 col-12">
          <a class="popup-youtube" href="<?= the_field('video_link'); ?>?autoplay=1&rel=0" style="background-image: url('<?= the_field('video_image'); ?>')">
            <i class="fa fa-play-circle fa-3x" aria-hidden="true"></i>
            <span>Play Introduction Video</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="benefits-home">
    <img class="bg-pattern" src="{{ get_theme_file_uri().'/resources/assets/images/' }}Pattern.png" alt="<?= the_field('benefits_headline'); ?>">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-12">
          <h2 class="headline text-center"><?= the_field('benefits_headline'); ?></h2>
          <p class="text-center">
            <img class="srat-icon" src="{{ get_theme_file_uri().'/resources/assets/images/' }}border.png" alt="<?= the_field('benefits_headline'); ?>">
          </p>
        </div>
      </div>
      <div class="row align-content-center list-items">
        <?php
          if( have_rows('benefits_list') ):
            while ( have_rows('benefits_list') ) : the_row(); 
        ?>
          <div class="item-list col-md-3 col-sm-6 col-12">
            <img src="<?= the_sub_field('list_icon'); ?>" alt="<?= the_sub_field('list_headline'); ?>">
            <p><span><?= the_sub_field('list_headline'); ?></span> <span><?= the_sub_field('list_subheadline'); ?></span></p>
          </div>
        <?php
            endwhile;
          endif;
        ?>
      </div>
    </div>
  </section>

  <section class="features-home">
    <img class="bg-pattern-top" src="{{ get_theme_file_uri().'/resources/assets/images/' }}Pattern.png" alt="<?= the_field('benefits_headline'); ?>">
    <div class="container">
      <?php
        if( have_rows('features_list') ):
          while ( have_rows('features_list') ) : the_row(); 
      ?>
        <div class="row align-content-center list-items">
          <div class="col-md-6 col-12 align-self-center">
            <h3><?= the_sub_field('features_list_headline'); ?></h3> 
            <p><?= the_sub_field('features_list_subheadline'); ?></p>
          </div>
          <div class="col-md-6 col-12">
            <img src="<?= the_sub_field('features_list_image'); ?>" alt="<?= the_sub_field('features_list_headline'); ?>">
          </div>
        </div>
      <?php
          endwhile;
        endif;
      ?>
    </div>
    <img class="bg-pattern-bottom" src="{{ get_theme_file_uri().'/resources/assets/images/' }}Pattern.png" alt="<?= the_field('benefits_headline'); ?>">
  </section>

  <section class="footer-home">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-12 text-center">
          <img class="m-auto img-fluid" src="<?= the_field('image_app_footer'); ?>" alt="<?= the_field('headline_footer'); ?>">
          <h2 class="headline text-center"><?= the_field('headline_footer'); ?></h2>
          <p class="subheadline text-center"><?= the_field('sub_headline_footer'); ?></p>
          <p class="text-center">
            <a class="link-app" href="<?= the_field('banner_link'); ?>">
              <img src="<?= the_field('banner_link_image'); ?>" alt="<?= the_field('banner_headline'); ?>">
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <style>
    section.features-home {
      background: #F0F5FF;
      padding: 70px 0;
      position: relative;
    }
    img.bg-pattern-top {
      position: absolute;
      top: -30px;
      left: 100px;
    }
    img.bg-pattern-bottom {
      position: absolute;
      bottom: -51px;
      z-index: 9;
      left: 100px;
      width: 100px;
    }    
    section.banner-home {
      min-height: 600px;
      background-size: contain;
      background-position: bottom;   
      background-repeat: no-repeat;
    }
    section.benefits-home h2 {
      font-family: Roboto;
      font-style: normal;
      font-weight: 500;
      font-size: 40px;
      line-height: 47px;
      text-transform: capitalize;
      color: #000000;
    }
    section.benefits-home {
      padding-bottom: 60px;
      position: relative;
    }
    img.bg-pattern {
      top: 10px;
      right: 100px;
      position: absolute;
    }
    section.benefits-home .list-items {
      margin-top: 70px;
    }
    section.benefits-home .list-items .item-list {
      text-align: center;
    }
    section.benefits-home .list-items .item-list p span:nth-child(1) {
      display: block;
      font-family: Roboto;
      font-style: normal;
      font-weight: normal;
      font-size: 24px;
      line-height: 32px;
      text-align: center;
      color: #282F39;
      margin: 30px 0 10px;
    }    
    section.banner-home #headline {
      font-weight: 500;
      font-size: 40px;
      line-height: 47px;
      text-transform: capitalize;
      color: #000000;
    }
    section.banner-home .subheadline {
      font-style: normal;
      font-weight: normal;
      font-size: 16px;
      line-height: 24px;
      letter-spacing: 0.04px;
      color: #000000;
    }  
    section.features-home .list-items h3 {
      font-weight: 500;
      font-size: 25px;
      line-height: 127.69%;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #282F39;
    }
    section.features-home .list-items p {
      font-style: normal;
      font-weight: 300;
      font-size: 20px;
      line-height: 127.69%;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #3D4552;
    } 
    section.features-home .list-items:nth-child(2) {
      flex-direction: row-reverse!important;
      margin: 50px 0;
    }    
    section.footer-home {
      padding: 70px 0;
      position: relative;
    }
    section.footer-home h2 {
      font-weight: 500;
      font-size: 40px;
      line-height: 47px;
      text-align: center;
      text-transform: capitalize;
      color: #000000;
      margin: 50px 0 0 0;
    }
    section.footer-home p.subheadline {
      font-weight: 300;
      font-size: 20px;
      line-height: 127.69%;
      text-align: center;
      letter-spacing: -0.3px;
      text-transform: capitalize;
      color: #000;
      margin: 0 0 30px 0;
    }
    .overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
    }

    /* Magnific Popup CSS */
    .mfp-bg {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 502;
      overflow: hidden;
      position: fixed;
      background: #0b0b0b;
      opacity: 0.8;
      filter: alpha(opacity=80);
    }

    .mfp-wrap {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 503;
      position: fixed;
      outline: none !important;
      -webkit-backface-visibility: hidden;
    }

    .mfp-container {
      height: 100%;
      text-align: center;
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      padding: 0 8px;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }

    .mfp-container:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
    }

    .mfp-align-top .mfp-container:before {
      display: none;
    }

    .mfp-content {
      position: relative;
      display: inline-block;
      vertical-align: middle;
      margin: 0 auto;
      text-align: left;
      z-index: 505;
    }

    .mfp-inline-holder .mfp-content,
    .mfp-ajax-holder .mfp-content {
      width: 100%;
      cursor: auto;
    }

    .mfp-ajax-cur {
      cursor: progress;
    }

    .mfp-zoom-out-cur,
    .mfp-zoom-out-cur .mfp-image-holder .mfp-close {
      cursor: -moz-zoom-out;
      cursor: -webkit-zoom-out;
      cursor: zoom-out;
    }

    .mfp-zoom {
      cursor: pointer;
      cursor: -webkit-zoom-in;
      cursor: -moz-zoom-in;
      cursor: zoom-in;
    }

    .mfp-auto-cursor .mfp-content {
      cursor: auto;
    }

    .mfp-close,
    .mfp-arrow,
    .mfp-preloader,
    .mfp-counter {
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    .mfp-loading.mfp-figure {
      display: none;
    }

    .mfp-hide {
      display: none !important;
    }

    .mfp-preloader {
      color: #cccccc;
      position: absolute;
      top: 50%;
      width: auto;
      text-align: center;
      margin-top: -0.8em;
      left: 8px;
      right: 8px;
      z-index: 504;
    }

    .mfp-preloader a {
      color: #cccccc;
    }

    .mfp-preloader a:hover {
      color: white;
    }

    .mfp-s-ready .mfp-preloader {
      display: none;
    }

    .mfp-s-error .mfp-content {
      display: none;
    }

    button.mfp-close,
    button.mfp-arrow {
      overflow: visible;
      cursor: pointer;
      background: transparent;
      border: 0;
      -webkit-appearance: none;
      display: block;
      padding: 0;
      z-index: 506;
    }

    button::-moz-focus-inner {
      padding: 0;
      border: 0;
    }

    .mfp-close {
      width: 44px;
      height: 44px;
      line-height: 44px;
      position: absolute;
      right: 0;
      top: 0;
      text-decoration: none;
      text-align: center;
      opacity: 0.65;
      padding: 0 0 18px 10px;
      color: white;
      font-style: normal;
      font-size: 28px;
      font-family: Arial, Baskerville, monospace;
    }

    .mfp-close:hover,
    .mfp-close:focus {
      opacity: 1;
    }

    .mfp-close:active {
      top: 1px;
    }

    .mfp-close-btn-in .mfp-close {
      color: #333333;
    }

    .mfp-image-holder .mfp-close,
    .mfp-iframe-holder .mfp-close {
      color: white;
      right: -6px;
      text-align: right;
      padding-right: 6px;
      width: 100%;
    }

    .mfp-counter {
      position: absolute;
      top: 0;
      right: 0;
      color: #cccccc;
      font-size: 12px;
      line-height: 18px;
    }

    .mfp-arrow {
      position: absolute;
      top: 0;
      opacity: 0.65;
      margin: 0;
      top: 50%;
      margin-top: -55px;
      padding: 0;
      width: 90px;
      height: 110px;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .mfp-arrow:active {
      margin-top: -54px;
    }

    .mfp-arrow:hover,
    .mfp-arrow:focus {
      opacity: 1;
    }

    .mfp-arrow:before,
    .mfp-arrow:after,
    .mfp-arrow .mfp-b,
    .mfp-arrow .mfp-a {
      content: '';
      display: block;
      width: 0;
      height: 0;
      position: absolute;
      left: 0;
      top: 0;
      margin-top: 35px;
      margin-left: 35px;
      border: solid transparent;
    }

    .mfp-arrow:after,
    .mfp-arrow .mfp-a {
      opacity: 0.8;
      border-top-width: 12px;
      border-bottom-width: 12px;
      top: 8px;
    }

    .mfp-arrow:before,
    .mfp-arrow .mfp-b {
      border-top-width: 20px;
      border-bottom-width: 20px;
    }

    .mfp-arrow-left {
      left: 0;
    }

    .mfp-arrow-left:after,
    .mfp-arrow-left .mfp-a {
      border-right: 12px solid black;
      left: 5px;
    }

    .mfp-arrow-left:before,
    .mfp-arrow-left .mfp-b {
      border-right: 20px solid white;
    }

    .mfp-arrow-right {
      right: 0;
    }

    .mfp-arrow-right:after,
    .mfp-arrow-right .mfp-a {
      border-left: 12px solid black;
      left: 3px;
    }

    .mfp-arrow-right:before,
    .mfp-arrow-right .mfp-b {
      border-left: 20px solid white;
    }

    .mfp-iframe-holder {
      padding-top: 40px;
      padding-bottom: 40px;
    }

    .mfp-iframe-holder .mfp-content {
      line-height: 0;
      width: 100%;
      max-width: 900px;
    }

    .mfp-iframe-scaler {
      width: 100%;
      height: 0;
      overflow: hidden;
      padding-top: 56.25%;
    }

    .mfp-iframe-scaler iframe {
      position: absolute;
      top: -3px;
      left: 0;
      width: 100%;
      height: 100%;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
      background: black;
    }

    .mfp-iframe-holder .mfp-close {
      top: -43px;
    }

    /* Main image in popup */
    img.mfp-img {
      width: auto;
      max-width: 100%;
      height: auto;
      display: block;
      line-height: 0;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
      padding: 40px 0 40px;
      margin: 0 auto;
    }

    /* The shadow behind the image */
    .mfp-figure:after {
      content: '';
      position: absolute;
      left: 0;
      top: 40px;
      bottom: 40px;
      display: block;
      right: 0;
      width: auto;
      height: auto;
      z-index: -1;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
    }

    .mfp-figure {
      line-height: 0;
    }

    .mfp-bottom-bar {
      margin-top: -36px;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      cursor: auto;
    }

    .mfp-title {
      text-align: left;
      line-height: 18px;
      color: #f3f3f3;
      word-break: break-word;
      padding-right: 36px;
    }

    .mfp-figure small {
      color: #bdbdbd;
      display: block;
      font-size: 12px;
      line-height: 14px;
    }

    .mfp-image-holder .mfp-content {
      max-width: 100%;
    }

    .mfp-gallery .mfp-image-holder .mfp-figure {
      cursor: pointer;
    }

    @media screen and (max-width: 800px) and (orientation: landscape),
    screen and (max-height: 300px) {

      /**
      * Remove all paddings around the image on small screen
      */
      .mfp-img-mobile .mfp-image-holder {
        padding-left: 0;
        padding-right: 0;
      }

      .mfp-img-mobile img.mfp-img {
        padding: 0;
      }

      /* The shadow behind the image */
      .mfp-img-mobile .mfp-figure:after {
        top: 0;
        bottom: 0;
      }

      .mfp-img-mobile .mfp-bottom-bar {
        background: rgba(0, 0, 0, 0.6);
        bottom: 0;
        margin: 0;
        top: auto;
        padding: 3px 5px;
        position: fixed;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }

      .mfp-img-mobile .mfp-bottom-bar:empty {
        padding: 0;
      }

      .mfp-img-mobile .mfp-counter {
        right: 5px;
        top: 3px;
      }

      .mfp-img-mobile .mfp-close {
        top: 0;
        right: 0;
        width: 35px;
        height: 35px;
        line-height: 35px;
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        text-align: center;
        padding: 0;
      }

      .mfp-img-mobile .mfp-figure small {
        display: inline;
        margin-left: 5px;
      }
    }

    @media all and (max-width: 800px) {
      .mfp-arrow {
        -webkit-transform: scale(0.75);
        transform: scale(0.75);
      }

      .mfp-arrow-left {
        -webkit-transform-origin: 0;
        transform-origin: 0;
      }

      .mfp-arrow-right {
        -webkit-transform-origin: 100%;
        transform-origin: 100%;
      }

      .mfp-container {
        padding-left: 6px;
        padding-right: 6px;
      }
    }

    .mfp-ie7 .mfp-img {
      padding: 0;
    }

    .mfp-ie7 .mfp-bottom-bar {
      width: 600px;
      left: 50%;
      margin-left: -300px;
      margin-top: 5px;
      padding-bottom: 5px;
    }

    .mfp-ie7 .mfp-container {
      padding: 0;
    }

    .mfp-ie7 .mfp-content {
      padding-top: 44px;
    }

    .mfp-ie7 .mfp-close {
      top: 0;
      right: 0;
      padding-top: 0;
    }
    .popup-youtube {
      height: 500px;
      display: flex;
      width: 100%;
      justify-content: center;
      align-items: center;
      flex-flow: column;
      font-size: 24px;
      line-height: 32px;
      color: #FFFFFF;
      margin: 100px 0;
    }
  </style>


@endsection
