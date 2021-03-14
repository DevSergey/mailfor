<?php
namespace App\Http\Controllers;
use App\Credential;
class CredentialsController extends Controller
{
    public function create()
    {
        $credential = new Credential();
        return view('credentials.create', compact('credential'));
    }
    public function store(Credential $credential)
    {
        $credential = auth()->user()->credentials()->create($this->validateRequest());
        return redirect('/credentials/' . $credential->id);
    }
    public function show(Credential $credential)
    {
        if (auth()->user()->isNot($credential->user)) {
            abort(403);
        }
        return view('credentials.show', compact('credential'));
    }
    public function index()
    {
        $credentials = auth()->user()->credentials;
        return view('credentials.index', compact('credentials'));
    }
    public function destroy(Credential $credential)
    {
        if (auth()->user()->isNot($credential->user)) {
            abort(403);
        }
        Credential::destroy($credential->id);
        return redirect('/credentials');
    }
    public function update(Credential $credential)
    {
        if (auth()->user()->isNot($credential->user)) {
            abort(403);
        }
        $credential->update($this->validateRequest());
        return redirect('/credentials/' . $credential->id);
    }
    protected function validateRequest()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'host' => ['required', 'max:255', 'regex:^((?:([a-z0-9]\.|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9])\.)+)([a-z0-9]{2,63}|(?:[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]))\.?$^'],
            'port' => ['required', 'numeric', 'between:0,65535'],
            'from_address' => ['required', 'max:255'],
            'from_name' => ['required', 'max:255'],
            'encryption' => ['required', 'max:20'],
            'username' => ['required', 'max:255'],
            'password' => ['required']
        ]);
    }
}
