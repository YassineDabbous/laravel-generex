@@extends('{!!$o->packageName!!}::layout')
@@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Create {{$o->modelName}}</h2>
        @@include('{!!$o->packageName!!}::{!!$o->tableName!!}.form')
</div>

@@endsection