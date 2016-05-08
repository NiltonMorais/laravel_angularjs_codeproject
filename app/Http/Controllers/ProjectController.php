<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Http\Requests;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Exceptions\NoActiveAccessTokenException;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectTaskRepository
     */
    private $taskRepository;

    /**
     * @var ProjectService
     */
    private $service;


    public function __construct(ProjectRepository $repository, ProjectService $service, ProjectTaskRepository $taskRepository)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index()
        {
            try
            {
                return $this->repository->with(['owner','client'])->findWhere(['owner_id'=> \Authorizer::getResourceOwnerId()]);
            }
            catch(NoActiveAccessTokenException $e){
                return $this->erroMsgm('Usuário não está logado.');
            }
            catch(\Exception $e){
                return $this->erroMsgm('Ocorreu um erro ao listar os projetos. Erro: '.$e->getMessage());
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
            return $this->service->create($request->all());
        }
        catch(NoActiveAccessTokenException $e){
            return $this->erroMsgm('Usuário não está logado.');
        }
        catch(ValidatorException $e){
            $error = $e->getMessageBag();
            return [
                'error' => true,
                'message' => "Erro ao cadastrar o projeto, alguns campos são obrigatórios!",
                'messages' => $error->getMessages(),
            ];
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
        try
        {
            if(!$this->checkProjectPermissions($id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            return $this->repository->with(['owner','client'])->find($id);
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(NoActiveAccessTokenException $e){
            return $this->erroMsgm('Usuário não está logado.');
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
        try
        {
            if(!$this->checkProjectOwner($id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            return $this->service->update($request->all(), $id);
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(NoActiveAccessTokenException $e){
            return $this->erroMsgm('Usuário não está logado.');
        }
        catch(ValidatorException $e){
            $error = $e->getMessageBag();
            return [
                'error' => true,
                'message' => "Erro ao atualizar o projeto, alguns campos são obrigatórios!",
                'messages' => $error->getMessages(),
            ];
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
        try
        {
            if(!$this->checkProjectOwner($id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            $this->repository->skipPresenter()->find($id)->delete();
        }
        catch(QueryException $e){
            return $this->erroMsgm('Projeto não pode ser apagado pois existe um ou mais clientes vinculados a ele.');
        }
        catch(ModelNotFoundException $e){
            return $this->erroMsgm('Projeto não encontrado.');
        }
        catch(NoActiveAccessTokenException $e){
            return $this->erroMsgm('Usuário não está logado.');
        }
        catch(\Exception $e){
            return $this->erroMsgm('Ocorreu um erro ao excluir o projeto.');
        }
    }


    public function members($id)
    {
        try {

            if(!$this->checkProjectOwner($id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }

            $members = $this->repository->find($id)->members()->get();

            if (count($members)) {
                return $members;
            }
            return $this->erroMsgm('Esse projeto ainda não tem membros.');

        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Projeto não encontrado.');
        } catch (QueryException $e) {
            return $this->erroMsgm('Cliente não encontrado.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao exibir os membros do projeto.');
        }

    }

    public function addMember($project_id, $member_id)
    {
        try {
            if(!$this->checkProjectOwner($project_id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            return $this->service->addMember($project_id, $member_id);
        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Projeto não encontrado.');
        } catch (QueryException $e) {
            return $this->erroMsgm('Cliente não encontrado.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao inserir o membro.');
        }
    }

    public function removeMember($project_id, $member_id)
    {
        try {
            if(!$this->checkProjectOwner($project_id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            return $this->service->removeMember($project_id, $member_id);
        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Projeto não encontrado.');
        } catch (QueryException $e) {
            return $this->erroMsgm('Cliente não encontrado.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao remover o membro.');
        }
    }

    public function tasks($id)
    {
        try {

            if(!$this->checkProjectOwner($id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            $tasks = $this->taskRepository->find(['project_id' => $id]);

            if (count($tasks)) {
                return $tasks;
            }
            return $this->erroMsgm('Esse projeto ainda não tem tarefas.');

        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Projeto não encontrado.');
        } catch (QueryException $e) {
            return $this->erroMsgm('Tarefa não encontrada.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao exibir as tarefas do projeto.');
        }
    }

    public function addTask(Request $request)
    {
        try {
            return $this->taskRepository->create($request->all());
        } catch (ValidatorException $e) {
            $error = $e->getMessageBag();
            return [
                'error' => true,
                'message' => "Erro ao cadastrar a tarefa, alguns campos são obrigatórios!",
                'messages' => $error->getMessages(),
            ];
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao cadastrar a tarefa.');
        }
    }

    public function removeTask($project_id, $task_id)
    {
        try {

            if(!$this->checkProjectOwner($project_id)){
                return $this->erroMsgm("O usuário não tem acesso a esse projeto");
            }
            $this->taskRepository->find($task_id)->delete();
            return ['success'=>true, 'message'=>'Tarefa deletada com sucesso!'];
        } catch (QueryException $e) {
            return $this->erroMsgm('Tarefa não pode ser apagado pois existe um projeto vinculado a ela.');
        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Tarefa não encontrada.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao excluir a tarefa.');
        }
    }

    private function checkProjectOwner($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();

        return $this->repository->isOwner($projectId,$userId);
    }

    private function checkProjectMember($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();

        return $this->repository->hasMember($projectId,$userId);
    }

    private function checkProjectPermissions($projectId)
    {
        if($this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId)){
            return true;
        }

        return false;
    }


    private function erroMsgm($mensagem)
    {
        return [
            'error' => true,
            'message' => $mensagem,
        ];
    }
}
