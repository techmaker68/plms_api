<?php

namespace Modules\Loi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use Modules\Loi\Transformers\LoiResource;
use Illuminate\Validation\ValidationException;
use Modules\Loi\Http\Requests\LOIStoreRequest;
use Modules\Loi\Contracts\PLMSLoiServiceContract;
use Modules\Loi\Http\Requests\DeleteLoiFilesRequest;
use Modules\Loi\Http\Requests\LOIGetRequest;
use Modules\Loi\Http\Requests\ZipFilesGetRequest;

class PLMSLoiController extends BaseController
{
    protected PLMSLoiServiceContract $service;

    /**
     * PLMSLoiController constructor.
     *
     * @param PLMSLoiServiceContract $service
     */
    public function __construct(PLMSLoiServiceContract $service)
    {
        $this->service = $service;

        $this->middleware('permission:LOI_BATCHES_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:LOI_BATCH_ADD', ['only' => ['store']]);
        $this->middleware('permission:LOI_BATCH_EDIT', ['only' => ['update']]);
        $this->middleware('permission:LOI_BATCH_DELETE', ['only' => ['destroy', 'deleteLoiFiles']]);
    }

    public function index(LOIGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $lois = $this->service->{$filters['method']}($filters, ['company.country']);
            return LoiResource::collection($lois)->response();
        });
    }

    public function store(LOIStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $validatedData['batch_no'] = $this->service->calculateBatchNo();
            $defaultData = $this->service->getDefaultDataForLoiStorage();
            $preparedData = array_merge($defaultData, $validatedData);
            $lois = $this->service->store($this->service->prepareDataForStorage($preparedData));

            return (new LoiResource($lois))->response();
        });
    }

    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoi');

        return $this->tryCatch(function () use ($id) {
            $loi = $this->service->show($id);
            $loi = $this->loadLoiRelationships($loi);
            return (new LoiResource($loi))->response();
        });
    }

    public function update(LOIStoreRequest $request, $id): JsonResponse //Pending for test
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoi');

        return $this->tryCatch(function () use ($request, $id) {
            $loi = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            $loi = $this->loadLoiRelationships($loi);
            return (new LoiResource($loi))->response();
        });
    }

    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoi');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroyLoiWithRelations($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    public function renewLoi($batch_no): JsonResponse
    {

        $validator = Validator::make(['batch_no' => $batch_no], [
            'batch_no' => 'required|exists:Modules\Loi\Entities\PLMSLoi,batch_no',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->tryCatch(function () use ($batch_no) {
            $loi = $this->service->renewLOI($batch_no);

            return (new LoiResource($loi))->response();
        });
    }

    public function deleteLoiFiles(DeleteLoiFilesRequest $request, $id)
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoi');

        return $this->tryCatch(function () use ($id, $request) {
            $this->service->deleteLoiFiles($id, $request->validated());
            return response()->json([
                'status' => true,
                'message' => "Files Deleted Successfully",
            ]);
        });
    }

    protected function loadLoiRelationships($loi)
    {
        return $loi->load([
            'company.country',
        ]);
    }

    public function generateZipFile(ZipFilesGetRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $this->service->generateZipFile($request->file, $request->batch_no);
            return response()->json([
                'status' => true,
                'message' => "Zip files sent to the LOI admin emails",
            ]);
        });
    }
}
