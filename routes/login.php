<?php

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;


//Token
$app->get('/token', function () use ($app) {
    $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
    $data->setIssuer(env('googleIssuer', false));
    $data->setAudience(env('googleAudience', false));

    //create token from string
    // $token = app()->request->header('Authorization');
    $token = app()->request->input('token', app()->request->bearerToken());
    //validate token
    $token = (new Parser())->parse((string) $token); // Parses from a string

    // echo "This is the header " . $token->getHeader('name');
    echo "THis is the claim" . $token->getClaim('name');

    var_dump($token->validate($data));
  // $thisVar = "Yo What up Token Friend!";
  return $token;
});
