<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;

class HistoriaUsuarioController extends Controller
{
    public function index($id){
        try {
            $Historias = DB::TABLE('historiausuario')
                            ->WHERE('idProyecto', $id)
                            ->GET();
            
            $Proyecto = DB::TABLE('proyecto')
                        ->WHERE('id', $id)
                        ->GET();
            
            return Inertia::render('Proyectos/HistoriasProyecto', ['Proyecto' => $id, 'HistoriasProyecto' => $Historias, 'nomProyecto' => $Proyecto[0]->Titulo]);
        } catch (Exception $e) {
            
        }
    }
    public function ConsultarHistoriasUsuario($id){
        try {
            $Historias = DB::TABLE('historiausuario')
                            ->WHERE('idProyecto', $id)
                            ->GET();
            
            return response()->json([
                'Response' => true,
                'Mensaje' => 'Historias consultadas correctamente',
                'Historias' => $Historias
            ], 200);
            
            
        } catch (Exception $e) {
            
        }
    }
    public function GuardarHistoria(Request $request){
        $ValidarForm = Validator::make($request->Historia, [
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
                    $Historia = DB::TABLE('historiausuario')
                                ->INSERT([
                                    'Titulo' => $request->Historia['Titulo'],
                                    'idProyecto' => $request->Historia['Proyecto'],
                                ]);

                    DB::commit();

                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Historia creada correctamente'
                    ], 200);

                } catch (Exception $e) {
                    //throw $th;
                }
                break;
            
            default:
                # code...
                break;
        }
    }
    public function GuardarTicket(Request $request){

        $ValidarForm = Validator::make($request->Ticket, [
            'Comentarios' => ['required', 'string', 'max:255'],
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
                    $Ticket = DB::TABLE('ticket')
                                ->INSERT([
                                    'Comentarios' => $request->Ticket['Comentarios'],
                                    'idHistoriaUsuario' => $request->Ticket['idHistoria'],
                                    'idUsuario' => Auth::user()->id
                                ]);

                    DB::commit();

                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Ticket creado correctamente'
                    ], 200);

                } catch (Exception $e) {
                    //throw $th;
                }
                break;
            case 'Editar':
                DB::beginTransaction();
                try {
                    $Ticket = DB::TABLE('ticket')
                                ->WHERE('id', $request->Ticket['id'])
                                ->UPDATE([
                                    'Comentarios' => $request->Ticket['Comentarios'],
                                ]);

                    DB::commit();

                    return response()->json([
                        'Response' => true,
                        'Mensaje' => 'Ticket actualizado correctamente'
                    ], 200);

                } catch (Exception $e) {
                    //throw $th;
                }
                break;
            default:
                # code...
                break;
        }
    }
    public function ConsultarTickets($id){
        try {
            $Tickets = DB::TABLE('ticket AS T')
                        ->WHERE('T.idHistoriaUsuario', $id)
                        ->WHERE('Estado', '<>', 'C')
                        ->JOIN('users AS U', 'T.idUsuario', '=', 'U.id')
                        ->SELECT('T.*', 'U.name', DB::raw('DATE_FORMAT(T.Fecha, "%d/%m/%Y") as FechaOrdenada'))
                        ->ORDERBY('T.Fecha', 'desc')
                        ->GET();
            return response()->json([
                'Response' => true,
                'Mensaje' => 'Tickets consultados correctamente',
                'Tickets' => $Tickets
            ], 200);
        } catch (Exception $e) {
            //throw $th;
        }
    }
    public function EstadoTicket(Request $request){
        DB::beginTransaction();
        try {
            $Ticket = DB::TABLE('ticket')
                        ->WHERE('id', $request->id)
                        ->UPDATE(['Estado' => $request->Estado]);

            DB::commit();

            return response()->json([
                'Response' => true,
                'Mensaje' => 'Estado actualizado correctamente!'
            ], 200);
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
