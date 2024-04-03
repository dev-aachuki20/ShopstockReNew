
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.customers.fields.name')</th>
                <td >{{ $customer->name ? ucwords($customer->name) : '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.phone_number')</th>
                <td >{{ $customer->phone_number ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.alternate_phone_number')</th>
                <td >{{ $customer->alternate_phone_number ?? '' }}</td>
            </tr>


            <tr>
                <th>@lang('quickadmin.customers.fields.area_address')</th>
                <td >{{ $customer->area->address ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.customers.fields.is_type')</th>
                <td >{{ $customer->is_type ?? '' }}</td>
            </tr>
            @if($customer->is_type =="wholesaler")
            <tr>
                <th>Groups</th>
                <td >
                    @foreach($customerGroup as $row)
                            {{$row->group->name??''}}@if (!$loop->last),@endif
                    @endforeach
                </td>
            </tr>
            @endif


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
