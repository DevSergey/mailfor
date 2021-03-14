<?php
namespace App\Http\Controllers;
use App\Rules\InfectedKeyword;
use App\Validation;
class ValidationController extends Controller
{
    public function create()
    {
        $validation = new Validation();
        return view('validations.create', compact('validation'));
    }
    public function store()
    {
        $validation = auth()->user()->validations()->create($this->validateRequest());
        return redirect('/validations/' . $validation->id);
    }
    public function index()
    {
        $validations = auth()->user()->validations;
        return view('validations.index', compact('validations'));
    }
    public function show(Validation $validation)
    {
        if (auth()->user()->isNot($validation->user)) {
            abort(403);
        }
        return view('validations.show', compact('validation'));
    }
    public function destroy(Validation $validation)
    {
        if (auth()->user()->isNot($validation->user)) {
            abort(403);
        }
        Validation::destroy($validation->id);
        return redirect('/validations');
    }
    public function update(Validation $validation)
    {
        if (auth()->user()->isNot($validation->user)) {
            abort(403);
        }
        $validation->update($this->validateRequest());
        return redirect('/validations/' . $validation->id);
    }
    protected function validateRequest()
    {
        return request()->validate([
            'name' => ['required', 'max:255'],
            'validation' => ['required', 'string', new InfectedKeyword]
        ]);
    }
}
