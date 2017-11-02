<?php

use Illuminate\Support\Facades\Gate;
use App\Models\MapStyles;

//  Include Single Sign-On Routes
include_once 'login.php';

$app->get('/', function () use ($app) {
    // return $app->version();

    return "<style>body{background:#282a36;animation:colorchange 10s infinite;}
        @keyframes colorchange{0%{color:#8be9fd;}10%{color:#50fa7b;}30%{color:#ffb86c;}50%
        {color:#ff79c6;}70%{color:#bd93f9;}90%{color:#6272a4;}100% {color:#8be9fd;}}
        </style><pre>
____   _______________         _____ __________.___
\   \ /   /\__    ___/        /  _  \\______   \   |
 \   Y   /   |    |  ______  /  /_\  \|     ___/   |
  \     /    |    | /_____/ /    |    \    |   |   |
   \___/     |____|         \____|__  /____|   |___|
                                    \/

        Running on {$app->version()} </pre>";
});

//Return Map Styles
$app->get('/get_mapstyles', function () use ($app) {
  $data = app(MapStyles::class)->getMapStyles();
  // return response()->json($data);
  return $data;
});

$app->group(['middleware' => 'auth'], function () use ($app) {
//Must have a token for all api routes here

    //Test
    $app->get('/howdy', function () use ($app) {
        $name = app()->request->user()->name;
        $thisVar = "Yo What up Blood! The username is " . $name;
        return $thisVar;
    });

    //Save New Map Style
    $app->post('/save_new_map_style', function () use ($app) {
        $content = json_decode(app()->request->getContent(), true);
        $content['user_id'] = app()->request->user()->user_id;
        $id = app(MapStyles::class)->saveNewMapStyle($content);
      return $id;
    });

    //Edit Map Style
    $app->post('/edit_map_style', function () use ($app) {
        $content = json_decode(app()->request->getContent(), true);
        $content['user_id'] = app()->request->user()->user_id;
        $results = app(MapStyles::class)->editMapStyle($content);
      return $results;
    });

    //Upload Image
    $app->post('/upload_image/{mapStyle}', function ($mapStyle) use ($app) {

        // only allow images
        $mimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/bmp'];
        $photoDir = storage_path("mapPreviews/{$mapStyle}");
        $files = app()->request->file();
        $list = [];
        foreach ($files as $file) {
            if ($file->isValid() && in_array($file->getMimeType(), $mimeTypes)) {

                // save file
                $filename = 'img_'.date('Ymd-His')."_".$file->getClientOriginalName();
                $file->move($photoDir, $filename);

                // generate a thumbnail
                $thumb = Image::make("$photoDir/$filename")->resize(80, 80,  function ($constraint) {
                    $constraint->aspectRatio();
                });
                $thumb->save("$photoDir/thumb_{$filename}");

                $list[] = [
                    'filename' => $filename,
                    'thumb' => "/mapPreviews/$mapStyle/thumbs/$filename",
                    'full' => "/mapPreviews/$mapStyle/photos/$filename"
                ];
            }
        }
        return response()->json($list, 201);

        // $results = app(MapStyles::class)->editMapStyle($content);
        echo "shiza";
      return "shooza";
    });

    //Display thumbnail
    $app->get('getPreview/{mapStyle}/thumbs/{filename}', function ($mapStyle, $filename) {
        $filename = urldecode($filename);
        $thumbPath = storage_path("bmps/{$mapStyle}/thumb_{$filename}");
        if (is_file($thumbPath)) {
           return response()->download($thumbPath);
        } else {
            $photoPath = storage_path("bmps/bmp_{$idbmp}/$filename");
            if (is_file($photoPath)) {
                $thumb = Image::make($photoPath)->resize(80, 80,  function ($constraint) {
                    $constraint->aspectRatio();
                });
                $thumb->save($thumbPath);
               return response()->download($thumbPath);
            }
        }
        return response('', 404);
    });

});

?>
