@phpTag

namespace {{ $o->packageNamespace }}\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use {{ $o->packageNamespace }}\Models\{{ $o->modelClassName }};
use {{ $o->packageNamespace }}\Http\Requests\{{ $o->requestClassName }};


class {{ $o->modelName }}ApiController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('index', {{ $o->modelClassName }}::class);

        $results = {{ $o->modelClassName }}::paginate();

        return response()->json($results);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store({{ $o->requestClassName }} $request)
    {
        Gate::authorize('create', {{ $o->modelClassName }}::class);

        $model = {{ $o->modelClassName }}::create($request->validated());

        return response()->json($model);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);
        
        Gate::authorize('show', $model);

        return response()->json($model);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update({{ $o->requestClassName }} $request, $id)
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);

        Gate::authorize('update', $model);

        $model->update($request->validated());

        return response()->json($model);
    }

    /**
     * Delete the specified resource.
    */
    public function destroy($id)
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);

        Gate::authorize('delete', $model);

        $model->delete();

        return response()->json();
    }
}
