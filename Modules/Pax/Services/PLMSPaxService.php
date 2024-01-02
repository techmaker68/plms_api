<?php

namespace Modules\Pax\Services;

use App\Services\BaseService;
use Illuminate\Support\Collection;
use Modules\Pax\Contracts\PLMSPaxRepositoryContract;
use Modules\Pax\Contracts\PLMSPaxServiceContract;

class PLMSPaxService extends BaseService implements PLMSPaxServiceContract
{
    /**
     * PLMSPaxService constructor.
     *
     * @param PLMSPaxRepositoryContract $repository
     */
    public function __construct(PLMSPaxRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get type counts.
     *
     * @param int|null $companyId
     * @return Collection
     */
    public function typeCounts($companyId): Collection
    {
        return $this->repository->typeCounts($companyId);
    }

    /**
     * Get status counts.
     *
     * @return array
     */
    public function statusCounts(): array
    {
        return $this->repository->statusCounts();
    }

    /**
     * Calculate and return a new Pax ID.
     *
     * @return int
     */
    public function calculatePaxId(): int
    {
        $maxId = $this->repository->getMaxPaxId();
        return intval(str_pad($maxId + 1, 6, '0', STR_PAD_LEFT));
    }

    /**
     * Prepare Relations to be loaded
     *
     * @return array
     */
    public function prepareRelations(array &$filters): array
    {
        $baseRelations = ['company', 'department', 'country','countryCode'];

        $conditionalRelationsMap = [
            'latest_loi' => 'latestLoi',
            'latest_visa' => 'latestVisa',
            'latest_passport' => 'latestPassport',
            'latest_blood_test' => 'latestBloodTest',
        ];

        $conditionalRelationKeys = array_intersect_key($filters, $conditionalRelationsMap);
    
        $filters = array_diff_key($filters, $conditionalRelationsMap);
    
        $conditionalRelations = array_filter($conditionalRelationKeys);

        $relations = array_merge(
            $baseRelations,
            array_values(array_intersect_key($conditionalRelationsMap, $conditionalRelations))
        );
    
        return $relations;
    }       
}
