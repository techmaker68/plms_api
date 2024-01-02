<?php

function fixApplicantSequence($applicants){
    $expectedSequenceNo = 1;  // initialize expected sequence number to 1

    foreach ($applicants as $applicant) {
        if ($applicant->sequence_no != $expectedSequenceNo) {
            $applicant->sequence_no = $expectedSequenceNo;  // fix the sequence number
            $applicant->save();  // save the updated record
        }
        $expectedSequenceNo++;  // increment expected sequence number for next iteration
    }
}

/**
 * Helping function
 */
function daysPassedAndRemaining($expirationDate){

    $now = new DateTime();
    $expiration = DateTime::createFromFormat('Y-m-d', $expirationDate);
    $interval = $now->diff($expiration);

    $daysPassed = $interval->invert ? 0 : $interval->days;
    $daysRemaining = $interval->invert ? -$interval->days : $interval->days;

    return [
        'passed' => $daysPassed,
        'remaining' => $daysRemaining
    ];
}


function convertPaxId($value){
    return $value ? str_pad($value, 6, '0', STR_PAD_LEFT) : null;
}


function convertFileToArray($value){

    if (is_null($value) || trim($value) === '') {
        return [];
    }

    return explode(',', $value);
}