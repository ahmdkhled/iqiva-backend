<?php

namespace App\Http\Controllers\Api;

use App\Models\Consent;
use App\Rules\MaxWordsRule;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConsentController extends Controller
{
    use GeneralTrait;

    public function CreateConsent(Request $request)
    {
        try {

            $token = JWTAuth::fromUser(auth()->guard()->user());

            if (!$token) {
                return $this->returnError('e02', 'UnAuthorized');
            }


            $validator = \Validator::make($request->all(), [

                'full_name_en' => new MaxWordsRule(3),
                'full_name_ar' => new MaxWordsRule(3),
                'email' => 'required|unique:users',
                'speciality' => 'required',
                'gov' => 'required',
                'city' => 'required',
                'address' => 'required',
                'hospital_name' => 'required',
                'phone_number' => 'required',
                'notes' => 'required',

            ]);

            if ($validator->fails()) {
                return $this->returnError('E02', $validator->errors()->all());
            }
            DB::beginTransaction();
            $consent = new Consent();
            $consent->full_name_en = $request->full_name_en;
            $consent->full_name_ar = $request->full_name_ar;
            $consent->speciality = $request->speciality;
            $consent->gov = $request->gov;
            $consent->city = $request->city;
            $consent->address = $request->address;
            $consent->hospital_name = $request->hospital_name;
            $consent->phone_number = $request->phone_number;
            $consent->email = $request->email;
            $consent->notes = $request->notes;
            $consent->on_key_id = $request->on_key_id;
            $consent->user_id = auth()->guard()->id();
            $consent->save();

            DB::commit();
            return $this->returnData('Consent', $consent, 'Consent Created  Successfully');

        } catch (\Exception $ex) {
            DB::rollback();

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function GetConsents($pag_count = 20)
    {
        try {
            $token = JWTAuth::fromUser(auth()->guard()->user());

            if (!$token) {
                return $this->returnError('e02', 'UnAuthorized');
            }
            $consents = DB::table('iqv_consents')->latest()->paginate($pag_count);
            return $this->returnData('Consents', $consents, 'Consents retrieved  Successfully');

        } catch (\Exception $ex) {
            DB::rollback();

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function SearchConsents(Request $request, $pag_count = 20, $key = 'none')
    {
        try {

            $token = JWTAuth::fromUser(auth()->guard()->user());

            if (!$token) {
                return $this->returnError('e02', 'UnAuthorized');
            }


            if ($request->has('array_search')) {
                $search = $request->array_search;


                $data = DB::Table('iqv_consents')
                    ->when(isset($search['city']), function ($q) use ($search) {
                        return $q->where('city', $search['city']);
                    })
                    ->when(isset($search['gov']), function ($q) use ($search) {
                        return $q->where('gov', $search['gov']);
                    })
                    ->when(isset($search['speciality']), function ($q) use ($search) {
                        return $q->where('speciality', $search['speciality']);
                    })
                    ->latest()->paginate($pag_count);
                return $this->returnData('data', $data, 'Consents retrieved  Successfully');


            }

            $consent = new Consent();

            switch ($key) {

                case 'full_name_en':
                    return $consent->ConsentSearchLike('full_name_en', $request, $pag_count);
                case 'full_name_ar':
                    return $consent->ConsentSearchLike('full_name_ar', $request, $pag_count);
                case 'phone_number':
                    return $consent->ConsentSearchLike('phone_number', $request, $pag_count);
                case 'on_key_id':
                    return $consent->ConsentSearchLike('on_key_id', $request, $pag_count);
                case 'speciality':
                    return $consent->ConsentSearchString('speciality', $request, $pag_count);
                case 'gov':
                    return $consent->ConsentSearchString('gov', $request, $pag_count);
                case 'city':
                    return $consent->ConsentSearchString('city', $request, $pag_count);
                case 'hospital_name':
                    return $consent->ConsentSearchString('hospital_name', $request, $pag_count);
            }
        } catch (\Exception $ex) {
            DB::rollback();

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


}
