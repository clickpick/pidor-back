<?php

Route::post('auth', 'MeController@me');
Route::get('friends', 'MeController@getFriends');

Route::get('pidor-of-the-day', 'PidorOfTheDayController@current');

Route::post('prepare-story', 'MeController@prepareStory');
Route::post('post-story', 'MeController@postStory');

Route::post('give-pidor-rate', 'MeController@givePidorRate');
