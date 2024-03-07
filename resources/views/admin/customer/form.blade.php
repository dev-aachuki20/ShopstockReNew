      <div class="col-md-6 form-group">
          {!! Form::label('name', trans('quickadmin.customers.fields.name'), ['class' => 'control-label']) !!} <span class="text-danger">*</span>
          {!! Form::text('name', $customer->name??'', ['class' => 'form-control name_checklist', 'placeholder' => 'Enter name']) !!}
          <div class="error_name text-danger error"></div>
      </div>

      <div class="col-md-6 form-group">
          {!! Form::label('phone_number', trans('quickadmin.customers.fields.phone_number'), ['class' => 'control-label']) !!} <span class="text-danger">*</span>
          {!! Form::text('phone_number', $customer->phone_number ?? '', ['class' => 'form-control only_integer', 'placeholder' => 'Enter phone number']) !!}
          <div class="error_phone_number text-danger error"></div>
      </div>
      <div class="col-md-6 form-group">
          {!! Form::label('alternate_phone_number', trans('quickadmin.customers.fields.alternate_phone_number'), ['class' => 'control-label']) !!}
          {!! Form::text('alternate_phone_number', $customer->alternate_phone_number ?? '', ['class' => 'form-control only_integer', 'placeholder' => 'Enter Alternate phone number']) !!}
          <div class="error_alternate_phone_number text-danger error"></div>
      </div>


      <div class="col-md-6 form-group">
          {!! Form::label('area_id', trans('quickadmin.customers.fields.area_address'), ['class' => 'control-label']) !!} <span class="text-danger">*</span>
          {!! Form::select('area_id', $areas, $customer->area_id ?? '', ['id'=>'areaList','class' => 'form-control select2']) !!}
          <p class="help-block red" id="area_id_error"></p>
          <div class="error_area_id text-danger error"></div>
      </div>

      @if(!isset($customer))
      <div class="col-md-6 col-sm-12 col-lg-6 form-group">
          {!! Form::label('opening_blance', trans('quickadmin.customers.fields.opening_blance'), ['class' => 'control-label ']) !!} <span class="text-danger">*</span>
          {!! Form::text('opening_blance', $customer->opening_blance ?? 0, ['class' => 'form-control only_integer', 'placeholder' => 'Enter opening balance', 'min'=>"0" ,'autocomplete'=>"off" ]) !!}
          <div class="error_opening_blance text-danger error"></div>
      </div>
      @endif

      <div class="col-md-6 col-sm-12 col-lg-6 form-group">
          {!! Form::label('credit_limit', trans('quickadmin.customers.fields.credit_limit'), ['class' => 'control-label ']) !!}
          {!! Form::text('credit_limit', $customer->credit_limit ?? 0, ['class' => 'form-control only_integer', 'placeholder' => 'Enter credit limit', 'min'=>"0" ,'autocomplete'=>"off" ]) !!}
          <div class="error_credit_limit text-danger error"></div>
      </div>
      <div class="col-md-12"></div>
      <div class="col-md-6 mb-3">
          {!! Form::label('is_type', trans('quickadmin.customers.fields.is_type'), ['class' => 'control-label']) !!} <span class="text-danger">*</span>
          <span class="select_group_list" style="@if(($customer->is_type ??'') != 'wholesaler') display: none @endif"><input type="checkbox" id="select_all"> <label for="select_all">Select All</label></span>
          {!! Form::select('is_type', $types, $customer->is_type ?? '', ['class' => 'form-control select2']) !!}
          <div class="error_is_type text-danger error"></div>

          <div class="select_group_list" style="@if(($customer->is_type ??'') != 'wholesaler') display: none @endif">
              <ul>
                  @foreach ($groups as $row)
                  <li>
                      @php $isChecked = ""; @endphp
                      @if(isset($customerGroup))
                      @php
                      $values = collect($customerGroup);
                      $searchValue = $row->id;
                      @endphp
                      @if($values->contains($searchValue))
                      @php $isChecked = "checked"; @endphp
                      @endif
                      @endif

                      <input type="checkbox" {{$isChecked}} id="group_check_{{$row->id}}" class="selected_product" name="groups[]" value="{{$row->id}}">
                      <label for="group_check_{{$row->id}}"> {{$row->name}}</label>
                  </li>
                  @endforeach
              </ul>
          </div>
      </div>


      <div class="col-md-12">
          @if(isset($customer))
          <input type="button" class="btn btn-primary save_btn" value="@lang('admin_master.g_update')">
          @else
          <input type="button" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
          @endif
      </div>



      <!-- Add Edit Modal -->
      <div class="modal fade" id="add_newModal" tabindex="-1" role="dialog" aria-labelledby="add_newModalTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Add</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <label for="naem">ADDRESS:</label>
                          <input type="text" class="form-control" id="add_new_name" placeholder="Enter address">
                          <span class="error_new_address text-danger error"></span>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-primary save_add_new">Save</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- Add Edit Modal -->

      @section('customJS')
      <script type="text/javascript">
          $(document).ready(function() {
            $(document).on('input','.name_checklist',function(){
                var nameIs = $(this).val();
                $.ajax({
                      type: "POST",
                      url: "{{route('admin.customers.namelist')}}",
                      data: {name:nameIs},
                      success: function(data) {
                          if ($.isEmptyObject(data.error)) {
                              var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                              var message = data.success;
                              var title = "Customer";
                              showToaster(title, alertType, message);
                              setTimeout(() => {
                                  window.location.replace("{{route('admin.customers.index')}}");
                              }, 1500);
                          } else {
                              $('.save_btn').prop('disabled', false);
                              printErrorMsg(data.error);
                          }
                      },
                      error: function(data) {
                          $('.save_btn').prop('disabled', false);
                      }
                  });

            })

              //
              $(document).on('change', '#select_all', function() {
                  var isChecked = $(this).prop('checked');
                  $('.selected_product').prop('checked', isChecked);
              });
              $(document).on('click','.selected_product',function(){
                    checkboxChecked();                   
              });



              $(document).on('change', '#is_type', function() {
                  var is_type_selected = $(this).val();
                  if (is_type_selected == "wholesaler") {
                      $('.select_group_list').css('display', 'inline-block');
                      setTimeout(()=>{
                        $("#select_all").trigger('click');
                      },200)
                  } else {
                      $('#select_all').prop('checked', false);
                      $('.selected_product').prop('checked', false);
                      $('.select_group_list').css('display', 'none');
                  }
              });
              // 
              $(document).on('input', '.only_integer', function(evt) {
                  var inputValue = $(this).val();
                if(inputValue.length > 10){
                    $(this).val($(this).val().substring(0, 10));
                }else{  
                    $(this).val(inputValue.replace(/[^0-9]/g, ''));
                }
              });

              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });

            //   $(document).on('submit', "#customerForm", function(e) {
              $(document).on('click', ".save_btn", function(e) {

                  e.preventDefault();
                  $('.error').html('');
                  var action = $("#customerForm").attr('action');
                  var method = $("#customerForm").attr('method');
                  var formData = new FormData($("#customerForm")[0]);
                  $('.save_btn').prop('disabled', true);
                  formData.append('_method', method);

                  $.ajax({
                      type: "POST",
                      url: action,
                      data: formData,
                      contentType: false,
                      processData: false,
                      success: function(data) {
                          if ($.isEmptyObject(data.error)) {
                              var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                              var message = data.success;
                              var title = "Customer";
                              showToaster(title, alertType, message);
                              setTimeout(() => {
                                  window.location.replace("{{route('admin.customers.index')}}");
                              }, 1500);
                          } else {
                              $('.save_btn').prop('disabled', false);
                              printErrorMsg(data.error);
                          }
                      },
                      error: function(data) {
                          $('.save_btn').prop('disabled', false);
                      }
                  });
              });

              // area add 
              $("#areaList").select2({}).on('select2:open', function() {
                  let a = $(this).data('select2');
                  if (!$('.select2-area_add').length) {
                      a.$results.parents('.select2-results').append('<div class="select2-area_add select_2_add_btn"><button class="btns addNewAreaTypeBtn get-customer"><i class="fa fa-plus-circle"></i> Add New</button></div>');
                  }
              });
              // area add 

              $(document).on('keyup', function(e) {
                  if (e.key === 'Enter') {
                      if ($('#add_newModal').is(':visible')) {
                          $('.add_unittype_btn').click();
                      } 
                    //   else {
                    //       $('#customerForm').submit();
                    //   }
                  }
              });

              $(document).on('click', ".addNewAreaTypeBtn", function() {
                  $("#add_new_name").val('');
                  $('.save_add_new').prop('disabled', false);
                  $('.add_new_drop').html('Area');
                  $("#add_newModal").modal('show');
                  $("#areaList").select2('close');
                  $(".save_add_new").addClass('add_unittype_btn');
              });
              $(document).on('click', '.add_unittype_btn', function(e) {
                  e.preventDefault();
                  var name = $("#add_new_name").val();
                  $('.save_add_new').prop('disabled', true);
                  $.ajax({
                      type: "POST",
                      url: "{{ route('admin.master.areas.store') }}",
                      data: {
                          address: name,
                          id: ""
                      },
                      success: function(data) {
                          $('.save_add_new').prop('disabled', false);
                          if ($.isEmptyObject(data.error)) {
                              var newOption = new Option(data.unitData.address, data.unitData.id, true, true);
                              $('#areaList').append(newOption).trigger('change');
                              $("#add_newModal").modal('hide');
                              var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                              var message = data.success;
                              var title = "Area";
                              showToaster(title, alertType, message);
                          } else {
                              printErrorMsgAdd(data.error);
                          }
                      }
                  });
              });

          })

          function printErrorMsg(msg) {
              $.each(msg, function(key, value) {
                  $(`.error_${key}`).html(value);
              });
          }

          function printErrorMsgAdd(msg) {
              $.each(msg, function(key, value) {
                  $(`.error_new_${key}`).html(value);
              });
          }
         
          function checkboxChecked(){
            var checkValue = "Yes";
            $(".selected_product").each(function(){
                if(!$(this).is(":checked")){
                    checkValue = "No";                       
                }
            });
            if(checkValue == "Yes"){
                $('#select_all').prop('checked', true);
            }else{
                $('#select_all').prop('checked', false);
            }
          }

          $(document).ready(function() {
                setTimeout(()=>{
                    checkboxChecked();
                },300);
        });
      </script>
      @endsection