@extends('layouts.app')
@section('content')
    @include('receivers._header')
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <form action="/receivers/{{$receiver->id}}" method="POST">
                    @csrf
                    @method('PATCH')
                    @include('receivers._form')
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="/receivers">
                            <button type="button" class="btn btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
