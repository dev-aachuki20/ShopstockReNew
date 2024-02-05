<div class="col-md-12">
    <div class="form-group">
        <label>@lang('quickadmin.ip.fields.addIp')  <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="name" value="{{ isset($product) ? $product->name : '' }}" id="name" autocomplete="true" placeholder="@lang('quickadmin.ip.fields.addIp')">
        </div>
        <div class="error_name text-danger error"></div>
    </div>

    <div class="form-group">
        <label>@lang('quickadmin.ip.fields.roles')  <span class="text-danger">*</span></label>        
        <div class="row">
            @foreach($allRoles as  $role)
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input permission-checkbox" name="roles[]" value="{{ $role->id }}" id="permission{{ $role->id }}">
                            <label class="custom-control-label" for="permission{{ $role->id }}">{{ $role->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
<div class="col-md-12">  
    <div class="success_error_message"></div>
  <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
</div>




@section('customJS')
<script type="text/javascript">         
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    $(document).on('submit', "#roleForm", function(e) {
        e.preventDefault();
        $('.error').html('');
        $('.success_error_message').html('');
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = new FormData($("#roleForm")[0]);
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
                $('.success_error_message').html(`<span class="text-success">${data.success}</span>`);
                setTimeout(() => {
                    window.location.replace("{{route('role_ip.index')}}");
                }, 1500);                  
            } else {
                $('.save_btn').prop('disabled', false);
                printErrorMsg(data.error);
            }
        },
        error: function(data){
            $('.save_btn').prop('disabled', false);
        }
        });
    });
 })

function printErrorMsg(msg) {
  $.each(msg, function(key, value) {
    $(`.error_${key}`).html(value);
  });
}


</script>
@endsection