<aside class="sidebar">
    <div class="logo-details">
        <img class="brand-icon" src="{{ asset('bolopa/back/images/icon/twemoji--coconut.svg') }}" alt="Cocofarma" />
        <div class="logo_name">Cocofarma</div>
        <!-- Use SVG image for hamburger to support swapping between open/closed visuals -->
        <img id="btn" src="{{ asset('bolopa/back/images/icon/line-md--menu-fold-right.svg') }}" alt="menu"
            style="width:28px;height:28px;cursor:pointer;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(63deg) brightness(108%) contrast(103%);" />
    </div>
    <!-- Menu group switch: toggle between Operasional and Master -->
    <div class="menu-group-switch" aria-hidden="false">
        <button type="button" id="menuGroupOperational"
            class="group-btn {{ request()->routeIs('backoffice.pesanan.*') || request()->routeIs('backoffice.produksi.*') || request()->routeIs('backoffice.transaksi.*') || request()->routeIs('backoffice.laporan.*') ? 'active' : '' }}"
            title="Tampilkan Operasional">
            <img class="group-icon" src="{{ asset('bolopa/back/images/icon/line-md--home-md.svg') }}" alt="operasional"
                style="width:18px;height:18px;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(63deg) brightness(108%) contrast(103%);" />
            <span class="group-text">Operasional</span>
        </button>
        @if (Auth::check() && Auth::user()->role === 'super_admin')
            <button type="button" id="menuGroupMaster"
                class="group-btn {{ request()->routeIs('backoffice.master-produk.*') || request()->routeIs('backoffice.master-user.*') || request()->routeIs('backoffice.pengaturan.*') || request()->routeIs('backoffice.bahanbaku.*') ? 'active' : '' }}"
                title="Tampilkan Master">
                <img class="group-icon" src="{{ asset('bolopa/back/images/icon/line-md--monitor-screenshot.svg') }}"
                    alt="master"
                    style="width:18px;height:18px;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(63deg) brightness(108%) contrast(103%);" />
                <span class="group-text">Master</span>
            </button>
        @endif
    </div>

    {{-- Ensure the correct menu group is selected on first load based on the current route (without overwriting an existing user preference) --}}
    @if (request()->routeIs('backoffice.pesanan.*') ||
            request()->routeIs('backoffice.produksi.*') ||
            request()->routeIs('backoffice.transaksi.*') ||
            request()->routeIs('backoffice.laporan.*'))
        <script>
            if (!localStorage.getItem('sidebar-group')) localStorage.setItem('sidebar-group', 'operational');
        </script>
    @elseif(request()->routeIs('backoffice.master-produk.*') ||
            request()->routeIs('backoffice.master-user.*') ||
            request()->routeIs('backoffice.pengaturan.*') ||
            request()->routeIs('backoffice.bahanbaku.*'))
        @if (Auth::check() && Auth::user()->role === 'super_admin')
            <script>
                if (!localStorage.getItem('sidebar-group')) localStorage.setItem('sidebar-group', 'master');
            </script>
        @endif
    @endif
    <ul class="nav-list">
        <!-- search removed -->

        <!-- Primary Dashboard Link -->
        <li>
            <a href="{{ Route::has('backoffice.dashboard') ? route('backoffice.dashboard') : '#' }}"
                class="{{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}">
                <i class="bx bx-grid-alt"></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>

        <!-- Operasional Group -->
        <li class="menu-group-title" data-group="operational">
            <span class="links_name group-full"
                style="opacity:1;color:#bbb;padding:8px 12px;font-size:13px;">Operasional</span>
            <span class="links_name group-short"
                style="display:none;opacity:1;color:#bbb;padding:8px 12px;font-size:13px;">-</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.pesanan.index') ? route('backoffice.pesanan.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.pesanan.*') ? 'active' : '' }}">
                <i class="bx bx-cart-alt"></i>
                <span class="links_name">Pesanan</span>
            </a>
            <span class="tooltip">Pesanan</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.produk.index') ? route('backoffice.produk.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.produk.*') ? 'active' : '' }}">
                <i class="bx bx-box"></i>
                <span class="links_name">Produk</span>
            </a>
            <span class="tooltip">Produk</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.bahanbaku.index') ? route('backoffice.bahanbaku.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.bahanbaku.*') ? 'active' : '' }}">
                <i class="bx bx-package"></i>
                <span class="links_name">Bahan Baku</span>
            </a>
            <span class="tooltip">Bahan Baku</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.produksi.index') ? route('backoffice.produksi.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.produksi.*') ? 'active' : '' }}">
                <i class="bx bx-cog"></i>
                <span class="links_name">Produksi</span>
            </a>
            <span class="tooltip">Produksi</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.transaksi.index') ? route('backoffice.transaksi.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.transaksi.*') ? 'active' : '' }}">
                <i class="bx bx-wallet"></i>
                <span class="links_name">Penjualan / Transaksi</span>
            </a>
            <span class="tooltip">Penjualan</span>
        </li>
        <li data-group="operational">
            <a href="{{ Route::has('backoffice.laporan.index') ? route('backoffice.laporan.index') : '#' }}"
                class="{{ request()->routeIs('backoffice.laporan.*') ? 'active' : '' }}">
                <i class="bx bx-line-chart"></i>
                <span class="links_name">Laporan</span>
            </a>
            <span class="tooltip">Laporan</span>
        </li>

        @if (Auth::check() && Auth::user()->role === 'super_admin')
            <!-- Master Group -->
            <li class="menu-group-title" data-group="master">
                <span class="links_name group-full"
                    style="opacity:1;color:#bbb;padding:8px 12px;font-size:13px;">Master</span>
                <span class="links_name group-short"
                    style="display:none;opacity:1;color:#bbb;padding:8px 12px;font-size:13px;">-</span>
            </li>
            <li data-group="master">
                <a href="{{ Route::has('backoffice.master-produk.index') ? route('backoffice.master-produk.index') : '#' }}"
                    class="{{ request()->routeIs('backoffice.master-produk.*') ? 'active' : '' }}">
                    <i class="bx bx-package"></i>
                    <span class="links_name">Produk</span>
                </a>
                <span class="tooltip">Produk</span>
            </li>
            <li data-group="master">
                <a href="{{ Route::has('backoffice.master-bahan.index') ? route('backoffice.master-bahan.index') : '#' }}"
                    class="{{ request()->routeIs('backoffice.master-bahan.*') ? 'active' : '' }}">
                    <i class="bx bx-package"></i>
                    <span class="links_name">Bahan Baku</span>
                </a>
                <span class="tooltip">Bahan Baku</span>
            </li>
            <li data-group="master">
                <a href="{{ Route::has('backoffice.master-user.index') ? route('backoffice.master-user.index') : '#' }}"
                    class="{{ request()->routeIs('backoffice.master-user.*') ? 'active' : '' }}">
                    <i class="bx bx-user-circle"></i>
                    <span class="links_name">User & Hak Akses</span>
                </a>
                <span class="tooltip">User & Hak Akses</span>
            </li>
            <li data-group="master">
                <a href="{{ Route::has('backoffice.pengaturan.index') ? route('backoffice.pengaturan.index') : '#' }}"
                    class="{{ request()->routeIs('backoffice.pengaturan.*') ? 'active' : '' }}">
                    <i class="bx bx-slider-alt"></i>
                    <span class="links_name">Pengaturan Sistem</span>
                </a>
                <span class="tooltip">Pengaturan</span>
            </li>
        @endif

        <li class="profile">
            <div class="profile-details">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                <div class="name_job">
                    @php
                        $firstName = 'Admin';
                        if (Auth::check() && !empty(Auth::user()->name)) {
                            $parts = preg_split('/\s+/', trim(Auth::user()->name));
                            $firstName = $parts[0] ?? Auth::user()->name;
                        }
                    @endphp
                    <div class="name">{{ $firstName }}</div>
                    <div class="job">{{ Auth::user()->role ?? '' }}</div>
                </div>
            </div>
            <form id="sidebar-logout-form" action="{{ route('backoffice.logout') }}" method="POST"
                style="display:none;">
                @csrf
            </form>
            <a href="#" class="logout-link"
                onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                title="Logout">
                <i class="bx bx-log-out" id="log_out"></i>
            </a>
        </li>
    </ul>
