<nav class="navbar navbar-light bg-light">
  <div class="container">
    <h1 class="logos mr-auto">
      <a class="navbar-brand p-0 align-self-center col" href="{{ home_url('/') }}" title="{{ get_bloginfo('name') }}">
        <img class="img-fluid" src="{{ get_theme_file_uri().'/dist/images/logo-plus.png' }}" alt="{{ get_bloginfo('name', 'display') }}" title="{{ get_bloginfo('name') }}"/>
        <span class="sr-only"> {{ get_bloginfo('name') }} </span>
      </a>
    </h1>
    <a href="#" class="create-account">Create account</a>
  </div>
</nav>


<style>
  .create-account {
    background: linear-gradient(90deg, #5433FF 0%, #20BDFF 100%);
    box-shadow: 0px 2px 15px rgba(30, 109, 251, 0.25);
    border-radius: 8px 8px 8px 0;
    padding: 10px;
    font-family: Roboto;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 19px;
    text-align: center;
    letter-spacing: 0.114083px;
    text-transform: capitalize;
    color: #FFFFFF;
  }
  .create-account:hover {
    color: #FFFFFF;
    background: linear-gradient(90deg, #20BDFF 0%, #5433FF 100%);
    border-radius: 0 8px 8px 8px;
  }

</style>