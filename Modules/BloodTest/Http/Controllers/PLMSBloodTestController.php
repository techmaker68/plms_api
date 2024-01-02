<?php

namespace Modules\BloodTest\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use Modules\BloodTest\Transformers\BloodTestResource;
use Modules\BloodTest\Http\Requests\BloodTestGetRequest;
use Modules\BloodTest\Contracts\BloodTestServiceContract;
use Modules\BloodTest\Http\Requests\BloodTestStoreRequest;

class PLMSBloodTestController extends BaseController
{
    protected BloodTestServiceContract $service;

    /**
     * BloodTestController constructor.
     *
     * @param BloodTestServiceContract $service
     */
    public function __construct(BloodTestServiceContract $service)
    {
        $this->service = $service;
                
        $this->middleware('permission:BLOOD_TESTS_GET', ['only' => ['index', 'show','store']]);
        $this->middleware('permission:BLOOD_TEST_EDIT', ['only' => ['update']]);
        $this->middleware('permission:BLOOD_TEST_DELETE', ['only' => ['destroy']]);

    }

    public function index(BloodTestGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $method = isset($filters['month']) && $filters['month'] ? 'all' : $filters['method'];
            $bloodTests = $this->service->{$method}($filters); // Calling "all" or "index" method, Depending on the method in filters.
            $current_week_batch = $this->service->getCurrentWeekData();
            $resourceCollection = BloodTestResource::collection($bloodTests);
            return $resourceCollection->additional([
                'meta' => [
                    'current_week_batch'=> $current_week_batch,
                ],
            ])->response();
        });
    }

    public function store(BloodTestStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $bloodTest = $this->service->store($this->service->prepareDataForStorage($validatedData));
            $bloodTest->processBatch($bloodTest->batch_no);
            return (new BloodTestResource($bloodTest))->response();
        });
    }

    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\BloodTest');

        return $this->tryCatch(function () use ($id) {
            $bloodTest = $this->service->show($id);
            return (new BloodTestResource($bloodTest))->response();
        });
    }

    public function update(BloodTestStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\BloodTest');
        
        return $this->tryCatch(function () use ($request, $id) {
            $bloodTest = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            $bloodTest->processBatch($bloodTest->batch_no);
            return (new BloodTestResource($bloodTest))->response();
        });
    }

    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\BloodTest');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }
}
