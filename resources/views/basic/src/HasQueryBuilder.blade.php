@phpTag

namespace {{ $o->packageNamespace }}\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Has{{ $o->modelName }}QueryBuilder
{
  
    public function filter(Builder $q, Request $request) : Builder {

@foreach($o->fields as $field)
        if($request->filled('{{ $field['name'] }}')){
            $q->where('{{ $field['name'] }}', $request->{{ $field['name'] }});
        }
@endforeach
    
        return $q;
    }

}
