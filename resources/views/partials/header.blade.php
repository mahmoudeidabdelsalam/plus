<nav class="navbar navbar-light bg-light">
  <div class="container">
    <h1 class="logos mr-auto">
      <a class="navbar-brand p-0 align-self-center col" href="{{ home_url('/') }}" title="{{ get_bloginfo('name') }}">
        <img class="img-fluid" src="{{ get_theme_file_uri().'/dist/images/logo-plus.png' }}" alt="{{ get_bloginfo('name', 'display') }}" title="{{ get_bloginfo('name') }}"/>
        <span class="sr-only"> {{ get_bloginfo('name') }} </span>
      </a>
    </h1>
    @if ( is_user_logged_in() )
      <a href="https://appsource.microsoft.com/en-us/product/office/WA200001396?src=office&tab=Overview" class="create-account">Install Add-in</a>
    @else 
      <a href="<?= the_field('sign_up_link', 'option'); ?>" class="create-account">Create account</a>
    @endif
  </div>
</nav>


<style>
  .create-account {
    border: 2px solid #282F39;
    border-radius: 4px;
    font-weight: 500;
    font-size: 16px;
    line-height: 21px;
    text-align: center;
    letter-spacing: 0.04px;
    color: #282F39;
    padding: 10px 20px;
  }
  .create-account:hover {
    color: #282F39;
    border-radius: 0 8px 8px 8px;
  }

</style>