<?php

namespace App\Traits;

trait SequenceHandler
{
    public function fixApplicantSequence($applicants){
        $expected_sequence_no = 1;
    
        $applicants->each(function ($applicant) use (&$expected_sequence_no) {
            if ($applicant->sequence_no != $expected_sequence_no) {
                $applicant->sequence_no = $expected_sequence_no;
                $applicant->save();
            }
            $expected_sequence_no++;
        });
    }
}
