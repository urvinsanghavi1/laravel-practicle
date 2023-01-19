<?php 
namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

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
            Config::set('database.connections.tenant.database', $schemaName);
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
            // $command = "migrate --path=/database/migrations/2023_01_19_072520_create_company_profile.php";
            DB::purge('tenant');
            DB::reconnect('tenant');
            $databaseName = Config::get('database.connections.tenant.database');
            Log::info("DATABASE | ". $databaseName);
            DB::connection($databaseName)->statement("CREATE TABLE tv3 (a INT, b INT, c INT)");
            Log::info("Tenant | Create Table For Customer Profile and Store Information.");
         } catch (\Exception $exception) {
            Log::info("Tenant | createCustomerProfile Error | ". $exception->getMessage());
         }
    }
}


?>