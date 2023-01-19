<?php 
namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Constants\CommanConstans as cc;

/**
 * Database Connection and Creation Process.
 */
trait MultiTenantProcess
{

    /**
     * Daynamic Database Creation for multiple company
     * 
     * @return void
     */
    public function databaseCreate($schemaName) : void {
        try {
            $charset = config("database.connections.tenant.charset", 'utf8mb4');
            $collation = config("database.connections.tenant.collation", 'utf8mb4_unicode_ci');
            $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
            $this->setDatabase($schemaName);
            DB::statement($query);
            Log::info("Tenant | Database Created");
        } catch (\Exception $exception) {
            Log::info("Tenant | databaseCreate Error | ". $exception->getMessage());
        }
    }

    /**
     * Store Tenant Details into shared database.
     * 
     * @return void
     */
    public function storeDatabaseDetail($databasDetails) : void {
        try {         
            DB::beginTransaction();
            Tenant::create($databasDetails);
            DB::commit();
            Log::info("Tenant | Store Data Related to Database");
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info("Tenant | storeDatabaseDetail Error | ". $exception->getMessage());
        }
    }

    /**
     * Table Creation with store information of new company profile
     * 
     * @return void
     */
    public function createCustomerProfile() : void {
         try {
            //swich database for new connection
            $this->databaseSwitch(cc::TENANT_CONNECTION_NAME);
            
            //Set Connection in Session.
            session(['connection' => cc::TENANT_CONNECTION_NAME]);
            $command = "migrate --path=/database/migrations/2023_01_19_072520_create_company_profile.php";
            Artisan::call($command);


            

            Log::info("Tenant | Create Table For Customer Profile and Store Information.");
         } catch (\Exception $exception) {
            Log::info("Tenant | createCustomerProfile Error | ". $exception->getMessage());
         }
    }

    /**
     * Switch new database connection
     * 
     * @return connection
     */
    public function databaseSwitch($connetionName) {
        DB::purge($connetionName);
        $connetion = DB::connection($connetionName);
        return $connetion;
    }

    /**
     * Set new database name
     * 
     * @return void
     */
    public function setDatabase($databaseName) : void {
        Config::set('database.connections.tenant.database', $databaseName);
    }

    /**
     * get new database name
     * 
     * @return database name
     */
    public function getDatabase() {
        return Config::get('database.connections.tenant.database');
    }
}


?>