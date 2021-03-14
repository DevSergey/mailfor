<div class="form-row">
    @include('form._form-element', [
'field'=>'name',
'text' => 'Name',
'placeholder'=>'Name',
'value'=> $credential->name,
'description'=> 'The name of the smtp credential.',
'error_message'=> 'Name was invalid.',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'host',
'text' => 'Host',
'placeholder'=>'mail.example.com',
'value'=> $credential->host,
'description'=> 'The host of the smtp server.',
'error_message'=> 'Host was invalid.',
'additional_options'=>'required'
])
    @include('form._form-element', [
'field'=>'port',
'text' => 'Port',
'placeholder'=>587,
'value'=> $credential->port,
'description'=> 'The port of the smtp server.',
'error_message'=> 'Port was invalid.',
'additional_options'=>'required min="0" max="65535" type="number"'
])
    @include('form._form-element', [
'field'=>'encryption',
'text' => 'Encryption',
'placeholder'=>'tls',
'value'=> $credential->encryption,
'description'=> 'The encryption which should be used for sending.',
'error_message'=> 'Encryption was invalid.',
'additional_options'=>'required maxlength="20""'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'from_address',
'text' => 'From Address',
'placeholder'=>'example@mail.com',
'value'=> $credential->from_address,
'description'=> 'The address which the mail should be sent of.',
'error_message'=> 'From Address was invalid.',
'additional_options'=>'required type="email"'
])
    @include('form._form-element', [
'field'=>'from_name',
'text' => 'From Name',
'placeholder'=>'Example',
'value'=> $credential->from_name,
'description'=> 'The Name which the mail should be sent of.',
'error_message'=> 'From Name was invalid.',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'username',
'text' => 'Username',
'placeholder'=>'username',
'value'=> $credential->username,
'description'=> 'The username to log into the smpt server.',
'error_message'=> 'Username was invalid.',
'additional_options'=>'required'
])
    <div class="col">
        <label for="password">Password</label>
        <input type="password" class="form-control {{$errors->get('password') ? 'is-invalid': ''}}" id="password"
               aria-describedby="passwordHelp"
               value="{{!! $credential->password !!}}" name="password" required>
        <small id="passwordHelp" class="form-text text-muted">The password to log into the smpt
            server.</small>
        <div class="invalid-feedback">
            Password was invalid.
        </div>
    </div>
</div>
