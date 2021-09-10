<?php
namespace App\Http\Controllers;
use App\Receiver;
class ReceiverController extends Controller
{
    public function create()
    {
        $receiver = new Receiver();
        return view('receivers.create', compact('receiver'));
    }
    public function store()
    {
        $receiver = auth()->user()->receivers()->create($this->validateRequest());
        return redirect('/receivers/' . $receiver->id);
    }
    protected function validateRequest()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email']
        ]);
    }
    public function index()
    {
        $receivers = auth()->user()->receivers;
        return view('receivers.index', compact('receivers'));
    }
    public function show(Receiver $receiver)
    {
        if (auth()->user()->isNot($receiver->user)) {
            abort(403);
        }
        return view('receivers.show', compact('receiver'));
    }
    public function destroy(Receiver $receiver)
    {
        if (auth()->user()->isNot($receiver->user)) {
            abort(403);
        }
        Receiver::destroy($receiver->id);
        return redirect('/receivers');
    }
    public function update(Receiver $receiver)
    {
        if (auth()->user()->isNot($receiver->user)) {
            abort(403);
        }
        $receiver->update($this->validateRequest());
        return redirect('/receivers/' . $receiver->id);
    }
}
