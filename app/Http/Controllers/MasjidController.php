<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MediaController;
use App\Models\Media;

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

        $masjid['media'] = $getMedia;

        return response()->json([
            "status" => true,
            "message" => "masjid with id ".$id,
            "data" => $masjid,
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

        $responsemedia = [];
        $files = $request->file('media');
        foreach ($files as $file) {
            $mime = $file->getClientMimeType();
            $mimetype = explode("/",$mime);
            if($mimetype[0] == "image"||$mimetype[0] == "video"){
                $filename = $file->hashName();
                $file->move("media/".$mimetype[0]."/", $filename);
                $path = $request->getSchemeAndHttpHost() . "/media/".$mimetype[0]."/" . $filename;
                $payloadmedia = [
                    "link" => $path,
                    "mime" => $mimetype[0],
                    "name" => $filename,
                    "id_masjid" => $masjid->id
                ];
                $media = Media::query()->create($payloadmedia);
                $responsemedia[]=$media;

            }
        }

        $masjid["media"] = $responsemedia;

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

    function destroy(Request $request,$id)
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

        $listmedia = [];
        
        $media = new MediaController;
        $getmedia = $media->MediaMasjidbyID($id);

        foreach($getmedia as $data){
            $med = $media->Destroymedia($request,$data->id,true);
            $listmedia[] = $med;

        }
        $masjid["media"] = $listmedia;
    
        return response()->json([
            "status" => true,
            "message" => "data deleted successfully",
            "data" => $masjid
        ]);
    }
}
