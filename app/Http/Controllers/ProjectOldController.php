<?php
namespace CodeProject\Http\Controllers;
use CodeProject\Http\Requests;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
class ProjectOldController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;
    /**
     * @var ProjectService
     */
    private $service;
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            return $this->repository->with(['owner','client'])->all();
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao listar os projetos.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            return $this->repository->create($request->all());
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao cadastrar o projeto.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return $this->repository->with(['owner','client'])->find($id);
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao exibir o projeto.');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            return $this->repository->update($request->all(), $id);
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao atualizar o projeto.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $this->repository->find($id)->delete();
        }
        catch(QueryException $e){
            return $this->erroMsgm('Projeto não pode ser apagado pois existe um ou mais clientes vinculados a ele.');
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao excluir o projeto.');
        }
    }
    private function erroMsgm($mensagem)
    {
        return [
            'error' => true,
            'message' => $mensagem,
        ];
    }
}