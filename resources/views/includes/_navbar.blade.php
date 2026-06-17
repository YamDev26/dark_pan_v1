<div class="sidebar pe-4 pb-3">
  <nav class="navbar bg-secondary navbar-dark">
    <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
      <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>{{ config('app.name') }}</h3>
    </a>
    <div class="navbar-nav w-100">
      <a class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
      </a>
      @if (auth()->user()->role->libelle == 'SuperAdmin')
        <a class="nav-item nav-link {{ request()->is('school/*') ? 'active' : '' }}" href="{{ route('school.index') }}">
        <i class="fa fa-graduation-cap me-2" style="font-size: 19px;"></i>School
        </a>
        <a class="nav-item nav-link {{ request()->is('cutting/*') ? 'active' : '' }}" href="{{ route('cutting.index') }}">
          <i class="fa fa-cube me-2" style="font-size: 19px;"></i>Découpage
        </a>
        <a class="nav-item nav-link {{ request()->is('school_year/*') ? 'active' : '' }}" href="{{ route('school_year.index') }}">
          <i class="fab fa-yelp me-2" style="font-size: 21px;"></i>Année Scolaire
        </a>
        <a class="nav-item nav-link {{ request()->is('country/*') ? 'active' : '' }}" href="{{ route('country.index') }}">
          <i class="fa fa-globe me-2" style="font-size: 21px;"></i>Nationalité
        </a>
        <a class="nav-item nav-link {{ request()->is('dren/*') ? 'active' : '' }}" href="{{ route('dren.index') }}">
          <i class="fa fa-list-alt me-2" style="font-size: 21px;"></i>Dren
        </a>
      @else
        <a class="nav-item nav-link {{ request()->is('evaluated/*') ? 'active' : '' }}" href="{{ route('evaluated.index') }}">
          <i class="fa fa-laptop me-2"></i>Evaluations
        </a>
        <a class="nav-item nav-link {{ request()->is('moyenne/*') ? 'active' : '' }}" href="{{ route('moyenne.index') }}">
          <i class="fa fa-chart-bar me-2"></i>Moyennes
        </a>
        <a class="nav-item nav-link {{ request()->is('resultat/*') ? 'active' : '' }}" href="{{ route('resultat.index') }}">
          <i class="fa fa-table me-2"></i>Resultats
        </a>
        <a class="nav-item nav-link {{ request()->is('horraire/*') ? 'active' : '' }}" href="{{ route('horraire.index') }}">
          <i class="fa fa-briefcase me-2"></i>Horaires
        </a>
        <a class="nav-item nav-link {{ request()->is('register/*') ? 'active' : '' }}" href="{{ route('register.index') }}">
          <i class="fa fa-edit me-2"></i>Inscriptions
        </a>
        <a class="nav-item nav-link {{ request()->is('classe/*') ? 'active' : '' }}" href="{{ route('classe.index') }}">
          <i class="fa fa-th me-2"></i>Classes
        </a>
        <a class="nav-item nav-link {{ request()->is('student/*') ? 'active' : '' }}" href="{{ route('student.index') }}">
          <i class="fa fa-graduation-cap me-2"></i>Elèves
        </a>
        <a class="nav-item nav-link {{ request()->is('teacher/*') ? 'active' : '' }}" href="{{ route('teacher.index') }}">
          <i class="fa fa-user me-2"></i>Enseignants
        </a>
        <a href="chart.html" class="nav-item nav-link">
          <i class="fa fa-users me-2"></i>Personnels
        </a>
        <div class="nav-item dropdown" title="Configurations">
          <a href="#" class="nav-link dropdown-toggle {{ request()->is(['setting/*', 'slot/*', 'level/*']) ? 'active' : '' }}" data-bs-toggle="dropdown">
            <i class="fa fa-cogs me-2"></i>Configs
          </a>
          <div class="dropdown-menu bg-transparent border-0 {{ request()->is(['setting/*', 'slot/*', 'level/*']) ? 'show' : '' }}">
            <a href="{{ route('level.index') }}" class="dropdown-item {{ request()->is('level/*') ? 'active' : '' }}">Disciplines</a>
            <a href="{{ route('slot.index') }}" class="dropdown-item {{ request()->is('slot/*') ? 'active' : '' }}">Slot Times</a>
            <a href="{{ route('setting.index') }}" class="dropdown-item {{ request()->is('setting/*') ? 'active' : '' }}">School</a>
          </div>
        </div>
      @endif
    </div>
  </nav>
</div>