<a class="nav-item nav-link {{ request()->is('enseignant/evaluation/*') ? 'active' : '' }}" href="{{ route('evaluation.index') }}">
  <i class="fa fa-laptop me-2"></i>Evaluations
</a>
<a class="nav-item nav-link {{ request()->is('enseignant/moyenne/*') ? 'active' : '' }}" href="{{ route('moyennes.index') }}">
  <i class="fa fa-puzzle-piece me-2"></i>Moyennes
</a>
<a class="nav-item nav-link {{ request()->is('enseignant/devoirs/*') ? 'active' : '' }}" href="{{ route('devoirs.index') }}">
  <i class="fa fa-cube me-2" style="font-size: 19px;"></i>Devoirs
</a>