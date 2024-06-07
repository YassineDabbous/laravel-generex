@@extends('{!!$o->packageName!!}::layout')
@@section('content')

<div class="mt-4 max-w-2xl mx-auto bg-white p-4 rounded-lg shadow-lg flex justify-between">
    <h2 class="text-2xl font-bold">{{ ucfirst($o->tableName) }}</h2>
    <a href="@{{ route('{!! $o->tableName !!}.create') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700 mr-2">Create</a>
</div>

@@foreach($items as $model)    
    <div class="mt-4 max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        @@include('{!!$o->packageName!!}::card', ['model'=>$model])
    </div>
@@endforeach

@@endsection