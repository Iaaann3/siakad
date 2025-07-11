<script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

<div id="sidebar">
    <div class="sidebar-wrapper active d-flex flex-column" style="height: 100vh;">
        <!-- Header Sidebar -->
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="index.html"><img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo" srcset=""></a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <!-- Icon Light/Dark -->
                    <svg xmlns="http://www.w3.org/2000/svg" role="img" class="iconify iconify--system-uicons" width="20" height="20" viewBox="0 0 21 21">
                        <!-- Icon code... -->
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" role="img" class="iconify iconify--mdi" width="20" height="20" viewBox="0 0 24 24">
                        <!-- Icon code... -->
                    </svg>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <!-- Menu Sidebar -->
        <div class="sidebar-menu flex-grow-1">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.mapel.index') }}" class='sidebar-link'>
                        <i class="fas fa-book-open me-2"></i>
                        <span>Mapel</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.guru.index') }}" class='sidebar-link'>
                        <i class="fas fa-user-tie me-2"></i>
                        <span>Guru</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jurusan.index') }}" class='sidebar-link'>
                       <i class="fas fa-chalkboard-teacher"></i>
                        <span>Jurusan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kelas.index') }}" class='sidebar-link'>
                        <i class="fas fa-door-open me-2"></i>
                        <span>Kelas</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.siswa.index') }}" class='sidebar-link'>
                       <i class="fas fa-child"></i>
                        <span>Siswa</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal.index') }}" class='sidebar-link'>
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span>Jadwal</span>
                    </a>
                </li>
                 <li class="sidebar-item {{ request()->routeIs('admin.semester.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.semester.index') }}" class='sidebar-link'>
                        <i class="fas fa-layer-group me-2"></i>
                        <span>Semester</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('admin.jeniskeuangan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jeniskeuangan.index') }}" class='sidebar-link'>
                        <i class="fas fa-wallet me-2"></i>
                        <span>Jenis Keuangan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button -->
        <div class="p-3">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin logout?')">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
