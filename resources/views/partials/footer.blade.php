<footer class="bg-dark">
  <div class="container">
    <div class="row">
      <h1 class="logos">
        <a class="navbar-brand p-0 align-self-center col" href="{{ home_url('/') }}" title="{{ get_bloginfo('name') }}">
          <img class="img-fluid" src="{{ get_theme_file_uri().'/dist/images/logo-light.png' }}" alt="{{ get_bloginfo('name', 'display') }}" title="{{ get_bloginfo('name') }}"/>
          <span class="sr-only"> {{ get_bloginfo('name') }} </span>
        </a>
      </h1>
      @if (has_nav_menu('footer_navigation'))
        {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'container' => false, 'menu_class' => 'menu-footer', 'walker' => new NavWalker()]) !!}
      @endif
      @if( have_rows('social_networks', 'option') )
        <ul class="list-inline social-btns">
          @while ( have_rows('social_networks', 'option') ) @php the_row(); @endphp
            <li class="list-inline-item"><a class="network" href="{{ the_sub_field('network_link', 'option') }}"><i class="fa {{ the_sub_field('network_icon', 'option') }}"></i></a></li>
          @endwhile
        </ul>
      @endif
    </div>
  </div>
</footer>

<style>
  ul.menu-footer {
    padding: 0;
    margin: 0;
    align-self: center!important;
  }
  ul.menu-footer li a {
    font-family: Roboto;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 127.69%;
    letter-spacing: -0.3px;
    text-transform: capitalize;
    color: #FFFFFF;
  }
  ul.social-btns {
    margin: 0;
    padding: 0;
    align-self: center!important;
    margin-left: auto;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  ul.social-btns li {
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FFFFFF;
    border-radius: 4px;
    font-size: 25px;
  }
  ul.social-btns li a {
    color: #343a40;
  }
</style>