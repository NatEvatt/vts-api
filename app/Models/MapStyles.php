<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MapStyles
{
    public function getMapStyles()
    {
        $query = "SELECT * FROM maps.mapstyles ORDER BY name";
        $data = DB::select($query);
        //if logged in return array with editable field
        $user = app()->request->user();
        if($user){
            foreach($data as $mapStyle){
                $mapStyle->editable = ($mapStyle->user_id == $user->user_id) ? true : false;
            }
        }
        // else {
        //     echo "I am not loogged in";
        // }
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

    public function editMapStyle($data)
    {
        $mapStyle = DB::select('SELCT * FROM maps.mapstyles WHERE id = ?', [ $data->id ]);
        if($mapStyle !== $data->user_id){
            return response('Forbidden', 403);
        } else {
            $id = DB::table('maps.mapstyles')->insertGetId($data);
            return $id;
        }

    }
}
