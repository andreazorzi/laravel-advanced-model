<?php

namespace AdvanceModel\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

class CreateModel extends Command
{
    protected $signature = 'advance:create-model {model_name} {--type=} {--force}';
    private $types = ["only-model", "with-controller", "with-page", "complete"];
    private $files;
    private $error = 0;
    private $errors = [
        1           => "Invalid model name",
        2           => "Command type option not valid",
        4           => "File already exist",
        8           => "Route request placeholder are missing, be sure to add them before running this command",
        16          => "Route web placeholder is missing, be sure to add it before running this command",
        32          => "Model table is missing",
    ];

    protected $description = 'This command will create a laravel model';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->files = [
            "model.php" => ["path" => App::path("Models/"), "name" => ":MODEL_NAME:.php", "type" => 0],
            "factory.php" => ["path" => App::databasePath("factories/"), "name" => ":MODEL_NAME:Factory.php", "type" => 0],
            "controller.php" => ["path" => App::path("Http/Controllers/"), "name" => ":MODEL_NAME:Controller.php", "type" => 1],
            "page.blade.php" => ["path" => App::resourcePath("/views/backoffice/"), "name" => ":MODEL_NAME_PLURAL_LOWER:.blade.php", "type" => 2],
            "modal.blade.php" => ["path" => App::resourcePath("/views/components/backoffice/modals/"), "name" => ":MODEL_NAME_LOWER:-data.blade.php", "type" => 3],
        ];
    }

    public function handle()
    {
        $model_name = $this->argument("model_name");
        
        // Check if the model name is valid (PascalCase and singular)
        preg_match('/^[A-Z][a-z]+(?:[A-Z][a-z]+)*$/m', $model_name, $matches);
        if(empty($matches) || Str::singular($model_name) != $model_name){
            $this->track_error(1);
            $this->error("Invalid model name: $model_name");
            return $this->error;
        }
        
        // Check if the type is valid
        $type = $this->option('type') ?? "complete";
        if (!in_array($type, $this->types)) {
            $this->track_error(1);
            $this->error("The type must be one of: ".implode(", ", $this->types));
            return $this->error;
        }
        $type = array_search($type, $this->types);
        
        $this->info("Creating model: $model_name\n");
        
        // Get model name derivatives
        $model_name_lower = Str::kebab($model_name);
        $model_name_variable = Str::snake($model_name);
        $model_name_plural = Str::plural($model_name);
        $model_name_plural_lower = Str::kebab($model_name_plural);
        $model_name_plural_variable = Str::snake($model_name_plural);
        
        // Copy the files
        foreach ($this->files as $file => $data) {
            if($type < $data["type"]) continue;
            
            // Get the files paths
            $template_path = __DIR__ . '/../storage/templates/'.$file;
            $file_name = str_replace(
                [":MODEL_NAME:", ":MODEL_NAME_VARIABLE:", ":MODEL_NAME_LOWER:", ":MODEL_NAME_PLURAL:", ":MODEL_NAME_PLURAL_LOWER:"],
                [$model_name, $model_name_variable, $model_name_lower, $model_name_plural, $model_name_plural_lower],
                $data["name"]
            );
            $file_path = $data["path"].$file_name;
            
            // Check if the file already exists, if it does, skip it, unless --force is used
            if (file_exists($file_path) && !$this->option('force')) {
                $this->warn("File `$file_name` already exists, use --force to overwrite");
                // 4
                continue;
            }
            
            // Check if the directory exists, if not, create it
            if(!file_exists($data["path"])){
                mkdir($data["path"], 0755, true);
            }
            
            // Get the template file contents
            $content = file_get_contents($template_path);
            
            // Replace placeholders
            $content = str_replace(
                [":MODEL_NAME:", ":MODEL_NAME_VARIABLE:", ":MODEL_NAME_LOWER:", ":MODEL_NAME_PLURAL:", ":MODEL_NAME_PLURAL_LOWER:"],
                [$model_name, $model_name_variable, $model_name_lower, $model_name_plural, $model_name_plural_lower],
                $content
            );
            
            // Save the file
            file_put_contents($file_path, $content);
            
            $this->info("- $file_name: created");
        }
        
        // check requests placeholders - err: 16
        if($type >= 1){
            $route_request_file = file_get_contents(App::basePath("routes/requests.php"));
            
            if(Str::contains($route_request_file, "// End Controllers Imports") && Str::contains($route_request_file, "// End Models Routes")){
                if(!Str::contains($route_request_file, "use App\Http\Controllers\\{$model_name}Controller;")){
                    $route_request_file = str_replace("// End Controllers Imports", "use App\Http\Controllers\\{$model_name}Controller;\n// End Controllers Imports", $route_request_file);
                }
                if(!Str::contains($route_request_file, "Route::resource('$model_name_plural_lower', {$model_name}Controller::class);")){
                    $tabs = Str::repeat(" ", 4 * 3);
                    $route_request_file = str_replace("// End Models Routes", "// $model_name_plural\n".$tabs."// End Models Routes", $route_request_file);
                    $route_request_file = str_replace("// End Models Routes", "Route::resource('$model_name_plural_lower', {$model_name}Controller::class);\n".$tabs."\n".$tabs."// End Models Routes", $route_request_file);
                }
                
                file_put_contents(App::basePath("routes/requests.php"), $route_request_file);
            }
            else{
                $this->track_error(8);
            }
        }
        
        // check web placeholders - err: 32
        if($type >= 2){
            $route_web_file = file_get_contents(App::basePath("routes/web.php"));
            
            if(Str::contains($route_web_file, "// End Models Routes")){
                if(!Str::contains($route_web_file, "Route::view('$model_name_plural_lower', 'backoffice.$model_name_plural_lower'")){
                    $tabs = Str::repeat(" ", 4 * 3);
                    $route_web_file = str_replace("// End Models Routes", "Route::view('$model_name_plural_lower', 'backoffice.$model_name_plural_lower', headers: ['menu' => true])->name('backoffice.$model_name_plural_lower');\n".$tabs."// End Models Routes", $route_web_file);
                }
                
                file_put_contents(App::basePath("routes/web.php"), $route_web_file);
            }
            else{
                $this->track_error(16);
            }
        }
        
        // Check if model table exists
        if (!Schema::hasTable($model_name_plural_variable)) {
            $this->warn("\nTable `$model_name_plural_variable` does not exists, rememer to run `php artisan migrate`!");
        }
        
        // Create the model
        if($this->error == 0){
            $this->info("\nModel creation completed");
            return 0;
        }
        
        $this->error("\nModel creation failed ({$this->error}): \n\t- ".implode("\n\t- ", $this->get_erros($this->error)));
        $this->warn("\nAfter correcting the errors, re-run this command!");
        return $this->error;
    }
    
    private function track_error($key){
        $this->error = $this->error | $key;
    }
    
    private function get_erros($error):array{
        $errors = [];
        
        foreach ($this->errors as $value => $description) {
            if ($error & $value) {
                $errors[] = $description;
            }
        }
        
        return $errors;
    }
}
