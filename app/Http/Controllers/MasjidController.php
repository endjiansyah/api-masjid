<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MediaController;

class MasjidController extends Controller
{

    function index()
    {
        $masjid = Masjid::query()->get();

        return response()->json([
            "status" => true,
            "message" => "list masjid",
            "data" => $masjid
        ]);
    }

    function show($id)
    {
        $masjid = Masjid::query()
            ->where("id", $id)
            ->first();

        $media = new MediaController;
        $getMedia = $media->MediaMasjidbyID($id);

        if (!isset($masjid)) {
            return response()->json([
                "status" => false,
                "message" => "masjid with id ".$id." not found",
                "data" => null,
                
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "masjid with id ".$id,
            "data" => $masjid,
            "media" => $getMedia
        ]);
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $validator = Validator::make($payload, [
            "nama_masjid" => 'required',
            "kota" => 'required',
            "provinsi" => 'required',
            "alamat" => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
                "data" => null
            ]);
        }

        if(isset($payload["tahun_didirikan"])){
            $validator = Validator::make($payload, [
                "tahun_didirikan" => 'integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => null
                ]);
            }
        }
        $masjid = Masjid::query()->create($payload);

        return response()->json([
            "status" => true,
            "message" => "data saved successfully",
            "data" => $masjid
        ]);
    }

    function update(Request $request, $id)
    {
        $masjid = Masjid::query()->where("id", $id)->first();
        if (!isset($masjid)) {
            return response()->json([
                "status" => false,
                "message" => "masjid with id ".$id." not found",
                "data" => null
            ]);
        }

        
        if(isset($request["nama_masjid"])){
            if( $request["nama_masjid"] == null){
                return response()->json([
                    "status" => false,
                    "message" => "nama_masjid is required",
                    "data" => null
                ]);
            }
        }

        if(isset($request["kota"])){
            if( $request["kota"] == null){
                return response()->json([
                    "status" => false,
                    "message" => "kota is required",
                    "data" => null
                ]);
            }
        }

        if(isset($request["provinsi"])){
            if( $request["provinsi"] == null){
                return response()->json([
                    "status" => false,
                    "message" => "provinsi is required",
                    "data" => null
                ]);
            }
        }

        if(isset($request["alamat"])){
            if( $request["alamat"] == null){
                return response()->json([
                    "status" => false,
                    "message" => "alamat is required",
                    "data" => null
                ]);
            }
        }

        $payload = $request->all();
        
        if(isset($payload["tahun_didirikan"])){
            $validator = Validator::make($payload, [
                "tahun_didirikan" => 'integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => null
                ]);
            }
        }
        $masjid->fill($payload);
        $masjid->save();

        return response()->json([
            "status" => true,
            "message" => "data changes successfully saved",
            "data" => $masjid
        ]);
    }

    function destroy($id)
    {
        $masjid = Masjid::query()->where("id", $id)->first();
        if (!isset($masjid)) {
            return response()->json([
                "status" => false,
                "message" => "data not found",
                "data" => null
            ]);
        }

        $masjid->delete();
    
        return response()->json([
            "status" => true,
            "message" => "data deleted successfully",
            "data" => $masjid
        ]);
    }
}
