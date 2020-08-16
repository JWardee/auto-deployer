<?php

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/deploy', function(Request $request) {
    Log::info('Ping to /api/deploy');

    if ($request->has('repository')) {
        $repository = $request->input('repository');

        if (isset($repository['ssh_url'])) {
            Log::info('Valid payload getting model...');

            $project = Project::where('git_repo_ssh_url', $repository['ssh_url'])->first();

            if ($project == null) {
                response('Repository not found', 404);
            }

            // TODO: Need to wrap into a job
            $project->deploy();
        }
    }
});
