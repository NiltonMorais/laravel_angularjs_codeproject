<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectMemberValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectMemberService
{
    /**
     * @var ProjectMemberRepository
     */
    protected $repository;
    protected $validator;
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    public function __construct(ProjectMemberRepository $repository, ProjectMemberValidator $validator, ProjectRepository $projectRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->projectRepository = $projectRepository;
    }

    public function create(array $data)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        }
        catch(ValidatorException $e){
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function delete($id)
    {
        $projectMember = $this->repository->skipPresenter()->find($id);
        return $projectMember->delete();
    }
}