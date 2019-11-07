<?php

namespace App\Exports;

use App\SurveyData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
          //$img = $surveys->images()->where('survey_id',$surveys->id)->pluck('url')->get();
    	 //echo $img;
         
          return $surveys;
        
    }


     public function headings(): array
    {

        return [
            'GSMA TAC',
            'Imei Number',
            'Brand Name',
            'Model Name',
            'Images',
            'GSMA Brand Name',
            'GSMA Model Name',
            'Name',
            'Email',
            'Shop Address',
            'Web Address',
            'Create At',
            'Description'
        ];
    }

     public function map($surveys): array
    {
        $img = $surveys->images()->where('survey_id',$surveys->id)->pluck('url')->all();
        
        $string =  preg_replace('/\s+/', '', $img);
       // $data = json_encode($string, JSON_UNESCAPED_SLASHES);
        //$images = implode(",",$data);
        //$string = trim($rep,"/");
        // function(){

        // }
        //$string = str_replace(array('\/', '\\'),'/', $rep);
       // $string = array_flatten($rep);
        //dd($string);
      // $string =  array_walk($rep,'/');
        //$string = array_map('/', $rep);
       // dd($string);
       // dd($string);
       // dd(class_basename($img));
        //dd($string);
      //  $string = str_replace("/","",$rep);
        //$string = last(explode("/",$rep));
        
          // dd($string);
        //$images = public_path().'/images'; 
        return [
        	$surveys->tac->gsmaData->pluck('gsma_device_id')->first(),
        	$surveys->tac()->pluck('imei_number')->first(),
        	$surveys->brand_name,
        	$surveys->model_name,
            implode(" ,", $string),
            // dd($string),
            // $string,
            
           // $surveys->images()->where('survey_id',$surveys->id)->pluck('url')->all(),

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
