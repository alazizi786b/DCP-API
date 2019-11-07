<?php

namespace App\Http\Controllers;

use App\Activity;
use App\GSMAData;
use App\Http\Requests\IMEISearchRequest;
use App\Http\Requests\ReportRequest;
use App\Image;
use App\Libraries\WCOApi;
use App\SurveyData;
use App\TAC;
use Carbon\Carbon;
use App\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\Response;

class SurveyDataController extends Controller
{

    public function report(ReportRequest $request)
    {
        try {
            $request->validated();
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e
            ], Response::HTTP_BAD_REQUEST);
        }

        $imei = $request->imei_number;

        if (TAC::where('imei_number', $imei)->exists()) {

            $tac = TAC::where('imei_number', $imei)->first();


            if ($request->hasFile('reportImage')) {
                //we'll use its ID
                $survey = $this->createSurvey($request, $tac);
                //dd($survey);
                $this->createImage($request->reportImage, $survey);
           //dd($this->createImage($request->reportImage, $survey));
                //dd($request->reportImage);
                $this->createActivity($tac);

                return response()->json([
                    'success' => true,
                    'message' => 'Device Reported Successfully'
                ]);


            }


            //we'll use its ID
            $survey = $this->createSurvey($request, $tac);
            $this->createActivity($tac);
            return response()->json([
                'success' => true,
                'message' => 'Device Reported Successfully'
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'No TAC found'
        ]);


    }

    protected function createImage($reportImage, $survey)
    {

        if (isset($reportImage) && count($reportImage) <= 5) {

            foreach ($reportImage as $image) {
                $name = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/images/', $name);
            // dd($image);
                //dd(public_path().'/images');
                $img = new Image();

                //$img->url = $name;
                // $img->url = asset('/images/'.$name);
                $img->url =  asset('/images/'.$name);
                //dd($img->url);
                 //$replace = array('[', ']','"',' ');
              //  $img->url = str_replace(array('[', ']','"',' ') , array('-'), $imgUrl);
                //dd($img->url);
                //$img->url = public_path().'/images'.$name;
               // dd($img->url);
                $img->survey_id = $survey->id;
                $img->save();
            }
            return true;
        } else {
            return false;
        }


    }

    public function getReport()
    {
        $surveys = SurveyData::with('images', 'tac', 'tac.gsmaData', 'user')->paginate(100);
 
        return view('survey', compact('surveys'));

    }

    /**
     * @param ReportRequest $request
     * @param $tac
     * @return SurveyData
     */
    protected function createSurvey(ReportRequest $request, $tac): SurveyData
    {
        $survey = new SurveyData();
        $survey->brand_name = $request->brand_name;
        $survey->model_name = $request->model_name;
        $survey->shop_address = $request->shop_address;
        $survey->web_address = $request->web_address;
        $survey->specifications = $request->specifications;
        $survey->description = $request->description;

        $survey->user_id = auth()->user()->id;
        $survey->tac_id = $tac->id;

        $survey->save();
        return $survey;
    }

    /**
     * @param $tac
     */
    protected function createActivity($tac): void
    {
        $activity = new Activity();
        $activity->user_id = auth()->user()->id;
        $activity->user_name = auth()->user()->first_name;
        $activity->tac_id = $tac->id;
        $activity->is_reported = true;

        $activity->save();
    }

}
