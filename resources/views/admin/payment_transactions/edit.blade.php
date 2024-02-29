@extends('layouts.app')
@section('title')@lang('quickadmin.transaction-management.fields.new_case_reciept') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
@endsection
@section('main-content')
<section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
            <form action="{{ route('admin.transactions.update',['transaction'=>$transaction->id]) }}" method="PUT" id="transactionForm" name="productForm" enctype="multipart/form-data">
              <div class="row">
                @include('admin.payment_transactions.editform')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
