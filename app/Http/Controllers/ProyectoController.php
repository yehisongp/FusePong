<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;
use Validator;

class ProyectoController extends Controller
{
    public function index(){
        return Inertia::render('Proyectos/index');
    }
    public function GuardarProyecto(Request $request){
        $ValidarForm = Validator::make($request->Proyecto, [
            'Titulo' => ['required', 'string', 'max:255'],
        ]);

        if (!$ValidarForm->passes()) {
            return response()->json([
                'Response' => false,
                'Mensaje' => $ValidarForm->errors()->all()
            ], 200);
        }

        switch ($request->Accion) {
            case 'Guardar':
                DB::beginTransaction();
                try {
                    $Proyecto = DB::TABLE('proyecto')
                                ->INSERT([
                                    'Titulo' => $request->Proyecto['Titulo'],
                                    'idEmpresa' => Auth::user()->idEmpresa
                                ]);

                    DB::commit();

                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Proyecto guardado correctamente!'
                    ], 200);
                    
                } catch (Exception $e) {
                    //throw $th;
                }
                break;
            case 'Editar':
                DB::beginTransaction();
                try {
                    $Proyecto = DB::TABLE('proyecto')
                                ->WHERE('id', $request->Proyecto['id'])
                                ->UPDATE([
                                    'Titulo' => $request->Proyecto['Titulo'],
                                ]);

                    DB::commit();

                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Proyecto actualizado correctamente!'
                    ], 200);
                    
                } catch (Exception $e) {
                    //throw $th;
                }
                break;
            default:
                return response()->json([
                    'Response' => false,
                    'Mensaje' => 'La acción solicitada no existe'
                ], 200);
                break;
        }
    }
    public function ConsultarProyectos(){
        try {
            $Proyectos = DB::TABLE('Proyecto')
                            ->WHERE('idEmpresa', Auth::user()->idEmpresa)
                            ->SELECT('Proyecto.*', DB::raw('DATE_FORMAT(Proyecto.Fecha, "%d/%m/%Y") as Fecha'))
                            ->GET();
            return response()->json([
                'Response' => true,
                'Mensaje' => 'Proyectos consultados correctamente',
                'Proyectos' => $Proyectos
            ], 200);
                
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
