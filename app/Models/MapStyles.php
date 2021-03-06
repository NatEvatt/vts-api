<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MapStyles
{
    public function getMapStyles()
    {
        $user = app()->request->user();
        $query = DB::table('maps.mapstyles');
        //if not logged in return all except for user_id
        if($user){
            $query->select('*');
        } else {
            $query->select('id', 'name', 'url', 'type', 'author', 'image', 'github', 'jsonStyle', 'date');
        }
        $query->orderBy('name');
        $data = $query->get();

        //if logged in return array with editable field
        $user = app()->request->user();
        if($user){
            foreach($data as $mapStyle){
                $mapStyle->editable = ($mapStyle->user_id == $user->user_id) ? true : false;
                unset($mapStyle->user_id);
            }
        }
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
        // $id = DB::table('maps.mapstyles')->insertGetId($data);
        $where = [
          'id' => $data['id']
        ];
        DB::table('maps.mapstyles')->where($where)->update($data);
        return $data['id'];
    }

    public function deleteMapStyle($id)
    {
        // $id = DB::table('maps.mapstyles')->insertGetId($data);
        $where = [
          'id' => $id
        ];
        $data = DB::table('maps.mapstyles')->where($where)->delete();
    }

    public function getMapStyleById($id)
    {
        $mapStyle = DB::selectOne('SELECT * FROM maps.mapstyles WHERE id = ?', [ $id ]);
        return $mapStyle;
    }

    public function addImageLink($url, $id)
    {
        DB::table('maps.mapstyles')
            ->where('id', $id)
            ->update(['image' => $url]);
    }
}
