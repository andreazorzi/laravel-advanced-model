<?php

namespace AdvancedModel\Exports;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $models;
    protected $formats = [];
    
    public function __construct($models)
    {
        $this->models = $models;
    }
    
    public function collection(): \Illuminate\Support\Collection
    {
        return $this->models;
    }
    
    public function headings(): array
    {
        $header = [];
        
        foreach ($this->models[0]?->getTableFields() ?? [] as $field => $options) {
            $header[] = Str::title(__("validation.attributes.{$field}"));
        }
        
        return $header;
    }
    
    public function map($model): array
    {
        $data = [];
        
        foreach ($model->getTableFields() ?? [] as $field => $options) {
            $value = $model->$field;
            
            if(!empty($options["custom-value"])){
                $value = strip_tags($model->{$options["custom-value"]}());
            }
            
            $data[] = $value;
        }
        
        return $data;
    }
    
    public function columnFormats(): array
    {
        return [];
    }
    
    public function styles(Worksheet $sheet): array
    {
        return [];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Get the highest column and row
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                
                // Apply autofilter to the entire data range
                $sheet->setAutoFilter("A1:{$highestColumn}{$highestRow}");
                
                // Optional: Auto-size columns
                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}