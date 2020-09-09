@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.content-page')
  @endwhile
    <script>
    jQuery(function($) {
      var client = new ClientJS();


      var Browser = client.getCPU();

      console.log(client);



     });
  </script>
@endsection
