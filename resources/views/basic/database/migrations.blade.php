@phpTag

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function down()
    {
        Schema::{!! !$o->connectionName ? '' : "connection('{$o->connectionName}')->" !!}dropIfExists('{{ $o->tableName }}');
    }

    public function up()
    {
        Schema::{!! !$o->connectionName ? '' : "connection('{$o->connectionName}')->" !!}create('{{ $o->tableName }}', function (Blueprint $table) {
            
    @foreach($o->migrationLines as $line)
        {!! $line !!}
    @endforeach

        });
    }
};