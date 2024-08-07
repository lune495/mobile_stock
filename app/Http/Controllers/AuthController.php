<?php

namespace App\Http\Controllers;

use App\Models\{User,Outil};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $queryName = "users";
    // Register
    public function register(Request $request ) {
        $fields = $request->validate([
            'email' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string|confirmed',
            'role_id' => 'required|integer',
            'structure_id' => 'required|integer',
        ]);
      $user =  User::create([
            'name' => $fields['name'],
            'email' => $fields['email'], 
            'password' => bcrypt($fields['password']),
            'role_id' => $fields['role_id'],
            'structure_id' => $fields['structure_id'],
             
        ]);
        $id = $user->id;
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' =>  $user,
            // 'user' =>  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]),
            'token' => $token
        ];

        return $response;
        // return Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
    }

     public function login(Request $request ) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        // Check email
        $user = User::with('role','structure')->where('email',$fields['email'])->first();
        //Check email
            if (!$user || !Hash::check($fields['password'],$user->password)) {
                return response([
                    'message' => 'Mot de passe Incorrect'
                ],401);
                # code...
            }
        // if ($user->structure) {
        //     $schemaName = $user->structure->schema_name;
        //     DB::statement("SET search_path TO {$schemaName}");
        //     \Log::info("Schema set to: " . $schemaName); // Ajoutez cette ligne pour vérifier le schéma
        // }
        $token = $user->createToken('myapptoken')->plainTextToken;
        // return Outil::redirectgraphql($this->queryName, "id:{$user->id}", Outil::$queries[$this->queryName],$token);
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Deconnecte'
        ];
    }
}
