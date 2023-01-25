<?php

namespace App\Jobs;

use App\Constants\MainTableConstans as mainTableConstans;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\MultiTenantProcess;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;

class StoreTenantTableInformation implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MultiTenantProcess;

    private $data, $companyName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $companyName)
    {
        $this->data = $data;
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
        Log::info("Storing Profile Information in Tenant Table | Tenant - 5 ");
        $this->data[mainTableConstans::COMPANY_PROFILE_PASSWORD] = $this->encrypt_decrypt("encrypt", trim($this->data[mainTableConstans::COMPANY_PROFILE_PASSWORD]));
        $this->data[mainTableConstans::COMPANY_PROFILE_COMPANY_NAME] = $this->companyName;
        $this->data[mainTableConstans::COMPANY_PROFILE_CREATED_AT] = date('Y-m-d H:i:s');
        $this->data[mainTableConstans::COMPANY_PROFILE_UPDATED_AT] = date('Y-m-d H:i:s');
        $this->createCustomerProfile($this->data);
        Log::info("Stored Profile Information in Tenant Table | Tenant - 5 ");
    }
}
