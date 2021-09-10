@extends('layouts.app')
@section('content')
    @include('receivers._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/receivers" method="POST">
                    @csrf
                    @method('POST')
                    @include('receivers._form')
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/receivers">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </main>
@endsection
