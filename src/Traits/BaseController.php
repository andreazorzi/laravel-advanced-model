<?php

namespace AdvancedModel\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

trait BaseController
{
    private function modal($modal_name, $data = []){
        return View::make("components.backoffice.modals.$modal_name", $data);
    }
    
    private function modal_data($model, $data = []){
        return $this->modal("$model-data", $data);
    }
}