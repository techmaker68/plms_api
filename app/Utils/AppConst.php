<?php

namespace App\Utils;

class AppConst
{
    const PAGE_SIZE = 50;

    const FEATURED_PROPERTIES = 6;

    const NO = 0;
    const YES = 1;

    const INACTIVE = 'disable';
    const ACTIVE = 'active';
    const RESERVED = 'reserved';
    const SEMI_RESERVED = 'semi-reserved';

    const ASC= 'asc';
    const DESC= 'desc';

    const UNVERIFIED=0;
    const VERIFIED=1;

    const VERIFICATION_CODE = 6;

    const CONFIRM='confirm';
    const PENDING='pending';
    const EXPIRE='expire';
    const TIMEOUT= 'timeout';
    
    const ONE_MONTH = 2629746;

    // algorithms
    const HASH_ALGO = 'sha256';
    const JWT_ALGO = 'HS256';
}
