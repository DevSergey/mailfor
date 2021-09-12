<div class="form-row">
    @include('form._form-element', [
'field'=>'name',
'text' => 'Name',
'placeholder'=>'Name',
'value'=> $endpoint->name,
'description'=> 'The name of the endpoint',
'error_message'=> 'Name was invalid.',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'cors_origin',
'text' => 'Access-Control-Allow-Origin',
'placeholder'=>'example@mail.com',
'value'=> $endpoint->cors_origin,
'description'=> 'The Access-Control-Allow-Origin header.',
'error_message'=> 'Access-Control-Allow-Origin was invalid.',
'additional_options'=>'required type="url"'
])
</div>
