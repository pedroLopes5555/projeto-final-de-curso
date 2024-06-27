<?php $menu_open = $menu_open ?? ''; ?>
<aside>

  <div class="sidebar-item"><a href="/admin" style="padding-left:10px; border-bottom: 1px solid rgba(255, 255, 255, 0.6);">Dashboard</a></div>
  <div class="sidebar-item-group @if($menu_open == 'containers') open @endif">
    <div class="sidebar-item">Contentores</div>
    <div class="sidebar-subitems">
      <div class="sidebar-item"><a href="/admin/containers">Contentores</a></div>
      <div class="sidebar-item"><a href="/admin/arduinos">Microcontroladores</a></div>
    </div>
  </div>

	@if($user->canSee('admin'))
   <div class="sidebar-item-group @if($menu_open == 'admin') open @endif">
    <div class="sidebar-item">Admin</div>
    <div class="sidebar-subitems">
      <div class="sidebar-item"><a href="/admin/users">Utilizadores</a></div>
      <div class="sidebar-item"><a href="/admin/perms">Permissões</a></div>
    </div>
  </div>
	 @endif


</aside>
