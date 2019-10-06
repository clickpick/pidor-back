<?php

Route::post('auth', 'MeController@me');
Route::get('friends', 'MeController@getFriends');

Route::get('pidor_of_the_day', 'PidorOfTheDayController@current');
