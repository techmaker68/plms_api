<?php

namespace Modules\Pax\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use Modules\Pax\Http\Requests\PaxGetRequest;
use Modules\Pax\Transformers\PaxResource;
use Modules\Pax\Http\Requests\PaxStoreRequest;
use Modules\Pax\Contracts\PLMSPaxServiceContract;

class PLMSPaxController extends BaseController
{
    protected PLMSPaxServiceContract $service;

    /**
     * PLMSPaxController constructor.
     *
     * @param PLMSPaxServiceContract $service The service contract for handling paxes.
     */
    public function __construct(PLMSPaxServiceContract $service)
    {
        $this->service = $service;
        
        // Uncomment the following lines to add middleware permissions for specific actions
        $this->middleware('permission:PAXES_GET', ['only' => ['index', 'show']]);
        $this->middleware('permission:PAX_ADD', ['only' => ['store']]);
        $this->middleware('permission:PAX_EDIT', ['only' => ['update']]);
        $this->middleware('permission:PAX_DELETE', ['only' => ['destroy']]);
    }

    /**
     * Get the list of paxes.
     *
     * @param PaxGetRequest $request The request object containing any filters or parameters.
     * @return JsonResponse The JSON response containing the list of paxes.
     */
    public function index(PaxGetRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            // Prepare filters based on the validated request data
            $filters = $this->service->prepareFilters($request->validated());

            // Prepare reelations based on required relations and conditional relations
            $relations = $this->service->prepareRelations($filters);

            // Get the list of paxes based on the filters and include related models
            $paxes = $this->service->{$filters['method']}($filters, $relations);
            
            // Get the type counts for paxes
            $typeCounts = $this->service->typeCounts($filters['company_id'] ?? null);
            
            // Get the status counts for paxes
            $statusCounts = $this->service->statusCounts();

            // Transform the paxes into a resource collection
            $resourceCollection = PaxResource::collection($paxes);

            // Return the resource collection with additional meta data in the response
            return $resourceCollection->additional([
                'meta' => [
                    'type_counts' => $typeCounts,
                    'status_counts' => $statusCounts,
                ],
            ])->response();
        });
    }

    /**
     * Store a newly created pax in storage.
     *
     * @param PaxStoreRequest $request The request object containing the data for the new pax.
     * @return JsonResponse The JSON response containing the newly created pax.
     */
    public function store(PaxStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            // Validate the request data
            $validatedData = $request->validated();
            
            // Calculate the pax ID
            $validatedData['pax_id'] = $this->service->calculatePaxId();
            
            // Store the pax in the database
            $pax = $this->service->store($this->service->prepareDataForStorage($validatedData));

            // Return the newly created pax as a resource
            return (new PaxResource($pax))->response();
        });
    }

    /**
     * Display the specified pax.
     *
     * @param mixed $id The ID of the pax to display.
     * @return JsonResponse The JSON response containing the specified pax.
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Pax\Entities\PLMSPax');

        return $this->tryCatch(function () use ($id) {
            // Get the specified pax with related models
            $pax = $this->service->show($id);

            // Load the necessary relationships
            $pax = $this->loadPaxRelationships($pax);
            
            // Return the specified pax as a resource
            return (new PaxResource($pax))->response();
        });
    }

    /**
     * Update the specified pax in storage.
     *
     * @param PaxStoreRequest $request The request object containing the updated data for the pax.
     * @param mixed $id The ID of the pax to update.
     * @return JsonResponse The JSON response containing the updated pax.
     */
    public function update(PaxStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Pax\Entities\PLMSPax');
        
        return $this->tryCatch(function () use ($request, $id) {
            // Update the specified pax in the database
            $pax = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            
            // Load the necessary relationships
            $pax = $this->loadPaxRelationships($pax);

            // Return the updated pax as a resource
            return (new PaxResource($pax))->response();
        });
    }

    /**
     * Remove the specified pax from storage.
     *
     * @param mixed $id The ID of the pax to remove.
     * @return JsonResponse The JSON response indicating the success of the deletion.
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Pax\Entities\PLMSPax');

        return $this->tryCatch(function () use ($id) {
            // Delete the specified pax from the database
            $this->service->destroy($id); // also remove related files. Pax profile, passport file, visa file, lois file, blood test file
            
            // Return a success message
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }
    
    /**
     * Load necessary relationships for a pax.
     *
     * @param mixed $pax The pax entity.
     * @return mixed The pax entity with loaded relationships.
     */
    protected function loadPaxRelationships($pax)
    {
        return $pax->load([
            'department', 
            'company', 
            'passports.countryOfPassport', 
            'passports.countryOfIssue', 
            'visas', 
            'lois',
            'latestLoi',
            'latestPassport',
            'blood_tests', 
            'country', 
            'countryResidence', 
            'countryCode'
        ]);
    }
}
