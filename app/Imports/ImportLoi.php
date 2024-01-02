<?php
// ***********************************
// @author Syed, Umair, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Imports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Modules\Pax\Entities\PLMSPax;

class ImportLoi implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $rows->shift();
        foreach ($rows as $row) {
            $status = null;
            $type = strtolower($row[5]);
            if ($type === 'approved') {
                $status = '1';
            } elseif ($type === 'rejected') {
                $status = '2';
            } elseif ($type === 'cancelled') {
                $status = '3';
            } elseif ($type === 'giveup') {
                $status = '4';
            }
            $pax = PLMSPax::where('badge_no',  str_replace(' ', '', $row[0]))->first();
            if (is_numeric($row[2])) {
                $payment_date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays((int)$row[2] - 2)->format('Y-m-d');
            } else {
                $payment_date = Carbon::parse(str_replace(' ', '', $row[2]))->format('Y-m-d');
            }
            if ($pax) {
                PLMSLoiApplicant::create([
                    'batch_no' =>  str_replace(' ', '', $row[1]),
                    'pax_id' => $pax ? $pax->pax_id : null,
                    'loi_payment_date' =>  $payment_date,
                    'loi_payment_receipt_no' =>  str_replace(' ', '', $row[3]),
                    'deposit_amount' =>  str_replace(' ', '', $row[4]),
                    'status' => $status,
                ]);
            }
        }
    }
}
