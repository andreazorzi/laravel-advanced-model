<?php

namespace AdvanceModel\Traits;

use Illuminate\Support\Facades\View;

trait AlertResponse
{
    public function alert($data){
        $alert = [
            "status" => $data["status"] ?? "info",
            "message" => $data["message"] ?? "",
            "callback" => $data["callback"] ?? null,
            "beforeshow" => $data["beforeshow"] ?? null,
            "duration" => $data["duration"] ?? null
        ];
        
        return View::make("laravel-advance-model::components.alert", $alert);
    }
    
    public function sweetAlert($data){
        $alert = [
            "status" => $data["status"] ?? "info",
            "title" => $data["title"] ?? "",
            "message" => $data["message"] ?? "",
            "confirm" => [
                "text" => $data["confirm"]["text"] ?? null,
                "color" => $data["confirm"]["color"] ?? null,
                "disable" => $data["confirm"]["disable"] ?? null,
            ],
            "cancel" => [
                "text" => $data["cancel"]["text"] ?? null,
                "color" => $data["cancel"]["color"] ?? null,
                "disable" => $data["cancel"]["disable"] ?? null,
            ],
            "beforeshow" => $data["beforeshow"] ?? null,
            "onsuccess" => $data["onsuccess"] ?? null,
            "oncancel" => $data["oncancel"] ?? null
        ];
        
        return View::make("laravel-advance-model::components.sweetalert", $alert);
    }
}