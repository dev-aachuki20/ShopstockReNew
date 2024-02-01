@extends('layouts.app')
@section('title')@lang('quickadmin.roles.title') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')
<section class="section roles" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>@lang('quickadmin.split.title')</h4>
                </div>
                <div class="card-body">
                    <!-- Start Filter Section -->
                        <div class="panel panel-default ">
                            <div class="panel-heading">
                                    @lang('quickadmin.qa_filter')
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    {!! Form::open(['method' => 'POST', 'class'=>"row", 'id'=>'split-form','route' => ['admin.master.split.store'], 'onsubmit'=>"return confirm('Are you sure, You want to split ?')"]) !!}
                                        <div class="col-md-12 form-group bottom-gap">
                                            {!! Form::label('from_date', trans('quickadmin.split.fields.from_date'), ['class' => 'control-label']) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 form-group bottom-gap">
                                            {!! Form::date('from_date', old('from_date'), ['class' => 'form-control date', 'placeholder' => '', 'required' => '' ]) !!}
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12 pull-left">
                                            {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-sm btn-success']) !!}
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
            </div>
          </div>
        </div>
  </section>
@endsection
@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>


<script type="text/javascript">
    $(document).ready(function(){
        var getSession = "{{session('success')??''}}";
       if(getSession != ''){
        var alertType = "{{ trans('quickadmin.alert-type.success') }}";
        var message = getSession;
        var title = "Splits";
        showToaster(title,alertType,message); 
}
    });
</script>

@endsection