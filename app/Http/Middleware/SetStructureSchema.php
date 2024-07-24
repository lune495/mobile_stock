<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetStructureSchema
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->structure) {
            $schemaName = $user->structure->schema_name;
            DB::statement("SET search_path TO {$schemaName}");
            \Log::info("Schema set to: " . $schemaName); // Ajoutez cette ligne pour vérifier le schéma
        }

        return $next($request);
    }
}
