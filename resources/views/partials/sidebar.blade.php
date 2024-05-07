<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">
                {{-- <div class="circleimg"><img alt="image" src="{{asset('admintheme/assets/img/shopping-bag.png') }}" class="header-logo" />
        </div> --}}
        <span>{{ getSetting('company_name') ?? ''}}</span>
        </a>
</div>
<ul class="sidebar-menu">
    <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <x-side-bar-svg-icon icon="dashboard" />
            <span>@lang('quickadmin.qa_dashboard')</span>
        </a>
    </li>

    {{-- @can('role_access')
    <li class="{{ Request::is('roles*') ? 'active' : '' }}">
        <a href="{{route('roles.index')}}" class="nav-link">
            <x-side-bar-svg-icon icon="user" />
            <span>@lang('quickadmin.roles.title')</span></a>
    </li>
    @endcan --}}

    @can('staff_access')
    <li class="{{ Request::is('staff*') ? 'active' : '' }}">
        <a href="{{ route('staff.index') }}" class="nav-link">
            <x-side-bar-svg-icon icon="staff" />
            <span>@lang('quickadmin.user-management.title')</span>
        </a>
    </li>
    @endcan

    @can('product_access')
    <li class="{{ Request::is('admin/master/products*') || Request::is('admin/master/product-group*') || Request::is('admin/master/product-price*') || Request::is('admin/master/product-recycle*') || Request::is('admin/master/product-price*' || Request::is('admin/master/product-group/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.master.products.index') }}">
            <x-side-bar-svg-icon icon="device" />
            <span>@lang('admin_master.product.seo_title_product_master')</span>
        </a>
    </li>
    @endcan

    @can('estimate_management_access')
    <li class="dropdown {{ ((Request::is('admin/orders*') && !Request::is('admin/orders/draft-invoice')) || Request::is('admin/transactions/create') || Request::is('admin/orders-return')) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-side-bar-svg-icon icon="backup" />
            <span>@lang('quickadmin.order-management2.title')</span>
        </a>
        <ul class="dropdown-menu">
            @can('estimate_access')
            <li class="{{ (Request::is('admin/orders*') && !Request::is('admin/orders/draft-invoice') && !Request::is('admin/orders-return')) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.create') }}">
                    <span>@lang('quickadmin.order-management2.fields.add')</span>
                </a>
            </li>
            @endcan
            @can('transaction_access')
            <li class="{{ Request::is('admin/transactions/create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions.create') }}">
                    <span>@lang('quickadmin.transaction-management.fields.new_case_reciept')</span>
                </a>
            </li>
            @endcan
            @can('estimate_access')
            <li class="{{ Request::is('admin/orders-return') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.return') }}">
                    <span>@lang('quickadmin.return-order-management.fields.add')</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('transaction_management_access')
    <li class="dropdown {{ (Request::is('admin/transaction/current_estimate') || Request::is('admin/transaction/sales') || Request::is('admin/transaction/modified_sales') || Request::is('admin/transaction/sales_return') || Request::is('admin/transaction/cash_reciept') || Request::is('admin/orders/draft-invoice') || Request::is('admin/transaction/cancelled')) ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-side-bar-svg-icon icon="invoice" />
            <span>@lang('quickadmin.transaction-management.title')</span>
        </a>
        <ul class="dropdown-menu">
            @can('estimate_access')
            {{-- <li class="{{ Request::is('admin/transaction/current_estimate') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.type',['current_estimate']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.current_estimate')</span>
                </a>
            </li> --}}
            <li class="{{ Request::is('admin/transaction/sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions.type',['sales']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.sales')</span>
                </a>
            </li>
            {{-- <li class="{{ Request::is('admin/transaction/modified_sales') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.type',['modified_sales']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.modified_sales')</span>
                </a>
            </li> --}}
            <li class="{{ Request::is('admin/transaction/sales_return') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions.type',['sales_return']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.sales_return')</span>
                </a>
            </li>
            @endcan
            @can('transaction_access')
            <li class="{{ Request::is('admin/transaction/cash_reciept') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.transactions.type',['cash_reciept']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.case_reciept')</span>
                </a>
            </li>
            @endcan
            @can('estimate_access')
            <li class="{{ Request::is('admin/orders/draft-invoice') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.draftInvoice') }}">
                    <span>@lang('quickadmin.transaction-management.fields.draft_invoice')</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/transaction/cancelled') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.type',['cancelled']) }}">
                    <span>@lang('quickadmin.transaction-management.fields.cancelled')</span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('customer_management_access')
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
            @can('customer_access')
            {{-- <li class="{{ Request::is('admin/customers') ||  Request::is('admin/customers/*/edit') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customers.index') }}">
                    @lang('quickadmin.customer-management.fields.alter_list')
                </a>
            </li> --}}
            <li class="{{ Request::is('admin/customer/list') || Request::is('admin/customer/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.customer_list') }}">
                    @lang('quickadmin.customer-management.fields.list')
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('master_access')
    <li class="dropdown {{ (Request::is('admin/master*') && !(Request::is('admin/master/products*')) && !(Request::is('admin/master/product-recycle*')) && !(Request::is('admin/master/product-price*')) && !(Request::is('admin/master/product-group/*')))  ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <x-side-bar-svg-icon icon="customer" />
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
    @endcan

    @can('report_access')
        <li class="dropdown {{ Request::is('admin/reports*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <x-side-bar-svg-icon icon="user" />
                <span>@lang('quickadmin.reports.report_management')</span>
            </a>
            <ul class="dropdown-menu">
                @if (Gate::check('report_customer_access'))
                <li class="{{ Request::is('admin/reports/customers*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.reports.customer.index') }}">
                        @lang('quickadmin.reports.customer_report')
                    </a>
                </li>
                @endif
            </ul>
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

    <li class="{{ Request::is('logout*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('logout') }}">
            <x-side-bar-svg-icon icon="logout" />
            <span>@lang('quickadmin.qa_logout')</span>
        </a>
    </li>
</ul>

</aside>
</div>
