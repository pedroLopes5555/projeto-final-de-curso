<?php $menu_open = $menu_open ?? ''; ?>
<aside>

	{{-- @if($loginUser->canSee('books')) --}}
  <div class="sidebar-item-group @if($menu_open == 'containers') open @endif">
    <div class="sidebar-item">Containers</div>
    <div class="sidebar-subitems">
      <div class="sidebar-item"><a href="/admin/containers">Containers</a></div>
      <div class="sidebar-item"><a href="/admin/arduinos">Arduinos</a></div>
    </div>
  </div>

	<div class="sidebar-item-group @if($menu_open == 'sensors') open @endif">
		<div class="sidebar-item">Sensors</div>
		<div class="sidebar-subitems">
			<div class="sidebar-item"><a href="/admin/sensors">Sensors</a></div>
    </div>
  </div>

	{{-- @endif --}}

	{{-- @if($loginUser->canSee('admin')) --}}
   <div class="sidebar-item-group @if($menu_open == 'admin') open @endif">
    <div class="sidebar-item">Admin</div>
    <div class="sidebar-subitems">
      <div class="sidebar-item"><a href="/admin/users">Utilizadores</a></div>
      <div class="sidebar-item"><a href="/admin/perms">Permissões</a></div>
    </div>
  </div>
	{{-- @endif --}}


</aside>
