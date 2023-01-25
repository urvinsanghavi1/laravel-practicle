<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Constants\CommanConstans as commanConstans;
use APP\Constants\MainTableConstans as mainTableConstans;

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
    public function databaseCreate($schemaName): void
    {
        try {
            $charset = config("database.connections.tenant.charset", 'utf8mb4');
            $collation = config("database.connections.tenant.collation", 'utf8mb4_unicode_ci');
            $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
            $this->setDatabase($schemaName);
            DB::statement($query);
            Log::info("Tenant | Database Created");
        } catch (\Exception $exception) {
            Log::info("Tenant | databaseCreate Error | " . $exception->getMessage());
        }
    }

    /**
     * Store Tenant Details into shared database.
     * 
     * @return void
     */
    public function storeDatabaseDetail($databasDetails): void
    {
        try {
            DB::beginTransaction();
            Tenant::create($databasDetails);
            DB::commit();
            Log::info("Tenant | Store Data Related to Database");
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info("Tenant | storeDatabaseDetail Error | " . $exception->getMessage());
        }
    }

    /**
     * Table Creation with store information of new company profile
     * 
     * @return void
     */
    public function createCustomerProfile($data): void
    {
        try {
            $emailData = [
                mainTableConstans::TENANT_TABLE_DOMAIN_NAME => $data[mainTableConstans::TENANT_TABLE_DOMAIN_NAME],
                mainTableConstans::COMPANY_PROFILE_COMPANY_NAME => $data[mainTableConstans::COMPANY_PROFILE_COMPANY_NAME],
                mainTableConstans::COMPANY_PROFILE_PASSWORD => $this->encrypt_decrypt("decrypt", $data[mainTableConstans::COMPANY_PROFILE_PASSWORD]),
                mainTableConstans::COMPANY_PROFILE_EMAIL => $data[mainTableConstans::COMPANY_PROFILE_EMAIL]
            ];

            DB::purge(commanConstans::TENANT_CONNECTION_NAME);

            //Set Connection in Session.
            session(['connection' => commanConstans::TENANT_CONNECTION_NAME]);

            //create table company profile
            $command = "migrate --database=tenant --path=/database/migrations/2023_01_19_072520_create_company_profile.php";
            Artisan::call($command);

            Log::info("Tenant | Table Created for new company database");

            unset($data[mainTableConstans::TENANT_TABLE_DOMAIN_NAME]);
            //store data in table
            DB::beginTransaction();
            DB::connection(commanConstans::TENANT_CONNECTION_NAME)->table(mainTableConstans::COMPANY_PROFILE_TABLE)->insert($data);
            DB::commit();
            Log::info("Tenant | Customer Profile Store Information.");

            //send email to company user
            $this->sendEmail($emailData);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info("Tenant | createCustomerProfile Error | " . $exception->getMessage());
        }
    }

    /**
     * Set new database name
     * 
     * @return void
     */
    public function setDatabase($databaseName): void
    {
        Config::set('database.connections.tenant.database', $databaseName);
        Log::info("Switch Datbase Connection | " . $this->getDatabase());
    }

    /**
     * get new database name
     * 
     * @return database name
     */
    public function getDatabase()
    {
        return Config::get('database.connections.tenant.database');
    }


    /**
     * Encrypt Decrypt String base on action
     * 
     * @return encrypt or decrypt format of string
     */
    public function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = commanConstans::ENCRYPT_METHOD;
        $secret_key = commanConstans::SECRET_KEY;
        $secret_iv = commanConstans::SECRET_IV;
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    /** 
     * Send Email
     * 
     * @return void
     */
    public function sendEmail($emailData): void
    {
        try {
            \Mail::to($emailData['email'])->send(new \App\Mail\sendCustomerEmail($emailData));
            Log::info("Email Sent");
        } catch (\Exception $exception) {
            Log::error("MAIL ERROR | " . $exception->getMessage());
        }
    }
}
