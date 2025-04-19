<?php

namespace App\Controllers;

use App\Models\LocationModel;

class LocationController
{
    public static function getAllLocations(): array
    {
        return LocationModel::getAllLocations();
    }
}