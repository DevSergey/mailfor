@extends('layouts.app')
@section('content')
    @include('credentials._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/credentials/{{$credential->id}}" method="POST">
                    @csrf
                    @method('PATCH')
                    @include('credentials._form')
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/credentials">
                            <button type="button" class="btn btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
