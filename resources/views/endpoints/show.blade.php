@extends('layouts.app')
@section('content')
    @include('endpoints._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/endpoints/{{$endpoint->id}}" method="POST">
                    @csrf
                    @method('PATCH')
                    @include('endpoints._form')
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/endpoints">
                            <button type="button" class="btn btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
