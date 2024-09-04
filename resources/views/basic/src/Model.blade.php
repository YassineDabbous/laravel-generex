@phpTag

namespace {{ $o->packageNamespace }}\Models;

use Illuminate\Database\Eloquent\Model;
@if($o->useSoftDeletes)
use Illuminate\Database\Eloquent\SoftDeletes;
@endif

/**
 * Class {{ $o->modelClassName }}
@foreach($o->fields as $field)
 * @@property ${{ $field->name }}
@endforeach
 */
class {{ $o->modelClassName }} extends Model
{
    @if($o->useSoftDeletes)
    use SoftDeletes;
    @endif
    @if($o->connectionName)
    protected $connection = '{{ $o->connectionName }}';
    @endif

    protected $table = '{{ $o->tableName }}';
    
    public $timestamps = false;

    /**
     * Attributes that should be mass-assignable.
     */
    protected $fillable = [{!! $o->modelFillableValues !!}];

    
    @if($o->fieldsWithDefaultValues->count()) 
    /**
     * Default Values.
     */
    protected $attributes = [
    @foreach($o->fieldsWithDefaultValues as $field) '{{ $field->name }}' => {!! $field->defaultValue !!}, @endforeach 
    ];
    @endif

}
