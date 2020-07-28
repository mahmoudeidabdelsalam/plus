{{--
  Template Name: Reset Password
--}}


@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp

  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-6 col-12 modal-forms">
        <div id="formBlock">
          @php the_content() @endphp
        </div>
      </div>
    </div>
  </div>


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

    .modal-forms #formBlock input {
      width: 100%;
    }

    legend {
      font-family: Roboto;
      font-style: normal;
      font-weight: bold;
      font-size: 30px;
      line-height: 36px;
      text-align: center;
      color: #000000;
    }

    .modal-forms #formBlock input {
      background: #F9F9F9;
      border: 1px solid #BEC6D2;
      box-sizing: border-box;
      border-radius: 4px;
      width: 100%;
    }

    div#formBlock button {
      background: #1E6DFB;
      border-radius: 4px;
      width: 100%;
      height: 40px;
      color: #fff;
    }
  </style>

  @endwhile
@endsection
