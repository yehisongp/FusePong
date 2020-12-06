<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\HistoriaUsuarioController;
use App\Http\Controllers\UsuarioController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/registrarUsuarioView', [UsuarioController::class, 'RegistrarUsuarioView'])->name('registro');
Route::post('/registrarUsuario', [UsuarioController::class, 'RegistrarUsuario']);


Route::middleware(['auth:sanctum', 'verified'])->get('/Empresas', [EmpresasController::class, 'index'])->name('Empresas');
Route::get('/consultarEmpresas', [EmpresasController::class, 'ConsultarEmpresas']);
Route::middleware(['auth:sanctum', 'verified'])->post('/guardarEmpresa', [EmpresasController::class, 'GuardarEmpresa']);

Route::middleware(['auth:sanctum', 'verified'])->get('/Proyectos', [ProyectoController::class, 'index'])->name('Proyectos');
Route::middleware(['auth:sanctum', 'verified'])->post('/guardarProyecto', [ProyectoController::class, 'GuardarProyecto']);
Route::middleware(['auth:sanctum', 'verified'])->get('/consultarProyectos', [ProyectoController::class, 'ConsultarProyectos']);

Route::middleware(['auth:sanctum', 'verified'])->get('/consultarHistoriasProyecto/{id}', [HistoriaUsuarioController::class, 'index'])->name('HistoriaUsuario');
Route::middleware(['auth:sanctum', 'verified'])->get('/consultarHistoriasUsuario/{id}', [HistoriaUsuarioController::class, 'ConsultarHistoriasUsuario']);
Route::middleware(['auth:sanctum', 'verified'])->post('/guardarHistoria', [HistoriaUsuarioController::class, 'GuardarHistoria']);
Route::middleware(['auth:sanctum', 'verified'])->post('/guardarTicket', [HistoriaUsuarioController::class, 'GuardarTicket']);
Route::middleware(['auth:sanctum', 'verified'])->get('/consultarTickets/{id}', [HistoriaUsuarioController::class, 'ConsultarTickets']);
Route::middleware(['auth:sanctum', 'verified'])->post('/estadoTicket', [HistoriaUsuarioController::class, 'EstadoTicket']);