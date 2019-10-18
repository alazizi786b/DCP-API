<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TAC extends Model
{
    protected $table = "tacs";
    protected $guarded = [];
    protected $fillable = [
        'imei_number',
        'gsma_status',
        'survey_status'
    ];

    public function surveys()
    {
        return $this->hasMany(SurveyData::class,'tac_id');
    }

    public function gsmaData()
    {
        return $this->hasMany(GSMAData::class,'tac_id');

    }
}
