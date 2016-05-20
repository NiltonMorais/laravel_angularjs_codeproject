<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Http\Requests;
use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Services\ProjectFileService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Exceptions\NoActiveAccessTokenException;

class ProjectFileController extends Controller
{
    /**
     * @var ProjectFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;

    public function __construct(ProjectFileRepository $repository, ProjectFileService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return $this->erroMsgm("O arquivo é obrigatório!");
        }

        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['project_id'] = $request->project_id;

        return $this->service->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->service->checkProjectPermissions($id) == false) {
            return $this->erroMsgm("O usuário não tem acesso a esse projeto");
        }

        return $this->repository->find($id);
    }

    public function showFile($id)
    {
        if ($this->service->checkProjectPermissions($id) == false) {
            return $this->erroMsgm("O usuário não tem acesso a esse projeto");
        }
        $filePath = $this->service->getFilePath($id);
        $fileContent = file_get_contents($filePath);
        $file64 = base64_encode($fileContent);
        return [
            'file' => $file64,
            'size' => filesize($filePath),
            'name' => $this->service->getFileName($id)
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (!$this->service->checkProjectOwner($id)) {
                return $this->erroMsgm("O usuário não é onwer desse projeto");
            }
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return $this->erroMsgm('Projeto não encontrado.');
        } catch (NoActiveAccessTokenException $e) {
            return $this->erroMsgm('Usuário não está logado.');
        } catch (\Exception $e) {
            return $this->erroMsgm('Ocorreu um erro ao atualizar o projeto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->service->checkProjectOwner($id)) {
            return $this->erroMsgm("O usuário não é owner desse projeto");
        }

        $this->service->delete($id);
        return ['error'=>false,'Arquivo deletado com sucesso'];
    }

    private function erroMsgm($mensagem)
    {
        return [
            'error' => true,
            'message' => $mensagem,
        ];
    }
}
