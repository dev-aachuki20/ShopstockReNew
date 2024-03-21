@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.title') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
@endsection
@section('main-content')
<section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Party Create</h4>
            </div> 
            <div class="card-body">
            <form action="{{ route('admin.customers.store') }}"  id="customerForm" method="POST"  enctype="multipart/form-data">
              <div class="row">
                @include('admin.customer.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection


