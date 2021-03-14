@extends('layouts.app')
@section('content')
    @include('credentials._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/credentials" method="POST">
                    @csrf
                    @method('POST')
                    @include('credentials._form')
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/credentials">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </main>
@endsection
