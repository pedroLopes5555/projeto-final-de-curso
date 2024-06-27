<header>
  <h4>HydroGrowthManager</h4>
  
  <!-- Admin in the top right corner of a flex container -->

  @if(isset($user))
  <div class="dropdown p-4 d-flex justify-content-end">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-toggle="dropdown" aria-expanded="false">
        <span class="d-none d-sm-inline mx-1">{{ $user->user_name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" id="signOut">
      <form method="POST" style="margin:0" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item">Terminar Sessão</button>
      </form>
    </ul>
   </div>
  @endif

</header>
