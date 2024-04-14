@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $o->packageNamespace }}\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use {{ $o->packageNamespace }}\Models\{{ $o->modelName }};
use {{ $o->packageNamespace }}\Http\Requests\{{ $o->modelName }}Request;
use {{ $o->packageNamespace }}\Concerns\Has{{ $o->modelName }}QueryBuilder;


class {{ $o->modelName }}ApiController
{
    use Has{{ $o->modelName }}QueryBuilder;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('index', {{ $o->modelName }}::class);

        $filter = $this->filter({{ $o->modelName }}::query(), $request);

        $results = $filter->paginate();

        return response()->json($results);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store({{ $o->modelName }}Request $request)
    {
        Gate::authorize('create', {{ $o->modelName }}::class);

        $model = {{ $o->modelName }}::create($request->validated());

        return response()->json($model);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $model = {{ $o->modelName }}::findOrFail($id);
        
        Gate::authorize('show', $model);

        return response()->json($model);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update({{ $o->modelName }}Request $request, $id)
    {
        $model = {{ $o->modelName }}::findOrFail($id);

        Gate::authorize('update', $model);

        $model->update($request->validated());

        return response()->json($model);
    }

    /**
     * Delete the specified resource.
    */
    public function destroy($id)
    {
        $model = {{ $o->modelName }}::findOrFail($id);

        Gate::authorize('delete', $model);

        $model->delete();

        return response()->json();
    }
}
