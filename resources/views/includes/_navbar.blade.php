<div class="sidebar pe-4 pb-3">
  <nav class="navbar bg-secondary navbar-dark">
    <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
      <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>{{ config('app.name') }}</h3>
    </a>
    <div class="navbar-nav w-100">
      <a class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
      </a>

      @include('partials.menus.'.getUserMenus())

    </div>
  </nav>
</div>