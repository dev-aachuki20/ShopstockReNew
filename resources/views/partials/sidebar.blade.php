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

        <li class="dropdown {{ Request::is('admin/customer*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <x-side-bar-svg-icon icon="user" />
                <span>@lang('quickadmin.customer-management.title')</span>
            </a> 
            <ul class="dropdown-menu"> 
                @if (Gate::check('customer_create'))
                    <li class="{{ Request::is('admin/customers/create') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customers.create') }}">
                            @lang('quickadmin.customer-management.fields.add')
                        </a>
                    </li>
                @endif  
                @if (Gate::check('customer_access'))    
                <li class="{{ Request::is('admin/customers') ||  Request::is('admin/customers/*/edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.customers.index') }}">
                        @lang('quickadmin.customer-management.fields.alter_list')
                    </a>
                </li>
                <li class="{{ Request::is('admin/customer/list') || Request::is('admin/customer/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.customer_list') }}">
                        @lang('quickadmin.customer-management.fields.list')
                    </a>
                </li>
                @endif       
             </ul>
        </li>





      
            
           


        <li class="dropdown {{ Request::is('admin/master*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <x-side-bar-svg-icon icon="user" />
                <span>@lang('quickadmin.master-management.title')</span>
            </a> 
            <ul class="dropdown-menu">
               
                @if (Gate::check('group_access'))
                    <li class="{{ Request::is('admin/master/groups*') || Request::is('admin/master/group-recycle*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.groups.index') }}">
                            @lang('quickadmin.group_master.title')
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/master/sub-groups*') || Request::is('admin/master/sub-group-recycle*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.sub_group.index') }}">
                            @lang('quickadmin.group_master.sub_title')
                        </a>
                    </li>
                @endif
                {{-- @if (Gate::check('category_access'))
                    <li class="{{ Request::is('admin/master/categories*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.categories.index') }}">
                            @lang('quickadmin.category_master.title')
                        </a>
                    </li>
                @endif --}}
                @if (Gate::check('unit_access'))
                    <li class="{{ Request::is('admin/master/product-unit*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.product-unit.index') }}">
                            @lang('quickadmin.product_unint_master.title')
                        </a>
                    </li>
                @endif
                @if (Gate::check('product_access'))
                    <li class="{{ Request::is('admin/master/products*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.products.index') }}">
                            @lang('admin_master.product.seo_title_product_master')
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
                
                {{-- @if (Gate::check('split_access'))
                    <li class="{{ Request::is('admin/master/split*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.split.index') }}">
                            @lang('quickadmin.split.title')
                        </a>
                    </li>
                @endif --}}
                @if (Gate::check('log_access'))
                    <li class="{{ Request::is('admin/master/log-activity*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.log-activity.index') }}">
                            @lang('quickadmin.logActivities.title')
                        </a>
                    </li>
                @endif
                
                @if (Gate::check('ip_access'))
                    <li class="{{ Request::is('admin/master/role_ip*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.master.role_ip.index') }}">
                            @lang('quickadmin.ip.title')
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
