<?php

namespace App\Models;

use Illuminate\Http\Request;
use SearchTable\Traits\SearchModel;
use AdvancedModel\Traits\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table(/* name: 'table_name', key: 'code', incrementing: false, */ timestamps: false)]
#[Guarded(['no_key'])]
class :MODEL_NAME: extends Model
{
    use HasFactory, BaseModel, SearchModel;
    
    /** Settings **/
    protected static $table_fields = [
        "name" => [
            "filter" => true,
            "sort" => "asc",
            // "custom-label" => "Test",
            // "custom-value" => "getNameText", // the name of the model function
            // "custom-filter" => "(SELECT d.name FROM departments d WHERE d.id = department_id)", // sql value to filter by
            // "advanced-type" => "like",
        ],
    ];
    
    public function getTableActions($model_name, $key):array{
        return [
            // [
            //     "url" => route("backoffice.index"),
            //     "attributes" => 'role="button"',
            //     "title" => "Modifica pagine",
            //     "content" => '<i class="fa-regular fa-file-lines text-primary"></i>'
            // ],
            
            // Default action
            [
                "attributes" => 'data-id="'.$key.'" hx-get="'.route($model_name.".show", [$key ?? 0]).'" hx-target="#modal .modal-content"',
                "content" => '<i class="table-search-preview fa-solid fa-pen"></i>'
            ]
        ];
    }
    
    /** Relations **/
    
    /** Scopes **/
    
    /** Methods **/
    
    /** Update, delete and validation functions **/
    public function updateFromRequest(Request $request, bool $update = true):array{
        $validation = self::validate($request, $update);
        if ($validation["status"] == "danger") {
            return $validation;
        }
        
        // // Custom key value for model without incrementing
        // if(!$this->incrementing && !$update){
        //     // $request->merge([self::getModelKey() => self::modelCustomKeyGeneration()]);
        // }
        
        // Fill the model with the request
        $this->fill($request->all());
        
        // If the model is dirty, save it
        if($this->isDirty()){
            $this->save();
        }
        
        return ["status" => "success", "message" => __('advanced-model::actions.'.($update ? "updated" : "created"), ["model" => ":MODEL_NAME:"]), "model" => $this, "beforeshow" => 'modal.hide(); htmx.trigger("#page", "change");'];
    }
    
    public function deleteFromRequest():array{
        $name = $this->name;
        $this->delete();
        
        return ["status" => "success", "message" => __('advanced-model::actions.deleted', ["model" => ":MODEL_NAME:"]), "beforeshow" => 'modal.hide(); htmx.trigger("#page", "change");'];
    }
    
    public static function validate(Request $request, bool $update):array{
        $validator = Validator::make($request->all(), [
            "name" => ['required'],
        ]);

        if ($validator->fails()) {
            return ["status" => "danger", "message" => implode("\\n", $validator->errors()->all())];
        }

        return ["status" => "success"];
    }

    /** Attributes casting **/
    protected function casts(): array
    {
        return [
            
        ];
    }
}
