@extends('layouts.app')
@section('content')
    <header class="container d-flex justify-content-between mb-4">
        <h4>My Endpoints</h4>
        <a class="btn bg-primary" href="/endpoints/create">Create Endpoint</a>
    </header>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                            <th scope="col">Name</th>
                            <th scope="col">Access-Control-Allow-Origin</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($endpoints as $endpoint)
                            <tr>
                                <td>
                                    <a href="/endpoints/{{$endpoint->id}}">
                                        <button type="submit" class="btn btn-success btn-sm">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="/endpoints/{{$endpoint->id}}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                                <td>{{$endpoint->name}}</td>
                                <td>{{$endpoint->cors_origin}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
