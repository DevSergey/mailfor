<div class="form-row">
    @include('form._form-element', [
'field'=>'name',
'text' => 'Name',
'placeholder'=>'Name',
'value'=> $validation->name,
'description'=> 'The name of the validation type',
'error_message'=> 'Name was invalid.',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'validation',
'text' => 'Validation',
'placeholder'=>'required|numeric|between:10,20',
'value'=> $validation->validation,
'description'=> 'The validation consisting of laravel validation rules.',
'error_message'=> 'Type was invalid.',
'additional_options'=>'required'
])
</div>
