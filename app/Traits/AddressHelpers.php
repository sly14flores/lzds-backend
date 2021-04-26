<?php

namespace App\Traits;

use App\Models\PhilippineRegion;
use App\Models\PhilippineProvince;
use App\Models\PhilippineCity;
use App\Models\PhilippineBarangay;

trait AddressHelpers {

    public function getProvince($code)
    {
        $province = PhilippineProvince::where('province_code',$code)->first();

        return $province->province_description;
    }

    public function getCity($code)
    {
        $city = PhilippineCity::where('city_municipality_code',$code)->first();

        return $city->city_municipality_description;
    }
    
    public function getBarangay($code)
    {
        $barangay = PhilippineBarangay::where('barangay_code',$code)->first();

        return $barangay->barangay_description;
    }    

}