
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.customers.fields.name')</th>
                <td >{{ $customer->name ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.phone_number')</th>
                <td >{{ $customer->phone_number ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.email')</th>
                <td >{{ $customer->email ?? '' }}</td>
            </tr>


            <tr>
                <th>@lang('quickadmin.customers.fields.area_address')</th> 
                <td >{{ $customer->area->address ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.is_type')</th>
                <td >{{ $customer->is_type ?? '' }}</td>
            </tr>


            <tr>
                <th>@lang('quickadmin.customers.fields.credit_limit')</th>
                <td ><i class="fa fa-inr"></i> {{ $customer->credit_limit ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.created_at')</th>
                <td >{{ $customer->created_at ?? '' }}</td>
            </tr>

        </table>
    </div>
</div>