@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $o->packageNamespace }}\Models;

use Illuminate\Database\Eloquent\Model;
@if($o->useSoftDeletes)
use Illuminate\Database\Eloquent\SoftDeletes;
@endif

/**
 * Class {{ $o->modelName }}
@foreach($o->fields as $field)
 * @@property ${{ $field['name'] }}
@endforeach
 */
class {{ $o->modelName }} extends Model
{
    @if($o->useSoftDeletes)
    use SoftDeletes;
    @endif
    protected $table = '{{ $o->tableName }}';

    /**
     * Attributes that should be mass-assignable.
     */
    protected $fillable = [{!! $o->modelFillableValues !!}];

}
