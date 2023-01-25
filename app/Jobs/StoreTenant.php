<?php

namespace App\Jobs;

use App\Constants\MainTableConstans as mainTableConstans;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\MultiTenantProcess;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;

class StoreTenant implements ShouldQueue
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
        Log::info("Storing Database Information into Tenant | Tenant - 3 ");
        $password = config('database.connections.tenant.password');
        $subDomain = trim(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace('_', '-', $this->companyName)));
        $companyinfo = Company::where(mainTableConstans::COMPANY_TABLE_COMPANY_NAME, $this->companyName)->first(['id']);

        $databaseDetails = [
            mainTableConstans::TENANT_TABLE_HOSTNAME => mainTableConstans::HOSTNAME_DEFAULT_VALUE,
            mainTableConstans::TENANT_TABLE_PORT => mainTableConstans::PORT_DEFAULT_VALUE,
            mainTableConstans::TENANT_TABLE_DBNAME => $this->companyName,
            mainTableConstans::TENANT_TABLE_DBUSERNAME => config('database.connections.tenant.username'),
            mainTableConstans::TENANT_TABLE_DBPASSWORD => $password != "" ? $this->encrypt_decrypt("encrypt", $password) : "",
            mainTableConstans::TENANT_TABLE_COMPANY_ID => $companyinfo->id,
            mainTableConstans::TENANT_TABLE_DOMAIN_NAME => $subDomain
        ];
        $this->storeDatabaseDetail($databaseDetails);
        Log::info("Stored Database Information into Tenant | Tenant - 3 ");
    }
}
