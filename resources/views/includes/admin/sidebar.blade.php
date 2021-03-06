<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-user-graduate"></i>
    </div>
    <div class="sidebar-brand-text mx-3">DASHBOARD</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ \Route::currentRouteName() == 'home.admin' ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('home.admin') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Home</span></a>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->is('pengumuman') ? 'active' : (request()->is('pengumuman/*') ? 'active' : '') }}">
    <a class="nav-link" href="{{ route('pengumuman.index') }}">
        <i class="fas fa-bullhorn"></i>
        <span>Pengumuman</span></a>
    </li>

    <!-- Nav Item - Users -->
    <li class="nav-item {{ request()->is('admin/list/*') ? 'active' : null }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true" aria-controls="collapseUser">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <div id="collapseUser" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('all.guru') }}">Guru</a>
            <a class="collapse-item" href="{{ route('all.siswa') }}">Siswa</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->is('admin/kelas') ? 'active' : (request()->is('admin/kelas/*') ? 'active' : '') }}">
    <a class="nav-link" href="{{ route('kelas.index') }}">
        <i class="fas fa-chalkboard-teacher"></i>
        <span>Kelas</span></a>
    </li>

    <!-- Nav Item - Mapel -->
    <li class="nav-item {{ request()->is('admin/mapel') ? 'active' : (request()->is('admin/mapel/*') ? 'active' : '') }}">
    <a class="nav-link" href="{{ route('mapel.index') }}">
        <i class="fas fa-book-open"></i>
        <span>Mapel</span></a>
    </li>

    <!-- Nav Item - Assets -->
    <li class="nav-item {{ request()->is('assets/*') ? 'active' : (request()->is('admin/assets/*') ? 'active' : '') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAssets" aria-expanded="true" aria-controls="collapseAssets">
            <i class="fas fa-scroll"></i>
            <span>Assets</span>
        </a>
        <div id="collapseAssets" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('materi.index') }}">Materi</a>
            <a class="collapse-item" href="{{ route('soal.index') }}">Soal</a>
            <a class="collapse-item" href="{{ route('tugas.admin') }}">Tugas</a>
            </div>
        </div>
    </li>

<!-- Nav Item - Nilai -->
    <li class="nav-item {{ request()->is('admin/nilai') ? 'active' : (request()->is('admin/nilai/*') ? 'active' : '') }}">
    <a class="nav-link" href="{{ route('nilai.admin') }}">
        <i class="fas fa-star-half-alt"></i>
        <span>Nilai</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>