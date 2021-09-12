<?php
namespace App\Http\Controllers;
use App\Endpoint;
use App\Rules\CorsOrigin;
class EndpointController extends Controller
{
    public function create()
    {
        $endpoint = new Endpoint();
        return view('endpoints.create', compact('endpoint'));
    }
    public function store()
    {
        $endpoint = auth()->user()->endpoints()->create($this->validateRequest());
        return redirect('/endpoints/' . $endpoint->id);
    }
    protected function validateRequest()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'cors_origin' => ['required', new CorsOrigin]
        ]);
    }
    public function index()
    {
        $endpoints = auth()->user()->endpoints;
        return view('endpoints.index', compact('endpoints'));
    }
    public function show(Endpoint $endpoint)
    {
        if (auth()->user()->isNot($endpoint->user)) {
            abort(403);
        }
        return view('endpoints.show', compact('endpoint'));
    }
    public function destroy(Endpoint $endpoint)
    {
        if (auth()->user()->isNot($endpoint->user)) {
            abort(403);
        }
        Endpoint::destroy($endpoint->id);
        return redirect('/endpoints');
    }
    public function update(Endpoint $endpoint)
    {
        if (auth()->user()->isNot($endpoint->user)) {
            abort(403);
        }
        $endpoint->update($this->validateRequest());
        return redirect('/endpoints/' . $endpoint->id);
    }
}