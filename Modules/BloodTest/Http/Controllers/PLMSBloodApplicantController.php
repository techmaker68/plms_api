<?php

namespace Modules\BloodTest\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Modules\BloodTest\Contracts\BloodApplicantServiceContract;
use Modules\BloodTest\Http\Requests\ApplicantNotesRequest;
use Modules\BloodTest\Http\Requests\BloodApplicantBulkUpdateRequest;
use Modules\BloodTest\Http\Requests\BloodApplicantGetRequest;
use Modules\BloodTest\Http\Requests\RemoveBloodApplicantRequest;
use Modules\BloodTest\Http\Requests\BloodApplicantStoreRequest;
use Modules\BloodTest\Http\Requests\PenaltyPostRequest;
use Modules\BloodTest\Http\Requests\RenewAppointmentRequest;
use Modules\BloodTest\Http\Requests\RescheduleApplicantRequest;
use Modules\BloodTest\Transformers\BloodApplicantPaxResource;
use Modules\BloodTest\Transformers\BloodApplicantResource;


class PLMSBloodApplicantController extends BaseController
{

    protected BloodApplicantServiceContract $service;

    /**
     * BloodTestController constructor.
     *
     * @param BloodApplicantServiceContract $service
     */
    public function __construct(BloodApplicantServiceContract $service)
    {
        $this->service = $service;

        $this->middleware('permission:BLOOD_TESTS_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:BLOOD_TEST_APPLICANT_ADD', ['only' => ['store']]);
        $this->middleware('permission:BLOOD_TEST_APPLICANT_EDIT', ['only' => ['update']]);
        $this->middleware('permission:BLOOD_TEST_APPLICANT_DELETE', ['only' => ['destroy']]);
    }

    public function index(BloodApplicantGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $method = isset($filters['month']) && $filters['month'] ? 'all' : $filters['method'];
            $bloodApplicants = $this->service->{$method}($filters, ['blood_test', 'pax.latestPassport', 'pax.company', 'pax.country', 'pax.department', 'pax.countryCode', 'pax.countryResidence']); // Calling "all" or "index" method, Depending on the method in filters.
            $submission_information = $this->service->getSubmissionInformation($filters['batch_no']);
            $resourceCollection = BloodApplicantResource::collection($bloodApplicants);
            return $resourceCollection->additional([
                'meta' => [
                    'submission_information' => $submission_information,
                ],
            ])->response();
        });
    }

    public function store(BloodApplicantStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $bloodTestApplicant = $this->service->store($this->service->prepareDataForStorage($validatedData));
            $bloodTestApplicant = $this->loadApplicantsRelationships($bloodTestApplicant);

            return (new BloodApplicantResource($bloodTestApplicant))->response();
        });
    }

    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\PLMSBloodApplicant');

        return $this->tryCatch(function () use ($id) {
            $bloodTestApplicant = $this->service->show($id);
            $blood_test_history = $this->service->getBloodTestHistory($bloodTestApplicant);
            $bloodTestApplicant = $this->loadApplicantsRelationships($bloodTestApplicant);
            $resourceCollection = (new BloodApplicantResource($bloodTestApplicant));
            return $resourceCollection->additional([
                'blood_test_history' => $blood_test_history['blood_test_history'],
                'penalty' => $blood_test_history['penalty'],
            ])->response();
        });
    }

    protected function loadApplicantsRelationships($applicant)
    {
        return $applicant->load([
            'pax',
            'pax.department',
            'pax.company',
            'pax.latestPassport',
            'blood_test',
            'countryCode',
        ]);
    }

    public function update(BloodApplicantStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\PLMSBloodApplicant');

        return $this->tryCatch(function () use ($request, $id) {
            $applicant = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            return (new BloodApplicantResource($applicant))->response();
        });
    }
    public function updateApplicantsInBulk(BloodApplicantBulkUpdateRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $data = $this->service->prepareFilters($request->validated());
            $applicants = $this->service->updateApplicantsInBulk($data);
            return BloodApplicantResource::collection($applicants)->response();
        });
    }

    public function rescheduleApplicant(RescheduleApplicantRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $data = $this->service->prepareFilters($request->validated());
            $applicants = $this->service->rescheduleApplicant($data);
            return response()->json([
                'data' => BloodApplicantResource::collection($applicants),
                'message' => 'Applicants rescheduled successfully',
            ]);
        });
    }
    public function renewAppointment(RenewAppointmentRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\PLMSBloodApplicant');
        return $this->tryCatch(function () use ($request, $id) {
            $data = $this->service->renewAppointment($id, $this->service->prepareDataForStorage($request->validated()));
            return response()->json([
                'data' => $data,
                'message' => $data['message'],
            ]);
        });
    }

    public function addPenalty(PenaltyPostRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\PLMSBloodApplicant');
        return $this->tryCatch(function () use ($request, $id) {
            $message = $this->service->addPenalty($id, $request->validated());
            return response()->json($message);
        });
    }
    public function sendApplicantsNote(ApplicantNotesRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $data = $this->service->prepareFilters($request->validated());
            $message = $this->service->sendApplicantsNote($data);
            return response()->json(['message' => $message]);
        });
    }
    public function getPaxDetail($id): JsonResponse
    {
        return $this->tryCatch(function () use ($id) {
            $data = $this->service->getPaxDetail($id);
            $resourceCollection = new  BloodApplicantPaxResource($data['pax']);
            return $resourceCollection->additional(['data' => $data['result']])->response();
        });
    }


    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\BloodTest\Entities\PLMSBloodApplicant');

        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    public function removeBloodApplicant(RemoveBloodApplicantRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $this->service->removeApplicants($request->validated());

            return response()->json([
                'status' => true,
                'message' => "Blood Applicants removed",
            ]);
        });
    }
    public function generateDoc(BloodApplicantGetRequest $request)
    {
        $filters = $this->service->prepareFilters($request->validated());
        $method = isset($filters['month']) && $filters['month'] ? 'all' : $filters['method'];
        $bloodApplicants = $this->service->{$method}($filters, ['blood_test', 'pax.latestPassport', 'pax.company', 'pax.country', 'pax.department', 'pax.countryCode', 'pax.countryResidence']);
        $submission_information = $this->service->getSubmissionInformation($filters['batch_no']);
        return  $this->service->generateDoc($bloodApplicants, $submission_information);
    }
    public function generatePDF(BloodApplicantGetRequest $request)
    {
        $filters = $this->service->prepareFilters($request->validated());
        $method = isset($filters['month']) && $filters['month'] ? 'all' : $filters['method'];
        $bloodApplicants = $this->service->{$method}($filters, ['blood_test', 'pax.latestPassport', 'pax.company', 'pax.country', 'pax.department', 'pax.countryCode', 'pax.countryResidence']);
        $filteredApplicants = $bloodApplicants->filter(function ($applicant) {
            return $applicant->pax !== null;
        });
        $submission_information = $this->service->getSubmissionInformation($filters['batch_no']);
        return  $this->service->generatePDF($filteredApplicants, $submission_information);
    }
}
