@extends('layouts.app')
@section('content')
    @include('validations._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/validations" method="POST">
                    @csrf
                    @method('POST')
                    @include('validations._form')
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/validations">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </main>
@endsection
