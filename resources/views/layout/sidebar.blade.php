<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    <div class="app-brand demo">
        {{-- <a href="{{ url('/') }}" class="app-brand-link christmasLogo" style="display:none;">
            <span class="app-brand-logo demo"></span> --}}
            <span class="app-brand-text demo menu-text fw-bolder text-uppercase">
                {{ config('app.name') }}
            </span>
        {{-- </a> --}}
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>
    <!-- <div class="menu-inner-shadow"></div> -->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item @if (request()->routeIs('home')) active @endif">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>       
    </ul>
</aside>