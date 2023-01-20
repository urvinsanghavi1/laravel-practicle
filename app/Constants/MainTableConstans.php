<?php 

namespace App\Constants;

class MainTableConstans 
{

    const HOSTNAME_DEFAULT_VALUE = '127.0.0.1';
    const PORT_DEFAULT_VALUE = 3306;
    const DEFAULT_STATUE_VALUE = 1;

    const USER_TABLE_NAME = 'users';
    const USER_TABLE_ID = 'id';
    const USER_TABLE_USER_NAME = 'name';
    const USER_TABLE_EMAIL = 'email';
    const USER_TABLE_PASSWORD = 'password';
    const USER_TABLE_CREATED_AT = 'created_at';
    const USER_TABLE_UPDATED_AT = 'updated_at';
    const USER_TABLE_DELETED_AT = 'deleted_at';

    const COMPANY_TABLE_NAME = 'companies';
    const COMPANY_TABLE_ID = 'id';
    const COMPANY_TABLE_COMPANY_NAME = 'name';
    const COMPANY_TABLE_STATUS = 'status';
    const COMPANY_TABLE_CREATED_AT = 'created_at';
    const COMPANY_TABLE_UPDATED_AT = 'updated_at';
    const COMPANY_TABLE_DELETED_AT = 'deleted_at';

    const TENANT_TABLE_NAME = 'tenants';
    const TENANT_TABLE_ID = 'id';
    const TENANT_TABLE_COMPANY_ID = 'company_id';
    const TENANT_TABLE_HOSTNAME = 'hostname';
    const TENANT_TABLE_PORT = 'port';
    const TENANT_TABLE_DBNAME = 'dbname';
    const TENANT_TABLE_DBUSERNAME = 'dbusername';    
    const TENANT_TABLE_DBPASSWORD = 'dbpassword';
    const TENANT_TABLE_DOMAIN_NAME = 'domain';
    const TENANT_TABLE_CREATED_AT = 'created_at';
    const TENANT_TABLE_UPDATED_AT = 'updated_at';
    const TENANT_TABLE_DELETED_AT = 'deleted_at';

    const COMPANY_PROFILE_TABLE = "company_profile";
    const COMPANY_PROFILE_ID = "id";
    const COMPANY_PROFILE_COMPANY_NAME = 'name';
    const COMPANY_PROFILE_EMAIL = "email";
    const COMPANY_PROFILE_PASSWORD = "password";
    const COMPANY_PROFILE_WEBSITE = "website";
    const COMPANY_PROFILE_LICENSE_NUMBER = "license_number";
    const COMPANY_PROFILE_ADDRESS = "address";
    const COMPANY_PROFILE_COUNTRY = "country";
    const COMPANY_PROFILE_STATE = "state";
    const COMPANY_PROFILE_CITY = "city";
    const COMPANY_PROFILE_CREATED_AT = 'created_at';
    const COMPANY_PROFILE_UPDATED_AT = 'updated_at';
}

?>