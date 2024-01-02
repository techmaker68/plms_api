<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CountrySeeder::class);
        // $this->command->info('Countries Table Seeded Successfully!');

        $this->call(CompanySeeder::class);
        $this->command->info('Companies Table Seeded Successfully!');

        // $this->call(PaxSeeder::class);
        // $this->command->info('Pax Table Seeded Successfully!');

        // $this->call(LoiSeeder::class);
        // $this->command->info('loi Table Seeded Successfully!');

        // $this->call(LoiApplicantSeeder::class);
        // $this->command->info('loi app Table Seeded Successfully!');

        // $this->call(PassportSeeder::class);
        // $this->command->info('passport Table Seeded Successfully!');

        // $this->call(VisaSeeder::class);
        // $this->command->info('visa Table Seeded Successfully!');

        // $this->call(BloodTestSeeder::class);
        // $this->command->info('blood Table Seeded Successfully!');

        // $this->call(BloodTestApplicantSeeder::class);
        // $this->command->info('blood applicant Table Seeded Successfully!');
        // User::factory(10)->create();
        
    }
}
