<?php 
namespace App\Traits;

use Illuminate\Support\Facades\Http;
use App\Constants\CommanConstans as commanConstans;

/**
 *  Get the Data from Extrnal API
 */
trait ExtrnalApiConnection
{
    /**
     * HTTP call for get the data from extrnal api
     * 
     * @return response from api in json formate
     */
    public function getDataFromApi($url, $method = "GET" ,$parms = [])
    {
        if($method === "GET" && empty($parms)){
            $response = Http::get($url);
        } else {
            $response = Http::post($url, $parms);
        }

        if($response->successful()) {
            return $response->json();
        } else {
            return $response->clientError();
        }

    }

    /** 
     * Get List of Country Details
     * 
     * @return countries name array
     */
    public function getCountry()
    {
        $countries = [];
        $url = commanConstans::COUNTRY_API_URL;
        $countryData = $this->getDataFromApi($url, "GET");
        if(isset($countryData['data'])){
            $countries = array_map(function ($ar) {return $ar['name'];}, $countryData['data']);
        }
        return $countries;
    }

    /**
     * Get List of States Details
     * 
     * @return states name array
     */
    public function getStatesByCountry($countryName)
    {
        $states = [];
        $url = commanConstans::STATE_API_URL;
        $parms = [ 'country' => $countryName ];
        $statesData = $this->getDataFromApi($url, "POST", $parms);
        if(isset($statesData['data']['states'])){
            $states = array_map(function ($ar) {return $ar['name'];}, $statesData['data']['states']);
        }
        return $states;
    }

    /**
     * Get List of Citites Details
     * 
     * @return Cities name array
     */
    public function getCityByState($countryName, $stateName)
    {
        $url = commanConstans::CITY_API_URL;
        $parms = [ 'country' => $countryName, 'state' => $stateName ];
        $citiesData = $this->getDataFromApi($url, "POST", $parms);
        return $citiesData['data'];
    }

}

?>