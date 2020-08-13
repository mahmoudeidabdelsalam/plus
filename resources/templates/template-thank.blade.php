{{--
  Template Name: Template thanks
--}}


@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <div class="container">
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="title">
            <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
            <h2>Your account has been registered! </h2>
            <h5>Thanks for using premast plus. <br>Now you can access our plugin anytime</br></h5>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <section>
      <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="use">
              <span><i class="fa fa-question-circle" aria-hidden="true"></i></span>
              <h5>How to use premast plus </h5>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-md-4 col-sm-12">
            <div class="content-wrap">
              <h5>open the plugin within powerpoint and login with your account you just created</h5>
              <img src="{{ get_theme_file_uri().'/resources/assets/images' }}/first.png">
            </div>
          </div>
          <div class="col-md-4">
            <div class="nd-content-wrap">
              <h5>Select the type of assets you need, you can <br>add slides, icons, illustrations and images</h5>
              <img src="{{ get_theme_file_uri().'/resources/assets/images' }}/Frame 254 (1).png">
            </div>
          </div>
          <div class="col-md-4">
            <div class="rd-content-wrap">
              <h5>Customize the items as you need, you can edit colos, text and shapes</h5>
              <img src="{{ get_theme_file_uri().'/resources/assets/images' }}/third.png">
            </div>
          </div>
        </div>
        <div class="row justify-content-center m-5">
          <div class="inst-button">
          <a href="{{ the_field('link_app_install', 'option') }}" class="btn btn-primary" style="background-image:url({{ get_theme_file_uri().'/resources/assets/images' }}/bag.png); background-repeat: no-repeat; padding-left: 34px;  border: 5px solid transparent;box-sizing: border-box;">Install Plugin</a>
          </div>
        </div>
      </div>
    </section>


    <style>
      .title h2 {
        font-style: normal;
        font-weight: bold;
        font-size: 30px;
        line-height: 36px;
        color: #000000;
        width: 100%;
      }

      span {
        float: left;
        margin-left: -47px;
      }


      i.fa.fa-check-circle {
        background: linear-gradient(90deg, #02AAB0 0%, #00CDAC 100%), #25AE88;
        -webkit-text-fill-color: transparent;
        -webkit-background-clip: text;
        font-size: 40px;
      }

      .title h5 {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.04px;
        color: #646464;
        padding-left: 9px;
      }

      hr {
        border-top: 1px solid #BEC6D2;
        width: 90%;
      }

      .use h5 {
        font-style: normal;
        font-weight: normal;
        font-size: 24px;
        line-height: 32px;
        color: #000000;
      }

      i.fa.fa-question-circle {
        font-size: 35px;

      }

      .fa-question-circle:before {
        background: linear-gradient(141.33deg, #1FA2FF -4.21%, #274FDB 135.73%);
        -webkit-text-fill-color: transparent;
        -webkit-background-clip: text;
      }

      .content-wrap h5 {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.04px;
        color: #646464;
        width: 95%;
      }

      .content-wrap h5::before {
        content: "1";
        position: absolute;
        font-size: 14px;
        top: 0;
        left: -5%;
        background: linear-gradient(141.33deg, #1FA2FF -4.21%, #274FDB 135.73%);
        height: 27px;
        width: 27px;
        line-height: 2em;
        text-align: center;
        vertical-align: center;
        font-weight: bold;
        color: #F9F9F9;
        border-radius: 2em;
      }

      .nd-content-wrap h5::before {
        content: "2";
        position: absolute;
        font-size: 14px;
        top: 0;
        left: -5%;
        background: linear-gradient(141.33deg, #1FA2FF -4.21%, #274FDB 135.73%);
        height: 27px;
        width: 27px;
        line-height: 2em;
        text-align: center;
        vertical-align: center;
        font-weight: bold;
        color: #F9F9F9;
        border-radius: 2em;
      }

      .nd-content-wrap h5 {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.04px;
        color: #646464;
        width: 95%;
      }

      .rd-content-wrap h5 {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.04px;
        color: #646464;
        width: 95%;

      }

      .rd-content-wrap h5::before {
        content: "3";
        position: absolute;
        font-size: 14px;
        top: 0;
        left: -5%;
        background: linear-gradient(141.33deg, #1FA2FF -4.21%, #274FDB 135.73%);
        height: 27px;
        width: 27px;
        line-height: 2em;
        text-align: center;
        vertical-align: center;
        font-weight: bold;
        color: #F9F9F9;
        border-radius: 2em;
      }

      img {
        max-width: 100%;
      }

      .btn.btn-primary {
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 20px;
        letter-spacing: 0.04px;
        color: #FFFFFF;
      }

    </style>
  @endwhile
@endsection

