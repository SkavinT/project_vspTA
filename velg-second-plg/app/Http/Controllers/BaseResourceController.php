<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class BaseResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $modelClass;

    /** folder views, contoh: 'produks' */
    protected string $view;

    /** rules untuk store */
    protected array $rules = [];

    /** rules untuk update (override jika perlu) */
    protected function updateRules(int|string $id): array
    {
        return $this->rules;
    }

    public function index(): View
    {
        $items = ($this->modelClass)::latest()->paginate(12);
        return view("{$this->view}.index", compact('items'));
    }

    public function create(): View
    {
        return view("{$this->view}.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules);
        ($this->modelClass)::create($data);
        return redirect()->route("{$this->view}.index")->with('status', 'Saved');
    }

    public function show($id): View
    {
        $item = ($this->modelClass)::findOrFail($id);
        return view("{$this->view}.show", compact('item'));
    }

    public function edit($id): View
    {
        $item = ($this->modelClass)::findOrFail($id);
        return view("{$this->view}.edit", compact('item'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $item = ($this->modelClass)::findOrFail($id);
        $data = $request->validate($this->updateRules($item->getKey()));
        $item->update($data);
        return redirect()->route("{$this->view}.index")->with('status', 'Updated');
    }

    public function destroy($id): RedirectResponse
    {
        $item = ($this->modelClass)::findOrFail($id);
        $item->delete();
        return back()->with('status', 'Deleted');
    }
}