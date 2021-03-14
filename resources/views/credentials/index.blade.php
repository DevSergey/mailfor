@extends('layouts.app')
@section('content')
    <header class="container d-flex justify-content-between mb-4">
        <h4>My Credentials</h4>
        <a class="btn bg-primary" href="/credentials/create">Create Credential</a>
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
                            <th scope="col">Host</th>
                            <th scope="col">Port</th>
                            <th scope="col">From Address</th>
                            <th scope="col">From Name</th>
                            <th scope="col">Encryption</th>
                            <th scope="col">Username</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($credentials as $credential)
                            <tr>
                                <td>
                                    <a href="/credentials/{{$credential->id}}">
                                        <button type="submit" class="btn btn-success btn-sm">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="/credentials/{{$credential->id}}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                                <td>{{$credential->name}}</td>
                                <td>{{$credential->host}}</td>
                                <td>{{$credential->port}}</td>
                                <td>{{$credential->from_address}}</td>
                                <td>{{$credential->from_name}}</td>
                                <td>{{$credential->encryption}}</td>
                                <td>{{$credential->username}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
