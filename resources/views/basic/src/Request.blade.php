@phpTag

namespace {{ $o->packageNamespace }}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {{ $o->requestClassName }} extends FormRequest
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
    @foreach($o->fieldsWithCreateRules as $field)
    '{{ $field->name }}' => [{!! implode(', ', array_map(fn($v) => "'$v'", $field->rules )) !!}],
    @endforeach     
        ];
    }
}
