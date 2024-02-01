
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.logActivities.fields.name')</th>
                <td field-key='user name'>{{ $logActivity->user->name ?? '' }}</td>
            </tr>

            <tr>
                <th>@lang('quickadmin.logActivities.fields.subject')</th>
                <td field-key='user name'>{{ $logActivity->subject ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.url')</th>
                <td field-key='user name'>{{ $logActivity->url ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.method')</th>
                <td field-key='user name'>{{ $logActivity->method ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.agent')</th>
                <td field-key='user name'>{{ $logActivity->agent ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.logActivities.fields.ip')</th>
                <td field-key='user name'>{{ $logActivity->ip ?? '' }}</td>
            </tr>
            
            <tr>
                <th>@lang('quickadmin.qa_created_at')</th>
                <td field-key='user name'>{{ $logActivity->created_at ?? '' }}</td>
            </tr>
        </table>
    </div>
</div>