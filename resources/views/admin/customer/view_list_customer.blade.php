
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.alter_list') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')

<section class="section roles customer_view_deail" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>@lang('quickadmin.customer-management.fields.alter_list')</h4>                 
                </div>
                <div class="col-md-12">
                    <h3>{{$customer->name}}</h3>
                    <p class="clientInformation">
                      <small title="Customer category">
                        <i class="fa fa-user-md" aria-hidden="true"></i> 
                        {{$customer->is_type ?? ''}}
                      </small>
                        <small title="Customer phone number"><i class="fa fa-phone" aria-hidden="true"></i> {{$customer->phone_number}}</small>
                        <small title="Customer total blance"><i class="fa fa-money" aria-hidden="true"></i> <i class="fa fa-inr" aria-hidden="true"></i> {{$customer->credit_limit}}</small>
                        <small title="Customer address"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $customer->area->address ?? '' }}</small>
                  </p>
                </div>
             
                <div class="col-md-12">
                  <!-- Start Filter Section -->
                     <div class="panel panel-default mt-5">
                       <div class="panel-heading">
                               @lang('quickadmin.qa_filter')
                       </div>
                       <div class="panel-body">
                        <div class="row">
                          <div class="col-md-8">
                           {!! Form::open(['method' => 'POST', 'route' => ['admin.customers.historyFilter'],'id'=>'history-filter-form']) !!}
                               <div class="row">
                                   <div class="col-lg-6 form-group">
                                       <label for="fromDate">From Date</label>
                                       <input type="date" class="form-control" name="from_date" id="fromDate" placeholder="From Date" autocomplete="off">
                                   </div>
                                   <div class="col-lg-6 form-group">
                                       <label for="toDate">To Date</label>
                                       <input type="date" class="form-control" name="to_date" placeholder="To Date" id="toDate" autocomplete="off">
                                   </div>
                               </div>
                           {!! Form::close() !!}
                          </div>
                           <div class="col-md-4">
                              <button id="resetFilter" class="btn btn-sm btn-primary mr-5">Reset</button>
                              <button id="submitFilter" class="btn btn-sm btn-success mr-5">Submit</button>
                           </div>
                          </div>
                              
                       </div>
                     </div>
                   <!--End Filter Section -->
               </div>
               
               <div class="col-md-12">
                  <!-- Start payement history  -->
                    <div class="panel panel-default mt-0">
                      <div class="panel-heading">
                          @lang('quickadmin.qa_payment_history')
                      </div>
                      <div class="panel-body table-responsive payment-history-content">
                          @include('admin.customer.payment_history')
                      </div>
                  </div>
                <!-- End Payment History -->
               </div>
             
              </div>
            </div>
           </div>
        </div>
  </section>

 

@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script>
   //Submit Filter
   $(document).on('click','#submitFilter',function(){
            submitFilterForm();
        });
    //Reset Filter
    $(document).on('click','#resetFilter',function(){
        $('#history-filter-form')[0].reset();
        submitFilterForm();
    });

    // Submit Filter Form
    async function submitFilterForm(){
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{!! csrf_token() !!}"
            }
        });
        $.ajax({
            type: $('#history-filter-form').attr('method'),
            url: $('#history-filter-form').attr('action'),
            data: {from_date:fromDate,to_date:toDate,customer:'{{$customer->id}}'},
            dataType: 'json',
            success: function (response,status, xhr) {
                if(response.success){
                    $('.payment-history-content').children('table').remove();
                    $('.payment-history-content').html(response.viewRender);
                }
            },
            error: function (response) {
            }
        });
    }

</script>
@endsection
