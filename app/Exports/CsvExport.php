<?php

namespace App\Exports;

use App\SurveyData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use DB;
use App\TAC;
use Maatwebsite\Excel\Concerns\WithMapping;


class CsvExport implements FromCollection, WithHeadings, WithMapping
{
	


    public function collection()
    {

    	  $surveys = SurveyData::with('images', 'tac', 'tac.gsmaData', 'user')->get();
    	 return $surveys;
        
    }


     public function headings(): array
    {
        return [
            'gsma_device_id',
            'imei_number',
            'brand_name',
            'model_name',
            'images',
            'gsma_brand_name',
            'gsma_model_name',
            'first_name',
            'email',
            'shop_address',
            'web_address',
            'created_at',
            'description'
        ];
    }

     public function map($surveys): array
    {
        return [
        	$surveys->tac->gsmaData->pluck('gsma_device_id')->first(),
        	$surveys->tac()->pluck('imei_number')->first(),
        	$surveys->images()->where('survey_id',$surveys->id)->get(),
        	$surveys->brand_name,
        	$surveys->model_name,
        	$surveys->tac->gsmaData->pluck('gsma_brand_name')->first(),
        	$surveys->tac->gsmaData->pluck('gsma_model_name')->first(),
        	$surveys->user->first_name,
        	$surveys->user->email,
        	$surveys->shop_address,
        	$surveys->web_address,
        	$surveys->created_at,
        	$surveys->description

            
        ];
    }

}
