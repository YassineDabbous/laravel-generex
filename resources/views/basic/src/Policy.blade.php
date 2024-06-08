@phpTag

namespace {{ $o->packageNamespace }}\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use {{ $o->packageNamespace }}\Models\{{ $o->modelClassName }}; 

class {{ $o->policyClassName }}
{
    use HandlesAuthorization;

    public function index($user) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function create($user) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function show($user, {{ $o->modelClassName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function update($user, {{ $o->modelClassName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}')  ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function delete($user, {{ $o->modelClassName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

}
