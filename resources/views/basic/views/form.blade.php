@@php
    $model ??= null;
@@endphp
            <form action="@{{ $model ? route('{!! $o->tableName !!}.update', $model->id) : route('{!! $o->tableName !!}.store') }}" method="POST" enctype="multipart/form-data">
                @@csrf
                @@if($model)
                    @@method('PUT')
                @@endif
                @foreach ($o->editableFields as $field)
                    @php
                        $field->inputType ??= 'text';
                    @endphp

                    @if($field->inputType == 'textarea')

                    <div class="mb-4">
                        <label for="{{ $field->name }}" class="block text-gray-700">{{ Str::headline($field->name) }}</label>
                        <textarea name="{{ $field->name }}" id="{{ $field->name }}" rows="4" class="w-full mt-2 p-2 border rounded-lg @@error('{{ $field->name }}') border-red-500 @@enderror">@{{ old('{!! $field->name !!}') ?? $model?->{!! $field->name !!} }}</textarea>
                        @@error('{{ $field->name }}')
                            <p class="text-red-500 text-sm mt-2">@{{ $message }}</p>
                        @@enderror
                    </div>

                    @else

                    <div class="mb-4">
                        <label for="{{ $field->name }}" class="block text-gray-700">{{ Str::headline($field->name) }}</label>
                        <input type="{{ $field->inputType }}" name="{{ $field->name }}" id="{{ $field->name }}" class="w-full mt-2 p-2 border rounded-lg @@error('{{ $field->name }}') border-red-500 @@enderror" value="@{{ old('{!! $field->name !!}') ?? $model?->{!! $field->name !!} }}">
                        @@error('{{ $field->name }}')
                            <p class="text-red-500 text-sm mt-2">@{{ $message }}</p>
                        @@enderror
                    </div>

                    @endif

                @endforeach

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Submit</button>
                </div>
            </form>
