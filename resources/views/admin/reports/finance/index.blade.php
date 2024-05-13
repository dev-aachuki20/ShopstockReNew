
@extends('layouts.app')
@section('title')@lang('quickadmin.reports.customer_report') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
<style>
</style>
@endsection

@section('main-content')
    <section class="section roles" style="z-index: unset">
        <div class="section-body">
            <div class="row">

            </div>
        </div>
    </section>
@endsection

@section('customJS')


<script type="text/javascript">
$(document).ready(function(){


});
</script>
@endsection
