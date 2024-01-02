<?php

namespace App\Contracts;

interface ExcelImportRepositoryContract
{
    public function findByBadgeNo($badgeNo);
    public function findByPassportNo($passportNo);
    public function findByVisaNo($visaNo);
    public function findByBatchNo($batchNo);
    public function findByBatchAndPax($batch, $pax);
    public function findBypassportNoAndBatchNo($passport, $batch);
}
