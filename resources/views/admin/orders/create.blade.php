@extends('layouts.app')
@section('title')@lang('admin_master.new_estimate.seo_title') @endsection
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
              <h4>{{ trans('quickadmin.order.title-'.$orderType) }}</h4>
            </div>
            <div class="card-body">
            <form action="{{ route('admin.orders.store') }}"  id="productForm" method="POST"  name="productForm" enctype="multipart/form-data">
                @csrf
                @include('admin.orders.form')
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('admin.orders.modal.create_product_modal')
  </section>
@endsection


