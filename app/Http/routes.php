<?php

use \Illuminate\Support\Facades\Response,
    \LucaDegasperi\OAuth2Server\Facades\Authorizer;

Route::get('/', function () {
    return view('app');
});

Route::post('oauth/access_token', function(){
    return Response::json(Authorizer::issueAccessToken());
});


Route::group(['middleware'=>'oauth'], function(){

    Route::resource('client', 'ClientController', ['except'=>['create','edit']]);

    Route::resource('project', 'ProjectController', ['except'=>['create','edit']]);
    Route::get('projects-member', 'ProjectController@projectsMember');

    Route::resource('project.member', 'ProjectMemberController', ['except'=>['create','edit', 'update']]);

    Route::group(['prefix'=>'project','middleware'=>'check.project.permission'], function(){
        Route::get('{id}/note', 'ProjectNoteController@index');
        Route::post('{id}/note', 'ProjectNoteController@store');
        Route::get('{id}/note/{noteId}', 'ProjectNoteController@show');
        Route::put('{id}/note/{noteId}', 'ProjectNoteController@update');
        Route::delete('{id}/note/{noteId}', 'ProjectNoteController@destroy');


        Route::get('{id}/file', 'ProjectFileController@index');
        Route::get('{id}/file/{fileId}', 'ProjectFileController@show');
        Route::get('{id}/file/{fileId}/download', 'ProjectFileController@showFile');
        Route::post('{id}/file', 'ProjectFileController@store');
        Route::put('{id}/file/{fileId}', 'ProjectFileController@update');
        Route::delete('{id}/file/{fileId}', 'ProjectFileController@destroy');


        Route::get('{id}/member', 'ProjectController@members');
        Route::post('{id}/member/{member_id}', 'ProjectController@addMember');
        Route::delete('{id}/member/{member_id}', 'ProjectController@removeMember');

        Route::get('{id}/task', 'ProjectTaskController@index');
        Route::get('{id}/task/{taskId}', 'ProjectTaskController@show');
        Route::post('{id}/task', 'ProjectTaskController@store');
        Route::put('{id}/task/{taskId}', 'ProjectTaskController@update');
        Route::delete('{id}/task/{taskId}', 'ProjectTaskController@destroy');
    });

    Route::get('user/authenticated', 'UserController@authenticated');

    Route::resource('user', 'UserController', ['except'=>['create','edit']]);
});


