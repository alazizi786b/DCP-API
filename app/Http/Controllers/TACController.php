<?php

namespace App\Http\Controllers;

use App\Activity;
use App\GSMAData;
use App\Http\Requests\IMEISearchRequest;
use App\Libraries\WCOApi;
use App\TAC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TACController extends Controller
{

    /**
     * @param IMEISearchRequest $request
     * @param $user_device
     * @param $checking_method
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookup(IMEISearchRequest $request, $user_device, $checking_method)
    {
        try {
            $request->validated();
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e
            ], Response::HTTP_BAD_REQUEST);
        }
        $input_search = $request->get('imei_number');

        $wco_port_name = config('app.wco_port_name');
        $wco_port_type = config('app.wco_port_type');
        $wco_country = config('app.wco_country');
        $input = substr($input_search, 0, 8);
        list($data, $response) = (new WCOApi())->wcoGetHandSetDetails($input, $wco_port_name, $wco_country,
            $wco_port_type);

        try {
            $data = \GuzzleHttp\json_decode($response->getBody());
            if (isset($data) && $data->statusCode === 200) {

                $tac = new TAC();

                $tac->imei_number = $input_search;

                if ($data->gsmaApprovedTac === "No") {
                    $tac->gsma_status = 'not-approved';
                } elseif ($data->gsmaApprovedTac === "Yes") {
                    $tac->gsma_status = 'approved';
                }

                $tac->save();

                $gsmaData = new GSMAData();
                $gsmaData->gsma_device_id = $data->deviceId;
                $gsmaData->gsma_model_name = $data->modelName;
                $gsmaData->gsma_brand_name = $data->brandName;
                $gsmaData->gsma_marketing_name = $data->marketingName;
                $gsmaData->tac_id = $tac->id;
                $gsmaData->save();

                $activity = new Activity();
                $activity->user_device = $user_device;
                $activity->checking_method = $checking_method;
                $activity->user_id = auth()->user()->id;
                $activity->user_name = auth()->user()->first_name;
                $activity->tac_id = $tac->id;

                $activity->save();

                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);

            } elseif (isset($data) && ($data->statusCode === 100 || $data->statusCode === 101)) {
                return response()->json([
                    'error' => true,
                    'status_code' => Response::HTTP_CONTINUE,
                    'message' => trans('wco.responses.100')
                ]);
            } elseif (isset($data) && $data->statusCode === 102) {
                return response()->json([
                    'error' => true,
                    'status_code' => Response::HTTP_PROCESSING,
                    'message' => trans('wco.responses.102')
                ]);
            } elseif (isset($data) && $data->statusCode === 400) {
                return response()->json([
                    'error' => true,
                    'status_code' => Response::HTTP_BAD_REQUEST,
                    'message' => trans('wco.responses.400')
                ]);
            } elseif (isset($data) && $data->statusCode === 401) {
                return response()->json([
                    'error' => true,
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                    'message' => trans('wco.responses.401')
                ]);
            }

        } catch (\Exception $ex) {
            return response()->json([
                'errors' => true,
            ], $ex->getCode());
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function matched(Request $request)
    {
        if (TAC::where('imei_number', $request->imei_number)->exists()) {

            TAC::where('imei_number', $request->imei_number)
                ->update([
                    'survey_status' => 'matched'
                ]);

            return response()->json([
                'success' => true,
                'message' => trans('responses.imei.matched')
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'No TAC found'
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notMatched(Request $request)
    {
        if (TAC::where('imei_number', $request->imei_number)->exists()) {

            TAC::where('imei_number', $request->imei_number)
                ->update([
                    'survey_status' => 'not-matched'
                ]);

            return response()->json([
                'success' => true,
                'message' => trans('responses.imei.not_matched')
            ]);
        }
        return response()->json([
            'error' => true,
            'message' => 'No TAC found'
        ]);

    }
}
