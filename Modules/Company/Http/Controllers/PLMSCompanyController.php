<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Company\Contracts\PLMSCompanyServiceContract;
use Modules\Company\Http\Requests\CompanyRequest;
use Modules\Company\Http\Requests\CompanyStoreRequest;
use Modules\Company\Transformers\CompanyResource;

class PLMSCompanyController extends BaseController
{
    protected PLMSCompanyServiceContract $service;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(PLMSCompanyServiceContract $service)
    {
        $this->service = $service;
        
        // $this->middleware('permission:COMPANIES_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:COMPANY_ADD', ['only' => ['store']]);
        $this->middleware('permission:COMPANY_EDIT', ['only' => ['update']]);
        $this->middleware('permission:COMPANY_DELETE', ['only' => ['destroy']]);

    }

    public function index(CompanyRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $companies = $this->service->{$filters['method']}($filters, ['country']);
            $resourceCollection = CompanyResource::collection($companies);
            return $resourceCollection->response();
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CompanyStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $company = $this->service->store($this->service->prepareDataForStorage($validatedData));
            return (new CompanyResource($company))->response();
        });
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Company\Entities\PLMSCompany');

        return $this->tryCatch(function () use ($id) {
            $company = $this->service->show($id, ['country']);
            return (new CompanyResource($company))->response();
        });
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CompanyStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Company\Entities\PLMSCompany');

        return $this->tryCatch(function () use ($request, $id) {
            $visa = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            return (new CompanyResource($visa))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Company\Entities\PLMSCompany');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }
}
