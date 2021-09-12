@extends('layouts.app')
@section('content')
    @include('endpoints._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/endpoints" method="POST">
                    @csrf
                    @method('POST')
                    @include('endpoints._form')
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/endpoints">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </main>
@endsection
