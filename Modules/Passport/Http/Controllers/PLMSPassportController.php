<?php

namespace Modules\Passport\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Passport\Contracts\PLMSPassportServiceContract;
use Modules\Passport\Http\Requests\PassportGetRequest;
use Modules\Passport\Http\Requests\PassportStoreRequest;
use Modules\Passport\Transformers\PassportResource;

class PLMSPassportController extends BaseController
{
    protected PLMSPassportServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(PLMSPassportServiceContract $service)
    {
        $this->service = $service;

        $this->middleware('permission:PASSPORTS_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:PASSPORT_ADD', ['only' => ['store']]);
        $this->middleware('permission:PASSPORT_EDIT', ['only' => ['update']]);
        $this->middleware('permission:PASSPORT_DELETE', ['only' => ['destroy']]);

    }
    public function index(PassportGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->all());
            $passports = $this->service->{$filters['method']}($filters, ['pax', 'pax.company', 'pax.department', 'countryOfPassport']);
            $statusCounts = $this->service->statusCounts($filters['company_id'] ?? null);
            $resourceCollection = PassportResource::collection($passports);
            return $resourceCollection->additional([
                'meta' => [
                    'passport_status_counts' => $statusCounts,
                ],
            ])->response();
        });
    }

    public function store(PassportStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $passport = $this->service->store($this->service->prepareDataForStorage($validatedData));
            return (new PassportResource($passport))->response();
        });
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Passport\Entities\PLMSPassport');
        return $this->tryCatch(function () use ($id) {
            $passport = $this->service->show($id);
            $passport = $this->loadPassportRelationships($passport);
            return (new PassportResource($passport))->response();
        });
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(PassportStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Passport\Entities\PLMSPassport');

        return $this->tryCatch(function () use ($request, $id) {
            $passport = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            $passport = $this->loadPassportRelationships($passport);
            return (new PassportResource($passport))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Passport\Entities\PLMSPassport');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    /**
     * Load necessary relationships for a passport.
     *
     * @param mixed $passport The passport entity.
     * @return mixed The passport entity with loaded relationships.
     */
    protected function loadPassportRelationships($passport)
    {
        return $passport->load([
            'pax', 
            'pax.company', 
            'pax.department', 
            'countryOfPassport', 
            'countryOfIssue'
        ]);
    }
}
