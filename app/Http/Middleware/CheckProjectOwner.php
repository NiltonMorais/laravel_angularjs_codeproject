<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Services\ProjectService;
use Illuminate\Support\Facades\Response;

class CheckProjectOwner
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

        if($this->service->checkProjectOwner($projectId) == false){
            return Response::json([
                'error' => true,
                'message' => "Você não tem permissão de owner neste projeto!"
            ], 400);
        }

        return $next($request);
    }
}
