<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;

class UsuarioController extends Controller
{

    public function RegistrarUsuarioView(){
        return Inertia::render('Usuario/Registro');
    }
    public function RegistrarUsuario(Request $request){

        $ValidarForm = Validator::make($request->Usuario, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string']
        ]);

        if (!$ValidarForm->passes()) {
            return response()->json([
                'Response' => false,
                'Mensaje' => $ValidarForm->errors()->all()
            ], 200);
        }
        DB::beginTransaction();
        try {
            $Usuario = DB::TABLE('users')
                    ->INSERT([
                        'name' => $request->Usuario['name'],
                        'email' => $request->Usuario['email'],
                        'password' => Hash::make($request->Usuario['password']),
                        'idEmpresa' => $request->Usuario['Empresa']
                    ]);
            DB::commit();
            return response()->json([
                'Response' => true,
            ], 200);
        } catch (Exception $e) {
            //throw $th;
        }
        

    }
}
