<a class="nav-item nav-link {{ request()->is('school/*') ? 'active' : '' }}" href="{{ route('school.index') }}">
  <i class="fa fa-graduation-cap me-2" style="font-size: 19px;"></i>School
</a>
<a class="nav-item nav-link {{ request()->is('cutting/*') ? 'active' : '' }}" href="{{ route('cutting.index') }}">
  <i class="fa fa-cube me-2" style="font-size: 19px;"></i>Période Scolaire
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