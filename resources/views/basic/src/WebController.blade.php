@phpTag

namespace {{ $o->packageNamespace }}\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use {{ $o->packageNamespace }}\Models\{{ $o->modelClassName }};
use {{ $o->packageNamespace }}\Http\Requests\{{ $o->requestClassName }};


class {{ $o->modelName }}WebController
{
    
    public function index(Request $request) : View
    {
        // Gate::authorize('index', {{ $o->modelClassName }}::class);

        $items = {{ $o->modelClassName }}::paginate();

        return view('{{ $o->packageName }}::{{ $o->tableName }}.index', compact('items'));
    }


    public function create(): View
    {
        // Gate::authorize('create', {{ $o->modelClassName }}::class);
        
        return view('{{ $o->packageName }}::{{ $o->tableName }}.create');
    }



    public function store({{ $o->requestClassName }} $request): RedirectResponse
    {
        // Gate::authorize('create', {{ $o->modelClassName }}::class);

        $model = {{ $o->modelClassName }}::create($request->validated());

        return to_route('{{ $o->tableName }}.show', $model->id);
    }



    public function show($id) : View
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);
        
        // Gate::authorize('show', $model);

        return view('{{ $o->packageName }}::{{ $o->tableName }}.show', compact('model'));
    }



    public function edit($id) : View
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);
        
        // Gate::authorize('update', $model);

        return view('{{ $o->packageName }}::{{ $o->tableName }}.edit', compact('model'));
    }



    public function update({{ $o->requestClassName }} $request, $id): RedirectResponse
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);

        // Gate::authorize('update', $model);

        $model->update($request->validated());

        return to_route('{{ $o->tableName }}.show', $model->id);
    }

    

    public function destroy($id): RedirectResponse
    {
        $model = {{ $o->modelClassName }}::findOrFail($id);

        // Gate::authorize('delete', $model);

        $model->delete();

        return to_route('{{ $o->tableName }}.index');
    }
}
