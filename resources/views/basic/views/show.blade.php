@@extends('{!!$o->packageName!!}::layout', ['title' => $model->name])
@@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">{{$o->modelName}} Details</h2>

    @@include('{!!$o->packageName!!}::card')
    
</div>

@@endsection