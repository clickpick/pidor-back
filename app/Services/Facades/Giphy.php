<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class Giphy extends Facade {
    protected static function getFacadeAccessor() { return 'giphy'; }
}
