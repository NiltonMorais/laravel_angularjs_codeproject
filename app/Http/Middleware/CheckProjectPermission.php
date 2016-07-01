<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Services\ProjectService;
use Illuminate\Support\Facades\Response;

class CheckProjectPermission
{

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projectId = $request->route('id') ? $request->route('id') : $request->route('project');

        if($this->service->checkProjectPermissions($projectId) == false){
            return Response::json([
                'error' => true,
                'message' => "Você não tem permissão para acessar esse projeto!"
            ], 400);
        }

        return $next($request);
    }
}
