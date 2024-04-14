@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $o->packageNamespace }}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {{ $o->modelName }}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
    @foreach($o->editableFields as $field)
        @if(isset($field['rules']))
        '{{ $field['name'] }}' => [{!! implode(', ', array_map(fn($v) => "'$v'", $field['rules'] )) !!}],
        @endif
    @endforeach
    
        ];
    }
}
