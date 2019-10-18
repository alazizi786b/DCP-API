<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GSMAData extends Model
{
    protected $table = "gsmadatas";
    protected $guarded=[];
    protected $fillable = [
        'gsma_device_id',
        'gsma_model_name',
        'gsma_brand_name',
        'gsma_marketing_name'
    ];

    public function tac()
    {
        return $this->belongsTo(TAC::class);
    }
}
