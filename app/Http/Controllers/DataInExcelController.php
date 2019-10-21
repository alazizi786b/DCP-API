<?php

namespace App\Http\Controllers;
use App\GSMAData;
use Illuminate\Http\Request;
use App\Exports\CsvExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
//use Excel;

class DataInExcelController extends Controller
{
	
    public function csv_export(){
    	return Excel::download(new CsvExport, 'sample.csv');
    }

    public function export(){

    return Excel::download(new CsvExport, 'invoices.csv');
}

   


}
