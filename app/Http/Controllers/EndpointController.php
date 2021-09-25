<?php
namespace App\Http\Controllers;
use App\Endpoint;
use App\Rules\CorsOrigin;
use App\Rules\EntryBelongsToUser;
use Illuminate\Validation\Rule;
class EndpointController extends Controller
{
    protected array $validTimeUnits = [
        'month', 'week', 'day', 'hour', 'minute'
    ];
    public function create()
    {
        $endpoint = new Endpoint();
        $credentials = auth()->user()->credentials;
        return view('endpoints.create', compact(['endpoint', 'credentials']));
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
            'cors_origin' => ['required', new CorsOrigin],
            'subject' => ['required', 'max:255'],
            'monthly_limit' => ['required', 'numeric', 'min:0'],
            'client_limit' => ['required', 'numeric', 'min:0'],
            'time_unit' => ['required', 'string', Rule::in($this->validTimeUnits)],
            'credential_id' => ['required', 'exists:credentials,id', new EntryBelongsToUser('credentials')]
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
        $credentials = auth()->user()->credentials;
        return view('endpoints.show', compact(['endpoint', 'credentials']));
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
