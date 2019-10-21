<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DCP GSMA</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>

    <div class="col-md-12">
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>

                    <th scope="col">TAC (First 8 digits of IMEI)</th>
                    <th scope="col">IMEI (15 Digits)</th>
                    <th scope="col">Evidence of Non Compliance</th>
                    <th scope="col">Non Compliance Brand Name</th>
                    <th scope="col">Non Compliance Model Name</th>
                    <th scope="col">Original Brand Name</th>
                    <th scope="col">Original Model Name</th>
                    <th scope="col">Non Compliant Contact Name</th>
                    <th scope="col">Non Compliant Contact Email Address</th>
                    <th scope="col">Non Compliant HQ Address</th>
                    <th scope="col">Non Compliant Web Address</th>
                    <th scope="col">Date Evidence Obtained</th>
                    <th scope="col">Describe how evidence obtained</th>
                </tr>
                </thead>
                <tbody>
                @foreach($surveys as $survey)
                    <tr>
                        <td>{{$survey->tac->gsmaData->pluck('gsma_device_id')->first()}}</td>
                        <td>{{$survey->tac()->pluck('imei_number')->first()}}</td>
                        <td>

                            {{--                {{dd($images)}}--}}
                            @foreach ($survey->images()->where('survey_id',$survey->id)->get() as $image)

                                <img src="{{asset('images/'.$image->url)}}" alt="" style="width: 50px; height: 50px">

                            @endforeach</td>
                        <td>{{$survey->brand_name}}</td>
                        <td>{{$survey->model_name}}</td>
                        <td>{{$survey->tac->gsmaData->pluck('gsma_brand_name')->first()}}</td>
                        <td>{{$survey->tac->gsmaData->pluck('gsma_model_name')->first()}}</td>
                        <td>{{$survey->user->first_name}}</td>
                        <td>{{$survey->user->email}}</td>
                        <td>{{$survey->shop_address}}</td>
                        <td>{{$survey->web_address}}</td>
                        <td>{{$survey->created_at}}</td>
                        <td>{{$survey->description}}</td>

                        </td>

                    </tr>
                @endforeach

                </tbody>

            </table>
            {{$surveys->links("pagination::bootstrap-4")}}
        </div>
    </div>



</body>
</html>
