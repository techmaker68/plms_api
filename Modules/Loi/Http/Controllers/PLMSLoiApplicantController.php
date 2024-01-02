<?php

namespace Modules\Loi\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Modules\Loi\Contracts\PLMSLoiServiceContract;
use Modules\Loi\Http\Requests\DeletePaymentLetterCopyRequest;
use Modules\Loi\Http\Requests\LoiApplicantGetRequest;
use Modules\Loi\Http\Requests\LoiApplicantStoreRequest;
use Modules\Loi\Http\Requests\RemoveLoiApplicantRequest;
use Modules\Loi\Http\Requests\SaveLoiApplicantsBulkRequest;
use Modules\Loi\Http\Requests\SendLoiToApplicantsRequest;
use Modules\Loi\Http\Requests\UpdateMultipleLoiApplicantsRequest;
use Modules\Loi\Transformers\LoiApplicantResource;

class PLMSLoiApplicantController extends BaseController
{
    protected PLMSLoiServiceContract $service;
    protected $prefix;

    /**
     * PLMSLoiController constructor.
     *
     * @param PLMSLoiServiceContract $service
     */
    public function __construct(PLMSLoiServiceContract $service)
    {
        $this->service = $service;
        $this->prefix = 'loiApplicant';

        $this->middleware('permission:LOI_MANAGEMENT_GET');
        $this->middleware('permission:BLOOD_TEST_APPLICANT_ADD', ['only' => ['store']]);

    }

    public function index(LoiApplicantGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $relations = $this->service->prepareApplicantsPaxRelations($filters);
            $loiApplicants = $this->service->{$this->prefix . '' . $filters['method']}($filters, $relations);
            return LoiApplicantResource::collection($loiApplicants)->response();
        });
    }

    public function store(LoiApplicantStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $validatedData['sequence_no'] = $this->service->getMaxSequenceNo();
            $loiApplicant = $this->service->loiApplicantStore($validatedData);

            return (new LoiApplicantResource($loiApplicant))->response();
        });
    }

    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoiApplicant');

        return $this->tryCatch(function () use ($id) {
            $loiApplicants = $this->service->loiApplicantShow($id);
            return response()->json([
                'current_loi_applicant' => new LoiApplicantResource($loiApplicants['current']),
                'previous_loi_applicant' =>  $loiApplicants['previous'] ? new LoiApplicantResource($loiApplicants['previous']) : null
            ]);
        });
    }

    public function update(LoiApplicantStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoiApplicant');

        return $this->tryCatch(function () use ($request, $id) {
            $validatedData = $request->validated();
            $loiApplicant = $this->service->loiApplicantUpdate($id, $validatedData);
            return (new LoiApplicantResource($loiApplicant))->response();
        });
    }

    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoiApplicant');

        return $this->tryCatch(function () use ($id) {
            $this->service->loiApplicantDestroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    public function saveLoiApplicantsBulk(SaveLoiApplicantsBulkRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $bulkLoiApplicants = $this->service->saveLoiApplicantsBulk($filters);

            return LoiApplicantResource::collection($bulkLoiApplicants)->response();
        });
    }

    public function updateMultipleLoiApplicants(UpdateMultipleLoiApplicantsRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $requestData = $request->validated();
            $result = $this->service->updateMultipleApplicants($requestData);

            return response()->json(['message' => $result['message']], $result['status'] ? 200 : 404);
        });
    }

    public function removeLoiApplicant(RemoveLoiApplicantRequest $request)
    {
        return $this->tryCatch(function () use ($request) {

            $this->service->removeLoiApplicants($request->validated());

            return response()->json([
                'status' => true,
                'message' => "Applicants removed",
            ]);
        });
    }

    public function deletePaymentLetterCopy(DeletePaymentLetterCopyRequest $request, $id)
    {
        $this->validateId($id, 'Modules\Loi\Entities\PLMSLoiApplicant');
        return $this->tryCatch(function () use ($request, $id) {
            $index = $request->index;
            $response =  $this->service->deletePaymentLetterCopy($id, $index);

            return response()->json($response);
        });
    }

    public function sendLoiToApplicants(SendLoiToApplicantsRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $data = $this->service->prepareFilters($request->validated());
            $responseMessage = $this->service->sendLoiToApplicants($data, $request->attachment);

            return response()->json(['message' => $responseMessage], 200);
        });
    }

    public function generateDoc(LoiApplicantGetRequest $request)
    {
        $filters = $this->service->prepareFilters($request->validated());
        $data = $this->service->getLoiDetails($filters['batch_no']);
        return  $this->service->generateDoc($data);
    }
    public function generatePDF(LoiApplicantGetRequest $request)
    {
        $filters = $this->service->prepareFilters($request->validated());
        $data = $this->service->getLoiDetails($filters['batch_no']);
        return  $this->service->generatePDF($data);
    }
}
