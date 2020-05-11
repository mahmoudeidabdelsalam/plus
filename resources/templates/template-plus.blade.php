{{--
  Template Name: Plus Template
--}}

@extends('layouts.app')

@section('content')

@php 
  $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
  $item   = isset($_GET['item']) ? $_GET['item'] : '';
  $add   = isset($_GET['add']) ? $_GET['add'] : '';
  global $current_user;
  wp_get_current_user();
  $user = wp_get_current_user();
  $allowed_roles = array('vendor', 'administrator');
  $administrator = array('administrator');
  $link = get_the_permalink();
@endphp

<div class="container-fiuld vh-100">
  <div class="row">
    <div class="col-md-3 col-12 side-tabs">
      <h1 class="logos">
        <a class="navbar-brand p-0 align-self-center col" href="{{ home_url('/') }}" title="{{ get_bloginfo('name') }}">
            <img class="img-fluid" src="{{ get_theme_file_uri().'/dist/images/logo-plus.png' }}" alt="{{ get_bloginfo('name', 'display') }}" title="{{ get_bloginfo('name') }}"/>
            <span class="sr-only"> {{ get_bloginfo('name') }} </span>
            <span>Dashboard</span>
        </a>
      </h1>



      <div class="nav nav-side">
        @if($item)
          <a class="nav-link active" href="{{$link}}?item=all">
            <i class="fa fa-th-large" aria-hidden="true"></i> {{ _e('All items', 'premast') }} <i class="fa fa-caret-down" aria-hidden="true"></i>
          </a>
          <a class="sub-items {{ ($item == 'all')? 'is-active':'' }}" href="{{$link}}?item=all">All items</a>
          <a class="sub-items {{ ($item == 'pending')? 'is-active':'' }}" href="{{$link}}?item=pending">Pending</a>
          <a class="sub-items {{ ($item == 'rejected')? 'is-active':'' }}" href="{{$link}}?item=rejected">Rejected</a>
        @else 
          <a class="nav-link" href="{{$link}}?item=all">
            <i class="fa fa-th-large" aria-hidden="true"></i> {{ _e('All items', 'premast') }} <i class="fa fa-caret-up" aria-hidden="true"></i>
          </a>
        @endif
        @if (array_intersect($allowed_roles, $user->roles))
        @if($add)
          <a class="nav-link active" href="{{$link}}?add=new"><i class="fa fa-plus-square" aria-hidden="true"></i> {{ _e('Add Items', 'premast') }}</a>
          @else 
          <a class="nav-link" href="{{$link}}?add=new"><i class="fa fa-plus-square" aria-hidden="true"></i> {{ _e('Add Items', 'premast') }}</a>
        @endif
        @endif
      </div>



    </div>

    <div class="col-md-9 col-12 p-0">
      <div class="tab-content">
        @if($item)
          <div class="all-items">
            @include('partials/incloud/all-item-plus')
          </div>
        @endif
        @if($add)
          @if (array_intersect($allowed_roles, $user->roles))
            <div class="add-items">
              @include('partials/incloud/add-item-plus')
            </div>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
