@extends('layouts.app')
@section('title')@lang('admin_master.new_estimate.seo_title') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<style>
  .select2-results{
    padding-top: 0 !important;
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
              <h3>{{ trans('admin_master.new_estimate.seo_title') }}
            </div>
            <div class="card-body">
            <form action="{{ route('admin.orders.store') }}"  id="productForm" method="POST"  name="productForm" enctype="multipart/form-data">
              <div class="row">
              @csrf
                @include('admin.orders.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection


