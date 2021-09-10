<div class="form-row">
    @include('form._form-element', [
'field'=>'name',
'text' => 'Name',
'placeholder'=>'Name',
'value'=> $receiver->name,
'description'=> 'The name of the receiver',
'error_message'=> 'Name was invalid.',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'email',
'text' => 'Email',
'placeholder'=>'example@mail.com',
'value'=> $receiver->email,
'description'=> 'The email of the receiver.',
'error_message'=> 'Email was invalid.',
'additional_options'=>'required type="email"'
])
</div>
