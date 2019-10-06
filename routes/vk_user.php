<?php

Route::post('auth', 'MeController@me');
Route::get('friends', 'MeController@getFriends');

Route::get('pidor-of-the-day', 'PidorOfTheDayController@current');

Route::post('post-story', 'MeController@postStory');
