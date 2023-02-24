<?php

namespace App\Facades\UploadFacade\Facades;

use Illuminate\Support\Facades\Facade;

class UploadFacade extends Facade{
    protected static function getFacadeAccessor(){
        return 'upload';
    }
}