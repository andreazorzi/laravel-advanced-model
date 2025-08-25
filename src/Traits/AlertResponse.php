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
        
        return View::make("components.alert", $alert);
    }
    
    public function sweet_alert($data){
        $alert = [
            "status" => $data["status"] ?? "info",
            "title" => $data["title"] ?? "",
            "message" => $data["message"] ?? "",
            "duration" => $data["duration"] ?? null,
            "confirm" => [
                "text" => $data["confirm"]["text"] ?? "OK",
                "color" => $data["confirm"]["color"] ?? "#0A5399",
                "disable" => $data["confirm"]["disable"] ?? false
            ],
            "cancel" => [
                "text" => $data["confirm"]["text"] ?? "Cancel",
                "color" => $data["confirm"]["color"] ?? "#DC3545",
                "disable" => $data["confirm"]["disable"] ?? true
            ],
            "beforeshow" => $data["beforeshow"] ?? null,
            "onsuccess" => $data["onsuccess"] ?? null,
            "oncancel" => $data["oncancel"] ?? null
        ];
        
        return View::make("components.sweet-alert", $alert);
    }
}