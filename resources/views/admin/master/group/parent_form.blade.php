
    <div class="input-group">
        {!! Form::select('parent_id', $groups, $parent_id??'', ['class' => 'form-control select2', 'id'=>'parent_id']) !!}
    </div>
    <div class="error_parent_id text-danger error"></div>
