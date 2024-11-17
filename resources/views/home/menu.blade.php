<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- App Brand -->
    <div class="app-brand demo my-4">
        <a href="{{ url('/home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="https://www.topnetschool.tn/wp-content/uploads/2020/10/logo-topnet-2.png" alt="Topnet Logo"
                    class="" style="width: 200px;">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <li class="menu-item active">
        <a href="{{ url('/home') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
        </a>
    </li>

    <!-- Pages Menu Header -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Pages</span>
    </li>

    <!-- Opportunities Menu -->
    <li class="menu-item">
        <a href="{{ url('/opportunities') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-briefcase-alt"></i>
            <div data-i18n="Tables">Opportunités</div>
        </a>
    </li>

    <!-- Offers Menu -->
    <li class="menu-item">
        <a href="{{ url('/offers') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-gift"></i>
            <div data-i18n="Tables">Offres</div>
        </a>
    </li>

    <!-- Users -->
    <li class="menu-item">
        <a href="{{ url('/users') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Tables">Utilisateurs</div>
        </a>
    </li>

    <!-- Roles Menu -->
    <li class="menu-item">
        <a href="{{ url('/roles') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-lock"></i>
            <div data-i18n="Tables">Rôles</div>
        </a>
    </li>

    <!-- Permissions Menu -->
    <li class="menu-item">
        <a href="{{ url('/permissions') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-shield"></i>
            <div data-i18n="Tables">Permissions</div>
        </a>
    </li>

    <!-- Audit Logs Menu -->
    <li class="menu-item">
        <a href="{{ url('/audit-logs') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-search"></i>
            <div data-i18n="Tables">Audit Logs</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="{{ url('/chat') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-chat"></i>
            <div data-i18n="Tables">Chat</div>
        </a>
    </li>
</aside>
