{{--
  Template Name: Plus Template
--}}

@extends('layouts.app')

@section('content')

@php 
  $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

  global $current_user;
  wp_get_current_user();
  $user = wp_get_current_user();
  $allowed_roles = array('vendor', 'administrator');
  $administrator = array('administrator');
@endphp

<div class="container-fiuld vh-100">
  <div class="row">
    <div class="col-3 side-tabs">
      <h1 class="logos">
        <a class="navbar-brand p-0 align-self-center col" href="{{ home_url('/') }}" title="{{ get_bloginfo('name') }}">
            <img class="img-fluid" src="{{ get_theme_file_uri().'/dist/images/logo-plus.png' }}" alt="{{ get_bloginfo('name', 'display') }}" title="{{ get_bloginfo('name') }}"/>
            <span class="sr-only"> {{ get_bloginfo('name') }} </span>
        </a>
      </h1>
      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fa fa-th-large" aria-hidden="true"></i> {{ _e('All items', 'premast') }}</a>
        @if (array_intersect($allowed_roles, $user->roles))
          <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-plus-square" aria-hidden="true"></i> {{ _e('Add Items', 'premast') }}</a>
        @endif
      </div>
    </div>
    <div class="col-9 p-0">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active all-items" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          @include('partials/incloud/all-item-plus')
        </div>
        @if (array_intersect($allowed_roles, $user->roles))
          <div class="tab-pane fade add-items" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            @include('partials/incloud/add-item-plus')
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
