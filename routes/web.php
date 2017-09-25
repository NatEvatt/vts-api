<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Gate;

use App\Models\MapStyles;

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

//Test
$app->get('/howdy', function () use ($app) {
  $thisVar = "Yo What up Blood!";
  return $thisVar;
});

//Return Map Styles
$app->get('/get_mapstyles', function () use ($app) {
  $thisVar = "I am going to return the maps styles";
  $data = app(MapStyles::class)->getMapStyles();
  // return response()->json($data);
  return $data;
});
