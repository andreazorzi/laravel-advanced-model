<?php

namespace AdvancedModel\Traits;

use Illuminate\Http\Request;

trait BaseModel
{
    public static function createFromRequest(Request $request):array{
        return (new self)->updateFromRequest($request, false);
    }
}