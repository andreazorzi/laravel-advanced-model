<?php

namespace AdvanceModel\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

trait BaseController
{
    private static function modal($modal_name, $data = []){
        return View::make("components.backoffice.modals.$modal_name", $data);
    }
    
    private static function modal_data($model, $data = []){
        return self::modal("$model-data", $data);
    }
}