</aside>

<style>
    /* Global font is loaded in the main layout (Poppins). Keep styles font-agnostic so the layout controls typography. */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: inherit;
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 78px;
        background: #11101D;
        padding: 6px 14px;
        z-index: 99;
        transition: all 0.5s ease;
    }

    .sidebar.open {
        width: 250px;
    }

    .sidebar .logo-details {
        height: 60px;
        display: flex;
        align-items: center;
        position: relative;
        padding-right: 44px;
        /* leave room on the right for the hamburger */
        justify-content: center;
        /* center content horizontally */
    }

    .sidebar .logo-details .icon {
        opacity: 0;
        transition: all 0.5s ease;
    }

    .sidebar .logo-details .logo_name {
        color: #fff;
        font-size: 20px;
        font-weight: 600;
        opacity: 0;
        transition: all 0.5s ease;
    }

    .sidebar .logo-details .brand-icon {
        width: 28px;
        height: 28px;
        margin-right: 8px;
        filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(63deg) brightness(108%) contrast(103%);
        flex-shrink: 0;
        transition: right .18s ease, transform .18s ease;
    }

    /* When sidebar is collapsed, move the brand icon next to the hamburger with a small gap */
    .sidebar:not(.open) .logo-details .brand-icon {
        position: absolute;
        right: 30px;
        /* leave a small gap from the hamburger (hamburger is at right:0) */
        top: 50%;
        transform: translateY(-50%);
        margin-right: 0;
    }

    /* When sidebar is open, ensure brand icon participates in layout (not absolute) */
    .sidebar.open .logo-details .brand-icon {
        position: static;
        transform: none;
    }

    .sidebar.open .logo-details .icon,
    .sidebar.open .logo-details .logo_name {
        opacity: 1;
    }

    .sidebar .logo-details #btn {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        font-size: 22px;
        transition: all 0.4s ease;
        font-size: 23px;
        text-align: center;
        cursor: pointer;
        transition: all 0.5s ease;
        z-index: 1100;
        /* ensure hamburger stays on top */
    }

    .sidebar.open .logo-details #btn {
        text-align: right;
    }

    .sidebar i {
        color: #fff;
        height: 60px;
        min-width: 50px;
        font-size: 28px;
        text-align: center;
        line-height: 60px;
    }

    .sidebar .nav-list {
        margin-top: 20px;
        height: 100%;
    }

    .sidebar li {
        position: relative;
        margin: 8px 0;
        list-style: none;
    }

    .sidebar li .tooltip {
        position: absolute;
        top: -20px;
        left: calc(100% + 15px);
        z-index: 3;
        background: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 15px;
        font-weight: 400;
        opacity: 0;
        white-space: nowrap;
        pointer-events: none;
        transition: 0s;
    }

    .sidebar li:hover .tooltip {
        opacity: 1;
        pointer-events: auto;
        transition: all 0.4s ease;
        top: 50%;
        transform: translateY(-50%);
    }

    .sidebar.open li .tooltip {
        display: none;
    }

    .sidebar input {
        font-size: 15px;
        color: #FFF;
        font-weight: 400;
        outline: none;
        height: 50px;
        width: 100%;
        width: 50px;
        border: none;
        border-radius: 12px;
        transition: all 0.5s ease;
        background: #1d1b31;
    }

    .sidebar.open input {
        padding: 0 20px 0 50px;
        width: 100%;
    }

    .sidebar .bx-search {
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        font-size: 22px;
        background: #1d1b31;
        color: #FFF;
    }

    .sidebar.open .bx-search:hover {
        background: #1d1b31;
        color: #FFF;
    }

    .sidebar .bx-search:hover {
        background: #FFF;
        color: #11101d;
    }

    .sidebar li a {
        display: flex;
        height: 100%;
        width: 100%;
        border-radius: 12px;
        align-items: center;
        text-decoration: none;
        transition: all 0.4s ease;
        background: #11101D;
    }

    .sidebar li a:hover {
        background: #FFF;
    }

    /* Make server-side `active` class visible for sidebar links */
    .sidebar li a.active {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    .sidebar li a.active i,
    .sidebar li a.active .links_name {
        color: #fff;
    }

    .sidebar li a .links_name {
        color: #fff;
        font-size: 15px;
        font-weight: 400;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: 0.4s;
    }

    .sidebar.open li a .links_name {
        opacity: 1;
        pointer-events: auto;
    }

    .sidebar li a:hover .links_name,
    .sidebar li a:hover i {
        transition: all 0.5s ease;
        color: #11101D;
    }

    .sidebar li i {
        height: 50px;
        line-height: 50px;
        font-size: 18px;
        border-radius: 12px;
    }

    .sidebar li.profile {
        position: fixed;
        height: 60px;
        width: 78px;
        left: 0;
        bottom: -8px;
        padding: 10px 14px;
        background: #1d1b31;
        transition: all 0.5s ease;
        overflow: hidden;
    }

    .sidebar.open li.profile {
        width: 250px;
    }

    .sidebar li .profile-details {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }

    .sidebar li .avatar {
        height: 45px;
        width: 45px;
        border-radius: 6px;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar li.profile .name,
    .sidebar li.profile .job {
        font-size: 15px;
        font-weight: 400;
        color: #fff;
        white-space: nowrap;
    }

    .sidebar li.profile .job {
        font-size: 12px;
    }

    .sidebar .profile #log_out {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        background: #1d1b31;
        width: 100%;
        height: 60px;
        line-height: 60px;
        border-radius: 0px;
        transition: all 0.5s ease;
    }

    .sidebar.open .profile #log_out {
        width: 50px;
        background: none;
    }

    .home-section {
        position: relative;
        background: #E4E9F7;
        min-height: 100vh;
        top: 0;
        left: 78px;
        width: calc(100% - 78px);
        transition: all 0.5s ease;
        z-index: 2;
    }

    .sidebar.open~.home-section {
        left: 250px;
        width: calc(100% - 250px);
    }

    .home-section .text {
        display: inline-block;
        color: #11101d;
        font-size: 25px;
        font-weight: 500;
        margin: 18px
    }

    @media (max-width: 420px) {
        .sidebar li .tooltip {
            display: none;
        }
    }
