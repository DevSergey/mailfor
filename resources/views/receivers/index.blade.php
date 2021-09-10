@extends('layouts.app')
@section('content')
    <header class="container d-flex justify-content-between mb-4">
        <h4>My Validations</h4>
        <a class="btn bg-primary" href="/receivers/create">Create Validation</a>
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
                            <th scope="col">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($receivers as $receiver)
                            <tr>
                                <td>
                                    <a href="/receivers/{{$receiver->id}}">
                                        <button type="submit" class="btn btn-success btn-sm">Edit</button>
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="/receivers/{{$receiver->id}}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                                <td>{{$receiver->name}}</td>
                                <td>{{$receiver->email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
