<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MapStyles
{
    public function getMapStyles()
    {
        $query = "SELECT * FROM maps.mapStyles ORDER BY name";
        $data = DB::select($query);
        return $data;
    }
}
