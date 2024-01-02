<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PLMSPax;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sql = 
        "INSERT INTO `plms_companies` (`name`,`type`,`status`,`short_name`,`industry`,`email`,`phone`,`website`,`city`,`country_id`,`poc_name`,`poc_email_or_username`,`poc_mobile`,`address_1`) VALUES
('Basra Oil Company BOC', 'Partner', 1, 'BOC', 'Information Technology', 'boc@gmail.com', '9675844634', 'www.google.com', 'Dubai', '234', 'BOC', 'boc@gmail.com', '7685634645', 'address address'),
('High Tech ED', 'Services Provider', 1, 'HTET', 'Information Technology', 'HTET@gmail.com', '5462374545', 'www.google.com', 'Dubai', '234', 'HTET', 'HTET@gmail.com', '967588435', 'address address'),
('Antonoil Services DMCC', 'Operator', 1, NULL, NULL, NULL, NULL, NULL, 'Dubai', '234', 'AOS', 'info@antonoil.com', NULL, 'JLT')";

     DB::statement($sql);
    }
}
