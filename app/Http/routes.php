<?php

use \Illuminate\Support\Facades\Response,
    \LucaDegasperi\OAuth2Server\Facades\Authorizer;

Route::get('/', function () {
    return view('welcome');
});

Route::post('oauth/access_token', function(){
    return Response::json(Authorizer::issueAccessToken());
});


Route::group(['middleware'=>'oauth'], function(){

    Route::resource('client', 'ClientController', ['except'=>['create','edit']]);

    //Route::group(['middleware'=>'CheckProjectOwner'], function(){
        Route::resource('project', 'ProjectController', ['except'=>['create','edit']]);

        Route::get('project/{id}/members', 'ProjectController@members');
        Route::get('project/{id}/members/{member_id}/add', 'ProjectController@addMember');
        Route::delete('project/{id}/members/{member_id}/remove', 'ProjectController@removeMember');

        Route::get('project/{id}/tasks', 'ProjectController@tasks');
        Route::post('project/{id}/tasks/add', 'ProjectController@addTask');
        Route::delete('project/{id}/tasks/{task_id}/remove', 'ProjectController@removeTask');
   // });

    Route::group(['prefix'=>'project'], function(){
        Route::get('{id}/note', 'ProjectNoteController@index');
        Route::post('{id}/note', 'ProjectNoteController@store');
        Route::get('{id}/note/{noteId}', 'ProjectNoteController@show');
        Route::put('{id}/note/{noteId}', 'ProjectNoteController@update');
        Route::delete('{id}/note/{noteId}', 'ProjectNoteController@destroy');


        Route::post('{id}/file/', 'ProjectFileController@store');
    });

});


