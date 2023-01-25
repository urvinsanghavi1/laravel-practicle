<?php

namespace App\Listeners;

use App\Events\TenantProcess;
use App\Jobs\CreateTenant;
use App\Jobs\CreateTenantTable;
use App\Jobs\RegisterCompany;
use App\Jobs\SendEmailToCustomer;
use App\Jobs\StoreTenant;
use App\Jobs\StoreTenantTableInformation;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CreateCompanyProfile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TenantProcess  $event
     * @return void
     */
    public function handle(TenantProcess $event)
    {
        Log::info("------------- Event Listener ------------");
        Bus::batch([
            new RegisterCompany($event->companyName),
            new CreateTenant($event->companyName),
            new StoreTenant($event->companyName),
            new CreateTenantTable(),
            new StoreTenantTableInformation($event->data, $event->companyName),
            new SendEmailToCustomer($event->companyName),
        ])->then(function (Batch $batch) {
            Log::info("All Job Completed Successfully.");
        })->catch(function (Batch $batch, Exception $exception) {
            Log::error("Error Batch | Batch ID - ".$batch->id." | Error -> ".$exception->getMessage());
        })->finally(function (Batch $batch) {
            Log::info("The batch has finished executing...");
        })->dispatch();
    }
}
