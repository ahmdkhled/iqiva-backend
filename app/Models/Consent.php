<?php

namespace App\Models;

use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consent extends Model
{
    use HasFactory;
    use GeneralTrait;

    protected $table = 'iqv_consents';
    protected $fillable = [
        'full_name_en',
        'full_name_ar',
        'speciality',
        'gov',
        'city',
        'address',
        'hospital_name',
        'phone_number',
        'email',
        'notes',
        'on_key_id',
    ];
    public $timestamps = true;

    public function ConsentSearchLike($search, $request, $pag_count)
    {
        if ($search == 'phone_number' or $search == 'on_key_id') {
            $data = DB::Table('iqv_consents')
                ->where($search, 'like', $request->search . '%')
                ->latest()->paginate($pag_count);
        } else {
            $data = DB::Table('iqv_consents')
                ->where($search, 'like', '%' . $request->search . '%')
                ->latest()->paginate($pag_count);
        }

        return $this->returnData('data', $data, 'search retrieved  Successfully');
    }

    public function ConsentSearchString($search, $request, $pag_count)
    {
        $data = DB::Table('iqv_consents')
            ->where($search, $request->search)
            ->latest()->paginate($pag_count);
        return $this->returnData('data', $data, 'search retrieved  Successfully');
    }

    public function speciality()
    {
        return $this->belongsTo('iqv_speciality');
    }
}
