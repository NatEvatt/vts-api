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
});
