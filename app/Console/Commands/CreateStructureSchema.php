<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Structure;
use Illuminate\Support\Facades\DB;

class CreateStructureSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:create-schema {name}';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $structureName = $this->argument('name');
        $schemaName = 'structure_' . strtolower($structureName);

        // Crée la structure dans la base de données principale
        $structure = Structure::create([
            'name' => $structureName,
            'schema_name' => $schemaName,
        ]);

        // Crée le schéma pour la structure
        DB::statement("CREATE SCHEMA $schemaName");

        // Exécute les migrations sur le nouveau schéma
        $this->call('migrate', [
            '--path' => '/database/migrations/structures',
            '--database' => 'structure',
            '--schema' => $schemaName,
        ]);

        $this->info("Schema $schemaName created successfully for structure $structureName.");
    }
}