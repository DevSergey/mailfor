<div class="col">
    <label for="{{$field}}">{{$text}}</label>
    <input class="form-control {{$errors->get($field) ? 'is-invalid': ''}}" id="{{$field}}"
           aria-describedby="{{$field}}Help"
           placeholder="{{$placeholder}}" value="{{$value}}" name="{{$field}}" {{!! $additional_options !!}}>
    <small id="{{$field}}Help" class="form-text text-muted">{{$description}}</small>
    <div class="invalid-feedback">{{$error_message}}</div>
</div>
