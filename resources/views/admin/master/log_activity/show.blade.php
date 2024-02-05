
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.logActivities.fields.name')</th>
                <td field-key='user name'>{{ $logActivity->user->name ?? '' }}</td>
            </tr>

            <tr>
                <th>@lang('quickadmin.logActivities.fields.model_type')</th>
                <td field-key='user name'>{{ $logActivity->model_name ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.logActivities.fields.activity')</th>
                <td field-key='user name'>{{ $logActivity->activity ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.url')</th>
                <td field-key='user name'><p style="word-break: break-all;">{{ $logActivity->url ?? '' }}</p></td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.method')</th>
                <td field-key='user name'>{{ $logActivity->method ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.agent')</th>
                <td field-key='user name'><p style="word-break: break-all;">{{ $logActivity->agent ?? '' }}</p></td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.ip')</th>
                <td field-key='user name'>{{ $logActivity->ip ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.qa_created_at')</th>
                <td field-key='user name'>{{ $logActivity->created_at ?? '' }}</td>
            </tr>
            {{-- <tr>
                <th>@lang('quickadmin.old')</th>
                <td field-key='user name'>{{ $logActivity->old_value ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.new')</th>
                <td field-key='user name'>{{ $logActivity->new_value ?? '' }}</td>
            </tr> --}}
        </table>
    </div>
</div>