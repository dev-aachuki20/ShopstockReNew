
@extends('layouts.app')
@section('title')@lang('quickadmin.reports.customer_report') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">

@endsection

@section('main-content')
    <section class="section roles" style="z-index: unset">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="modal fade px-3" id="CheckPassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static"data-keyboard="false" >
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Please Enter Report View Password</h5>

                                </div>
                                <div class="modal-body">
                                    <form method="post" id="VerifyPassForm" action="{{route('admin.password.verify')}}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="password">Enter Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" id="password" autocomplete="true">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="submit" class="btn btn-primary">@lang('quickadmin.qa_submit')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
<script type="text/javascript">
    $(document).ready(function(){

        $('#CheckPassModal').modal('show');

        $(document).on('submit', '#VerifyPassForm', function(e) {
            e.preventDefault();
            $("#VerifyPassForm button[type=submit]").prop('disabled', true);
            $(".error").remove();
            $(".is-invalid").removeClass('is-invalid');
            var formData = $(this).serialize();
            var formAction = $(this).attr('action');
            $.ajax({
                url: formAction,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    var alertType = response['alert-type'];
                    var message = response['message'];
                    var title = "Password";
                    showToaster(title, alertType, message);
                    $('#VerifyPassForm')[0].reset();

                    window.location.href = "{{ route('admin.reports.customer.index') }}";

                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    for (const elementId in errors) {
                        $("#VerifyPassForm #" + elementId).addClass('is-invalid');
                        var errorHtml = '<div><span class="error text-danger">' + errors[elementId] + '</span></';
                        $(errorHtml).insertAfter($("#VerifyPassForm #" + elementId).parent());
                    }
                    $("#VerifyPassForm button[type=submit]").prop('disabled', false);
                }
            });
        });






});
</script>
@endsection
