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


       






      
            
           


        <li class="dropdown {{ Request::is('admin/master*', 'admin/products*', 'admin/areas*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <x-side-bar-svg-icon icon="user" />
                <span>@lang('quickadmin.master-management.title')</span>
            </a> 
            <ul class="dropdown-menu">
                @if (Gate::check('brand_access'))  
                    <li class="{{ Request::is('admin/master/brands*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.brands.index') }}">
                            @lang('quickadmin.brand_master.title')
                        </a>
                    </li>
                @endif
                @if (Gate::check('group_access'))
                    <li class="{{ Request::is('admin/master/groups*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.groups.index') }}">
                            @lang('quickadmin.group_master.title')
                        </a>
                    </li>
                @endif
                @if (Gate::check('category_access'))
                    <li class="{{ Request::is('admin/master/categories*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.categories.index') }}">
                            @lang('quickadmin.category_master.title')
                        </a>
                    </li>
                @endif
                @if (Gate::check('area_access'))
                    <li class="{{ Request::is('admin/master/areas*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.areas.index') }}">
                            @lang('quickadmin.area_master.title')
                        </a>
                    </li>
                @endif
            </ul>
        </li>



        @can('setting_access')
        <li class="{{ Request::is('settings*') ? 'active' : '' }}">
            <a href="{{ route('settings') }}" class="nav-link">
                <x-side-bar-svg-icon icon="setting" />
                <span>@lang('quickadmin.settings.title')</span>
            </a>
        </li>
        @endcan




        <li class="{{ Request::is('logout*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('logout') }}">
                <x-side-bar-svg-icon icon="logout" />
                <span>@lang('quickadmin.qa_logout')</span>
            </a>
        </li>
    </ul>

    </aside>
  </div>
