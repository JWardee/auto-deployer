<?php

use App\Project;
use Illuminate\Support\Facades\Route;

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

//$project = Project::first();
//dd($project->getPublicKeyPath());
//dd($project->generateSshKeys());
//dd($project->hasDeployKey());
//dd($project->deploy());

Route::get('/', function () {
    return redirect('/nova');
});
