<div >
    @foreach ($o->visibleFields as $field) 
    <div class="mb-4">
        <label class="block text-gray-700">{{$field['name']}}:</label>
        <p class="mt-2 p-2 bg-gray-100 rounded-lg">@{{ $model->{!! $field['name'] !!} }}</p>
    </div>
    @endforeach 

    <div class="flex justify-end">
        <a href="@{{ route('{!! $o->tableName !!}.edit', $model->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 mr-2">Edit</a>
        <form action="@{{ route('{!! $o->tableName !!}.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');">
            @@csrf
            @@method('DELETE')
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700">Delete</button>
        </form>
    </div>

</div>