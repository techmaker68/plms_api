<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Department\Contracts\PLMSDepartmentServiceContract;
use Modules\Department\Http\Requests\DepartmentRequest;
use Modules\Department\Http\Requests\DepartmentStoreRequest;
use Modules\Department\Transformers\DepartmentResource;

class PLMSDepartmentController extends BaseController
{
    protected PLMSDepartmentServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(PLMSDepartmentServiceContract $service)
    {
        $this->service = $service;
                
        // $this->middleware('permission:DEPARTMENTS_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:DEPARTMENT_ADD', ['only' => ['store']]);
        $this->middleware('permission:DEPARTMENT_EDIT', ['only' => ['update']]);
        $this->middleware('permission:DEPARTMENT_DELETE', ['only' => ['destroy']]);

    }

    public function index(DepartmentRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->all());
            $departments = $this->service->{$filters['method']}($filters, ['company']);
            $resourceCollection = DepartmentResource::collection($departments);
            return $resourceCollection->response();
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DepartmentStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $department = $this->service->store($this->service->prepareDataForStorage($validatedData));
            return (new DepartmentResource($department))->response();
        });
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\PLMSDepartment');

        return $this->tryCatch(function () use ($id) {
            $department = $this->service->show($id);
            $department = $this->loadDepartmentRelationships($department);
            return (new DepartmentResource($department))->response();
        });
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DepartmentStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\PLMSDepartment');

        return $this->tryCatch(function () use ($request, $id) {
            $visa = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            return (new DepartmentResource($visa))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\PLMSDepartment');
        
        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    /**
     * Load necessary relationships for a department.
     *
     * @param mixed $department The department entity.
     * @return mixed The department entity with loaded relationships.
     */
    protected function loadDepartmentRelationships($department)
    {
        return $department->load([
            'company', 
        ]);
    }
}
