
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.alter_list') @endsection
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
                  <h4>@lang('quickadmin.customer-management.fields.alter_list')</h4>                 
                </div>
                <div class="col-md-12">
                    <h3>{{$customer->name}}</h3>
                    <p class="clientInformation">
                      <small title="Customer category">
                        <i class="fa fa-user-md" aria-hidden="true"></i> 
                        Retailer
                      </small>
                        <small title="Customer phone number"><i class="fa fa-phone" aria-hidden="true"></i> 9680505848</small>
                        <small title="Customer total blance"><i class="fa fa-money" aria-hidden="true"></i> ₹22899</small>
                        <small title="Customer address"><i class="fa fa-map-marker" aria-hidden="true"></i> Kekri</small>
                  </p>
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
@endsection
