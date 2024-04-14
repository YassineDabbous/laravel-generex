@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $o->packageNamespace }}\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use {{ $o->packageNamespace }}\Models\{{ $o->modelName }}; 

class {{ $o->modelName }}Policy
{
    use HandlesAuthorization;

    public function create($user) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function show($user, {{ $o->modelName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function update($user, {{ $o->modelName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}')  ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    public function delete($user, {{ $o->modelName }} $model) : Response|bool
    {
        if( ! $user->can('manage_{{ $o->tableName }}') ){
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

}
