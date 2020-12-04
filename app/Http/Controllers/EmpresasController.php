<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class EmpresasController extends Controller
{
    public function index(){
        return Inertia::render('Empresas/index');
    }
    public function ConsultarEmpresas(){
        try {
            $Empresas = DB::TABLE('empresa')
                    ->GET();
            return response()->json([
                'Response' => true,
                'Mensaje' => 'Empresas consultadas correctamente',
                'Empresas' => $Empresas
            ], 200);
        } catch (Exception $e) {
            //throw $th;
        }
    }
    public function GuardarEmpresa(Request $request){
        switch ($request->Accion) {
            case 'Guardar':
                // Validar que la empresa con el nit indicado no exista
                if ($this->ValidarEmpresa($request->Empresa)['Estado']) {
                    return response()->json([
                        'Response' => false,
                        'Mensaje' => $this->ValidarEmpresa($request->Empresa)['Mensaje'],
                    ], 200);
                }
                DB::beginTransaction();
                try {
                    $Empresa = DB::TABLE('empresa')
                                    ->INSERT([
                                        'Nit' => $request->Empresa['NIT'],
                                        'Nombre' => $request->Empresa['Nombre'],
                                        'Telefono' => $request->Empresa['Telefono'],
                                        'Direccion' => $request->Empresa['Direccion'],
                                        'Email' => $request->Empresa['Email'],
                                    ]);
                    DB::commit();
                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Empresa guardada correctamente!',
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        'Response' => false,
                        'Mensaje' => 'Hubo un error al guardar la empresa, por favor contactar al administrador del sistema.',
                        'mensajeError' => $e->getMessage()
                    ], 500);
                }
                break;
            case 'Editar':
                // Validar que la empresa con el nit indicado no exista
                if ($this->ValidarEmpresa($request->Empresa)['Estado']) {
                    return response()->json([
                        'Response' => false,
                        'Mensaje' => $this->ValidarEmpresa($request->Empresa)['Mensaje'],
                    ], 200);
                }
                DB::beginTransaction();
                try {
                    $Empresa = DB::TABLE('empresa')
                                    ->WHERE('id', $request->Empresa['id'])
                                    ->UPDATE([
                                        'Nit' => $request->Empresa['NIT'],
                                        'Nombre' => $request->Empresa['Nombre'],
                                        'Telefono' => $request->Empresa['Telefono'],
                                        'Direccion' => $request->Empresa['Direccion'],
                                        'Email' => $request->Empresa['Email'],
                                    ]);
                    DB::commit();
                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Empresa actualizada correctamente!',
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        'Response' => false,
                        'Mensaje' => 'Hubo un error al actualizar la empresa, por favor contactar al administrador del sistema.',
                        'mensajeError' => $e->getMessage()
                    ], 500);
                }
                break;
            default:
                return response()->json([
                    'Response' => false,
                    'Mensaje' => ['La acción solicitada no existe']
                ], 200);
                break;
        }
    }
    private function ValidarEmpresa($Empresa){
        try {
            $Estado = false;
            $Mensaje = array();
            $Empresa = DB::TABLE('empresa')
                        ->WHERE('Nit', $Empresa['NIT'])
                        ->WHERE('id', '<>', $Empresa['id'])
                        ->GET();
            if (count($Empresa) > 0) {
                $Estado = true;
                array_push($Mensaje, 'El NIT ingresado ya ha sido asignado a otra empresa');
            }
            return [
                'Estado' => $Estado,
                'Mensaje' => $Mensaje
            ];
        } catch (Exception $e) {
            
        }
    }
}
