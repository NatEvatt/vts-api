<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

$app->group(['middleware' => 'auth'], function () use ($app) {

    $app->get('/test-python', function () {
        // if (Gate::denies('onboarding')) {
        //     return response('Forbidden', 403);
        // }

        $process = new Process(env('PYTHON_ENV') . " " . env('PYTHON_DIR') . "main.py python_test");
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    });

    //getTileInfo
    $app->post('/get_tile_info', function () use ($app) {
      $zoom = app()->request->input('zoom');
      $top_left_lat = app()->request->input('top_left_lat');
      $top_left_lon = app()->request->input('top_left_lon');
      $bottom_right_lat = app()->request->input('bottom_right_lat');
      $bottom_right_lon = app()->request->input('bottom_right_lon');

      $process = new Process(env('PYTHON_ENV') . " " . env('PYTHON_DIR') . "main.py get_tile_info {$zoom} {$top_left_lat} {$top_left_lon} {$bottom_right_lat} {$bottom_right_lon} ");
      $process->run();
      if (!$process->isSuccessful()) {
          throw new ProcessFailedException($process);
      }
      return $process->getOutput();
    });
    //printImage
    $app->post('/print-image', function () use ($app) {
      $zoom = app()->request->input('zoom');
      $top_left_lat = app()->request->input('top_left_lat');
      $top_left_lon = app()->request->input('top_left_lon');
      $bottom_right_lat = app()->request->input('bottom_right_lat');
      $bottom_right_lon = app()->request->input('bottom_right_lon');

      $process = new Process(env('PYTHON_ENV') . " " . env('PYTHON_DIR') . "main.py vt_print {$zoom} {$top_left_lat} {$top_left_lon} {$bottom_right_lat} {$bottom_right_lon} ");
      $process->run();
      if (!$process->isSuccessful()) {
          throw new ProcessFailedException($process);
      }
      return $process->getOutput();
    });

    $app->get('/create-agol-group/{groupId}', function ($groupId) {
        if (Gate::denies('onboarding')) {
            return response('Forbidden', 403);
        }
        $usernames = app()->request->input('usernames');

        $process = new Process(env('PYTHON_ENV') . " " . env('PYTHON_DIR') . "main.py create_group \"{$groupId}\" \"{$usernames}\"");
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();
    });


});
