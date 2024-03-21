@extends('layouts.app')
@section('title')@lang('quickadmin.transaction-management.fields.new_case_reciept') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<style>
  .select2-results {
  padding-top: 0px !important;
}
</style>
@endsection
@section('main-content')
<section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card"> 
            <div class="card-header">
              <h4> @lang('quickadmin.order-management2.title-case-reciept')</h4>
            </div>           
            <div class="card-body">
            <form action="{{ route('admin.transactions.store') }}"  id="cash-reciept-form" method="POST"  name="productForm" enctype="multipart/form-data">
              <div class="row">
                @csrf
                @include('admin.payment_transactions.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection


