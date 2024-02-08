@extends('layouts.app')
@section('title')@lang('quickadmin.ip.title') @endsection
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
            <form action="{{ route('admin.master.role_ip.store') }}"  id="roleForm" method="POST"  name="roleForm" enctype="multipart/form-data">
              <div class="row">
                @include('admin.master.roleip.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection


