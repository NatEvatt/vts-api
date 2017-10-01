<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MapStyles
{
    public function getMapStyles()
    {
        $query = "SELECT * FROM maps.mapstyles ORDER BY name";
        $data = DB::select($query);
        return $data;
    }

    public function saveNewMapStyle($data)
    {
        // some date or today
        if (empty($data['date'])) {
            $data['date'] = date(DATE_ATOM);
        }

        $id = DB::table('maps.mapstyles')->insertGetId($data);
        return $id;
    }
}
