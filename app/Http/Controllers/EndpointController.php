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
        return view('endpoints.create', [
            'endpoint' => new Endpoint(),
            'credentials' => auth()->user()->credentials,
            'receivers' => auth()->user()->receivers
        ]);
    }
    public function store()
    {
        $this->validateRequest();
        $endpoint = auth()->user()->endpoints()->create(request(['name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id']));
        $endpoint->receivers()->attach(request('receivers'));
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
            'credential_id' => ['required', 'exists:credentials,id', new EntryBelongsToUser('credentials')],
            'receivers' => ['required', new EntryBelongsToUser('receivers')]
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
        return view('endpoints.show', [
            'endpoint' => $endpoint,
            'credentials' => auth()->user()->credentials,
            'receivers' => auth()->user()->receivers
        ]);
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
        $this->validateRequest();
        $endpoint->update(request(['name', 'cors_origin', 'subject', 'monthly_limit', 'client_limit', 'time_unit', 'credential_id']));
        $endpoint->receivers()->attach(request('receivers'));
        return redirect('/endpoints/' . $endpoint->id);
    }
}
