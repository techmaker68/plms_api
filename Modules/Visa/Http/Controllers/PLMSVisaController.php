<?php

namespace Modules\Visa\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Visa\Contracts\PLMSVisaServiceContract;
use Modules\Visa\Transformers\VisaResource;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Modules\Visa\Http\Requests\VisaGetRequest;
use Modules\Visa\Http\Requests\VisaCancelRequest;
use Modules\Visa\Http\Requests\VisaStoreRequest;

class PLMSVisaController extends BaseController
{
    protected PLMSVisaServiceContract $service;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(PLMSVisaServiceContract $service)
    {
        $this->service = $service;

        $this->middleware('permission:VISAS_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:VISA_ADD', ['only' => ['store']]);
        $this->middleware('permission:VISA_EDIT', ['only' => ['update','cancelVisa']]);
        $this->middleware('permission:VISA_DELETE', ['only' => ['destroy']]);

    }
    public function index(VisaGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $relations = $this->service->prepareRelations($filters);
            $visas = $this->service->{$filters['method']}($filters, $relations);
            $statusCounts = $this->service->typeCounts($filters['company_id'] ?? null);
            $resourceCollection = VisaResource::collection($visas);
            return $resourceCollection->additional([
                'meta' => [
                    'visa_types_counts' => $statusCounts
                ],
            ])->response();
        });
    }

    public function store(VisaStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $visa = $this->service->store($this->service->prepareDataForStorage($validatedData));
            $visa = $this->loadVisaRelationships($visa);
            return (new VisaResource($visa))->response();
        });
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Visa\Entities\PLMSVisa');

        return $this->tryCatch(function () use ($id) {
            $visa = $this->service->show($id);
            $visa = $this->loadVisaRelationships($visa);
            return response()->json([
                'data'=> new VisaResource($visa),
                'historical_visa'=> VisaResource::collection($visa->historicalVisa()),
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(VisaStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Visa\Entities\PLMSVisa');

        return $this->tryCatch(function () use ($request, $id) {
            $visa = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            $visa = $this->loadVisaRelationships($visa);
            return (new VisaResource($visa))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Visa\Entities\PLMSVisa');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }


    /**
     * cancel Visa and update the status
     * @param int $id
     * @return Renderable
     */
    public function cancelVisa(VisaCancelRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Visa\Entities\PLMSVisa');
        
        return $this->tryCatch(function () use ($request , $id) {
            $visa = $this->service->cancelVisaWithReason($id, $this->service->prepareDataForStorage($request->validated()));
            return response()->json(['status' => true, 'message' => "Visa Cancelled Successfully"]);
        });
    }

    /**
     * Load necessary relationships for a visa.
     *
     * @param mixed $visa The visa entity.
     * @return mixed The visa entity with loaded relationships.
     */
    protected function loadVisaRelationships($visa)
    {
        return $visa->load([
            'pax',
            'passport',
            'loi',
        ]);
    }
}
