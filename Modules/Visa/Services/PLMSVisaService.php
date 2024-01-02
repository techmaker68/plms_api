<?php

namespace Modules\Visa\Services;

use App\Services\BaseService;
use Modules\Visa\Contracts\PLMSVisaServiceContract;
use Modules\Visa\Contracts\PLMSVisaRepositoryContract;

class PLMSVisaService extends BaseService implements PLMSVisaServiceContract
{
  /**
     * PLMSVisaService constructor.
     *
     * @param PLMSVisaRepositoryContract $repository
     */
    public function __construct(PLMSVisaRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    public function typeCounts($companyId): array
    {
        return $this->repository->typeCounts($companyId);
    }

        /**
     * Prepare Relations to be loaded
     *
     * @return array
     */
    public function prepareRelations(array &$filters): array
    {
        $baseRelations = ['pax.company', 'pax.department','passport'];

        $conditionalRelationsMap = [
            'latest_passport' => 'latestPassport',
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

    public function cancelVisaWithReason($id ,$data)
    {
        $visa = $this->repository->show($id);
        return $this->repository->updateVisaCancelReason($visa , $data);
    }
}
