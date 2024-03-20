@extends('layouts.app')
@section('title')@lang('admin_master.new_estimate.edit_seo_title') @endsection
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
              <h3>{{ trans('quickadmin.order.title-'.$orderType) }}
            </div>
            <div class="card-body">
            <form action="{{ route('admin.orders.update',$order->id) }}"  id="productForm" method="PUT"  name="productForm" enctype="multipart/form-data">
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


