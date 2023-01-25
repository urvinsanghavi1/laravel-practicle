<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\MultiTenantProcess;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;

class CreateTenant implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MultiTenantProcess;

    private $schemaName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($companyName)
    {
        $this->schemaName = $companyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("Database creating for register company | Tenant - 2 ");
        $this->databaseCreate($this->schemaName);
        Log::info("Database created for register company | Tenant - 2 ");
    }
}
