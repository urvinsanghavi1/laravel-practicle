<?php

namespace App\Jobs;

use App\Constants\CommanConstans;
use App\Constants\MainTableConstans;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\MultiTenantProcess;
use Illuminate\Bus\Batchable;

class SendEmailToCustomer implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MultiTenantProcess;

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
        Log::info("Email Sending To Company Email | Tenant - 6");
        // dd($this->companyName);

        $companyInformation = DB::connection(CommanConstans::TENANT_CONNECTION_NAME)->table(MainTableConstans::COMPANY_PROFILE_TABLE)
                              ->where(MainTableConstans::COMPANY_PROFILE_COMPANY_NAME, $this->companyName)
                              ->first([MainTableConstans::COMPANY_PROFILE_EMAIL, MainTableConstans::COMPANY_PROFILE_PASSWORD, MainTableConstans::COMPANY_PROFILE_COMPANY_NAME]);
        $companyInformation = (array)$companyInformation;
        $subDomain = trim(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace('_', '-', $this->companyName)));
        $companyInformation[mainTableConstans::COMPANY_PROFILE_PASSWORD] = $this->encrypt_decrypt("decrypt", $companyInformation[mainTableConstans::COMPANY_PROFILE_PASSWORD]);
        $companyInformation[MainTableConstans::TENANT_TABLE_DOMAIN_NAME] = $subDomain;
        \Mail::to($companyInformation[MainTableConstans::COMPANY_PROFILE_EMAIL])->send(new \App\Mail\sendCustomerEmail($companyInformation));
        Log::info("Email Sent | Tenant - 6");
    }
}
