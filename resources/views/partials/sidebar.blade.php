<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="#">
          {{-- <div class="circleimg"><img alt="image" src="{{asset('admintheme/assets/img/shopping-bag.png') }}" class="header-logo" /></div> --}}
          <span>{{ getSetting('company_name') ?? ''}}</span>
        </a>
      </div>
      <ul class="sidebar-menu">
        <li class="menu-header">Main</li>
        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <x-side-bar-svg-icon icon="dashboard" />
                <span>@lang('quickadmin.qa_dashboard')</span>
            </a>
        </li>

        @can('role_access')
        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
            <a href="{{route('roles.index')}}" class="nav-link">
                <x-side-bar-svg-icon icon="user" />
                <span>@lang('quickadmin.roles.title')</span></a>
        </li>
        @endcan

        @can('staff_access')
        <li class="{{ Request::is('staff*') ? 'active' : '' }}">
            <a href="{{ route('staff.index') }}" class="nav-link">
                <x-side-bar-svg-icon icon="staff" />
                <span>@lang('quickadmin.user-management.title')</span>
            </a>
        </li>
        @endcan


        @can('setting_access')
        <li class="{{ Request::is('settings*') ? 'active' : '' }}">
            <a href="{{ route('settings') }}" class="nav-link">
                <x-side-bar-svg-icon icon="setting" />
                <span>@lang('quickadmin.settings.title')</span>
            </a>
        </li>
        @endcan






        <li class="treeview {{ request()->is('admin/master*') || request()->is('admin/products*') || request()->is('admin/areas*') ? 'active' : '' }}">
            <a href="#">
                <i class="fa fa-asterisk"></i>
                <span>@lang('quickadmin.master-management.title')</span>
                <span class="pull-right-container">
                    <i class="fa  fa-angle-left pull-right"></i>
                </span>
            </a> 
            <ul class="treeview-menu {{ request()->is('admin/master*') ? 'menu-open' : '' }}">
                    <li class="{{ request()->is('admin/master/brands*') ? 'active menu-open' : '' }}">
                        <a href="{{ route('brands.index') }}">
                            <i class="fa fa-user"></i>
                            <span>@lang('quickadmin.brand_master.title')</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/master/categories*') ? 'active menu-open' : '' }}">
                        <a href="{{ route('categories.index') }}">
                            <i class="fa fa-list"></i>
                            <span>@lang('quickadmin.category_master.title')</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/master/categories*') ? 'active menu-open' : '' }}">
                        <a href="{{ route('areas.index') }}">
                            <i class="fa fa-list"></i>
                            <span>Area</span>
                        </a>
                    </li>
            </ul>
        </li>






        <li class="{{ Request::is('logout*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('logout') }}">
                <x-side-bar-svg-icon icon="logout" />
                <span>@lang('quickadmin.qa_logout')</span>
            </a>
        </li>
    </ul>

    </aside>
  </div>
