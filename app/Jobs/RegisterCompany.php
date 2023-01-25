<?php

namespace App\Jobs;

use App\Constants\MainTableConstans;
use App\Models\Company;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterCompany implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $companyName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        Log::info("Company Register In Master Table | Tenant - 1 ");
        DB::beginTransaction();
        Company::create(
            [
                MainTableConstans::COMPANY_TABLE_COMPANY_NAME => $this->companyName,
                MainTableConstans::COMPANY_TABLE_STATUS => 1
            ]
        );
        DB::commit();
        Log::info("Company Register Successfully | Tenant - 1 ");
    }
}
