

   
    <div class="input-group">
        {!! Form::select('sub_group_id', $groups, $selected_id??'', ['class' => 'form-control select2', 'id'=>'sub_group_list']) !!}
    </div>
    <div class="error_sub_group_id text-danger error"></div>
