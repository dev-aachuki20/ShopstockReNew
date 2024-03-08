@if($isOrderFrom == "No")
@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
@endsection
@section('main-content')
@endif
<section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
            <form action="{{ route('admin.master.products.store') }}"  id="productFormMain" method="POST"  name="productFormMain" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="row">
                @include('admin.master.product.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@if($isOrderFrom == "No")
@endsection
@endif


