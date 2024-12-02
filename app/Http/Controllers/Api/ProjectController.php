<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use App\Models\Project;

class ProjectController {
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? false,
            'perPage' => $request->perPage ?? 10,
            'page' => $request->page ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
            'sortByDate' => $request->sortByDate ?? false,
            'sortByEngagements' => $request->sortByDate ?? 'ASC',
        ];
        return $this->projectService->getAllProjects($params);
    }

    public function show(int $id){
        return $this->projectService->getProject($id);
    }

    public function store(StoreProjectRequest $request){
        return $this->projectService->createProject($request->validated());
    }

    public function update(UpdateProjectRequest $request, int $id){
        return $this->projectService->updateProject($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->projectService->deleteProject($id);
    }
}
