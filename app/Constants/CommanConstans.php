<?php 

namespace App\Constants;

/** Define Comman Constant */
class CommanConstans
{
    const COUNTRY_API_URL = "https://countriesnow.space/api/v0.1/countries/positions";
    const STATE_API_URL = "https://countriesnow.space/api/v0.1/countries/states";
    const CITY_API_URL = "https://countriesnow.space/api/v0.1/countries/state/cities";

    const TENANT_CONNECTION_NAME = "tenant";
    const DEFAULT_CONNECTION_NAME = "mysql";

    const SECRET_KEY = "KAJDSLKDJS23847238AJIAOIAD";
    const SECRET_IV = "LAKDJSJKJKNADJSa87asdaosdad";
    const ENCRYPT_METHOD = "AES-256-CBC";

}

?>