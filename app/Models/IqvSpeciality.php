<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IqvSpeciality extends Model
{
    use HasFactory;

    protected $table ='iqv_specialities';


    protected $fillable = [
        'name',
        'company_id',
        'status',
    ];
    public function consets(){
        return $this->hasMany('iqv_consents','speciality_id');
    }

}