</style>

<!-- Move toggle behavior into DOMContentLoaded to avoid attaching listeners before DOM is ready -->

<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 78px;
        height: 100vh;
        background: #2f2f31;
        color: white;
        transition: width 0.3s ease;
        z-index: 1050;
        overflow: hidden;
    }

    .sidebar.open {
        width: 250px;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logo svg {
        color: white;
        flex-shrink: 0;
    }

    .logo-text {
        font-weight: bold;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar.open .logo-text {
        opacity: 1;
    }

    .toggle-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background 0.2s ease;
    }

    .toggle-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .profile-info {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar.open .profile-info {
        opacity: 1;
    }

    .name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .role {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .sidebar-nav {
        padding: 1rem 0;
        height: calc(100vh - 140px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .nav-section {
        padding: 0 1rem;
    }

    .section-title {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar.open .section-title {
        opacity: 1;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-list li {
        margin-bottom: 0.25rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        position: relative;
        justify-content: center;
    }

    .sidebar:not(.open) .nav-link span {
        display: none;
    }

    .sidebar:not(.open) .nav-link .badge {
        display: none;
    }

    .nav-link:hover,
    .nav-link.active {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .nav-link i {
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .nav-link span {
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar.open .nav-link span {
        opacity: 1;
    }

    .badge {
        margin-left: auto;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Tooltip for collapsed state */
    .sidebar:not(.open) .nav-link::after {
        content: attr(title);
        position: absolute;
        left: 78px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(47, 47, 49, 0.95);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        z-index: 2000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        font-size: 0.85rem;
    }

    .sidebar:not(.open) .nav-link:hover::after {
        opacity: 1;
        transform: translateY(-50%) translateX(8px);
    }

    .nav-section-bottom {
        margin-top: auto;
    }

    /* Content adjustment */
    .content-with-sidebar {
        margin-left: 78px;
        transition: margin-left 0.3s ease;
    }

    .sidebar.open~.content-with-sidebar {
        margin-left: 250px;
    }

    @media (max-width: 992px) {
        .sidebar {
            display: none;
        }

        .content-with-sidebar {
            margin-left: 0;
        }
    }
</style>

<!-- Removed duplicate toggle script that referenced non-existing #sidebarToggle. -->

<style>
    /* Menu group switch styles */
    .menu-group-switch {
        display: flex;
        gap: 6px;
        padding: 8px 10px;
        justify-content: center;
        align-items: center;
    }

    .menu-group-switch .group-btn {
        background: transparent;
        color: #cfcfcf;
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
    }

    .menu-group-switch .group-btn {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Always load icon element; show/hide text depending on collapsed state */
    .menu-group-switch .group-btn .group-icon {
        display: inline-block !important;
        width: 18px;
        height: 18px;
        vertical-align: middle;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    .menu-group-switch .group-btn .group-text {
        display: inline;
    }

    /* When sidebar is collapsed, hide the text and show the small icon (icon already visible) */
    .sidebar:not(.open) .menu-group-switch .group-btn .group-text {
        display: none;
    }

    .menu-group-switch .group-btn.active {
        background: rgba(255, 255, 255, 0.08);
        color: white;
        border-color: rgba(255, 255, 255, 0.14);
    }

    /* Show short dash when sidebar is collapsed */
    .menu-group-title .group-short {
        display: none;
    }

    .menu-group-title .group-full {
        display: inline;
    }

    .sidebar:not(.open) .menu-group-title .group-full {
        display: none;
    }

    .sidebar:not(.open) .menu-group-title .group-short {
        display: inline;
    }

    /* By default show group items so the menu is usable if JS fails. When JS runs it will add
    the `js-enabled` class to `.nav-list` and JS will then control visibility via `.visible`. */
    .nav-list li[data-group] {
        display: list-item;
    }

    .nav-list.js-enabled li[data-group] {
        display: none;
    }

    .nav-list.js-enabled li[data-group].visible {
        display: list-item;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navList = document.querySelector('.nav-list');
        const menuOperational = document.getElementById('menuGroupOperational');
        const menuMaster = document.getElementById('menuGroupMaster');
        const menuItems = navList ? navList.querySelectorAll('li[data-group]') : [];
        const sidebarEl = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('btn');

        // Restore sidebar open/closed state
        const sidebarState = localStorage.getItem('sidebar-open');
        if (sidebarState === '1') {
            sidebarEl.classList.add('open');
        } else if (sidebarState === '0') {
            sidebarEl.classList.remove('open');
        }

        // Setup SVG sources for open/closed states
        const svgRight = '{{ asset('bolopa/back/images/icon/line-md--menu-fold-right.svg') }}';
        const svgLeft = '{{ asset('bolopa/back/images/icon/line-md--menu-fold-left.svg') }}';
        if (toggleBtn && toggleBtn.tagName === 'IMG') {
            // Ensure the initial icon matches the state
            toggleBtn.src = sidebarEl.classList.contains('open') ? svgLeft : svgRight;
        }

        // Restore selected menu group (default operational)
        let selectedGroup = localStorage.getItem('sidebar-group') || 'operational';
        // If master button is not present (user is not super_admin) and saved group is 'master', fallback
        if (selectedGroup === 'master' && !menuMaster) {
            selectedGroup = 'operational';
        }

        function applySelectedGroup(group) {
            if (menuOperational) {
                menuOperational.classList.toggle('active', group === 'operational');
            }
            if (menuMaster) {
                menuMaster.classList.toggle('active', group === 'master');
            }

            menuItems.forEach(item => {
                if (item.dataset.group === group) {
                    item.classList.add('visible');
                } else {
                    item.classList.remove('visible');
                }
            });

            localStorage.setItem('sidebar-group', group);
        }

        // Mark navList as JS-enabled so CSS will allow JS-controlled visibility
        if (navList) {
            navList.classList.add('js-enabled');
        }

        applySelectedGroup(selectedGroup);

        // Click handlers (attach only if the buttons exist)
        if (menuOperational) {
            menuOperational.addEventListener('click', function() {
                applySelectedGroup('operational');
            });
        }
        if (menuMaster) {
            menuMaster.addEventListener('click', function() {
                applySelectedGroup('master');
            });
        }

        // Persist toggle button state and swap SVG icon
        if (toggleBtn && sidebarEl) {
            toggleBtn.addEventListener('click', function() {
                const isOpen = sidebarEl.classList.toggle('open');
                localStorage.setItem('sidebar-open', isOpen ? '1' : '0');
                if (toggleBtn.tagName === 'IMG') {
                    toggleBtn.src = isOpen ? svgLeft : svgRight;
                }
            });
        }

        // Ensure the menu group remains applied when keyboard toggled
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                const isOpen = sidebarEl.classList.toggle('open');
                localStorage.setItem('sidebar-open', isOpen ? '1' : '0');
            }
        });
    });
</script>
