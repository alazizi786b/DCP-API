<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyData extends Model
{
    protected $table = "survey_data";

    protected $guarded=[];

    public function tac()
    {
        return $this->belongsTo(TAC::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class,'survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
