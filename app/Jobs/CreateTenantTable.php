<?php

namespace App\Jobs;

use App\Constants\CommanConstans;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTenantTable implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        Log::info("Creating Table in Tenant | Tenant - 4 ");
        
        DB::purge(CommanConstans::TENANT_CONNECTION_NAME);
        //Set Connection in Session.
        session(['connection' => CommanConstans::TENANT_CONNECTION_NAME]);
        //Create table company profile
        $command = "migrate --database=tenant --path=/database/migrations/2023_01_19_072520_create_company_profile.php";
        Artisan::call($command);

        Log::info("Created Table in Tenant | Tenant - 4 ");

    }
}
