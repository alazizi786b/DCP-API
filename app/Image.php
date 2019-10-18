<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = "images";
    protected $fillable = ['survey_id', 'url'];


    public function survey()
    {
        return $this->belongsTo(SurveyData::class);
    }
}
