<?php

Route::post('auth', 'MeController@me');
Route::get('friends', 'MeController@getFriends');
