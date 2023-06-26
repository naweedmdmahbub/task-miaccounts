<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now()->subDays(30);
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $single_transaction = [
                'date' => $startDate->copy()->addDays(rand(0, 29))->format('Y-m-d'),
                'debit' => mt_rand(50,100),
                'credit' => mt_rand(30,50),
                'account_head_id' => rand(1, 3),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $data[] = $single_transaction;
        }
        DB::table('transactions')->insert($data);
    }
}
