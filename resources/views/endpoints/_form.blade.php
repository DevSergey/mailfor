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
'placeholder'=>'https://formail.dev',
'value'=> $endpoint->cors_origin,
'description'=> 'The Access-Control-Allow-Origin header.',
'error_message'=> 'Access-Control-Allow-Origin was invalid.',
'additional_options'=>'required type="url"'
])
    @include('form._form-element', [
'field'=>'subject',
'text' => 'Subject',
'placeholder'=>'Subject',
'value'=> $endpoint->subject,
'description'=> 'Subject of the mail',
'error_message'=> 'Subject was invalid',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    @include('form._form-element', [
'field'=>'monthly_limit',
'text' => 'Monthly Limit',
'placeholder'=>1000,
'value'=> $endpoint->monthly_limit,
'description'=> 'The monthly limit of allowed api requests',
'error_message'=> 'Monthly Limit was invalid',
'additional_options'=>'required type="number" min="0"'
])
    @include('form._form-element', [
'field'=>'client_limit',
'text' => 'Client Limit',
'placeholder'=>5,
'value'=> $endpoint->client_limit,
'description'=> 'The client limit of allowed api requests',
'error_message'=> 'Client Limit was invalid',
'additional_options'=>'required type="number" min="0"'
])
    @include('form._form-element', [
'field'=>'time_unit',
'text' => 'Time Unit of the client limit',
'placeholder'=>'minute',
'value'=> $endpoint->time_unit,
'description'=> 'The time unit that will be used together with client limit',
'error_message'=> 'Time Unit was invalid',
'additional_options'=>'required'
])
</div>
<div class="form-row">
    <div class="col">
        <label for="credential_id">Credential</label>
        <select class="custom-select" name="credential_id" id="credential_id" required>
            @foreach($credentials as $credential)
                <option value="{{$credential->id}}"
                        @if($credential->id === $endpoint->credential_id)selected="selected"
                        @endif aria-describedby="credential_idHelp">{{$credential->name}}</option>
            @endforeach
        </select>
        <small id="credential_idHelp" class="form-text text-muted">The credentials to send mails for this
            endpoint</small>
        <div class="invalid-feedback">Credential was invalid.</div>
    </div>
    <div class="col">
        <label for="receivers">Credential</label>
        <select class="custom-select" multiple name="receivers[]" id="receivers" required>
            @foreach($receivers as $receiver)
                <option value="{{$receiver->id}}"
                        @if($endpoint->receivers->contains($receiver->id))selected="selected"
                        @endif aria-describedby="receiversHelp">{{$receiver->name}}</option>
            @endforeach
        </select>
        <small id="receiversHelp" class="form-text text-muted">The receivers who should receive the incoming
            requests.</small>
        <div class="invalid-feedback">Receivers was invalid.</div>
    </div>
</div>
