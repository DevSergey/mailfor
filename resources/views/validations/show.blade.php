@extends('layouts.app')
@section('content')
    @include('validations._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/validations/{{$validation->id}}" method="POST">
                    @csrf
                    @method('PATCH')
                    @include('validations._form')
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/validations">
                            <button type="button" class="btn btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
