<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    function MediaMasjidbyID($id)
    {
        $media = Media::query()->where("id_masjid",$id)->get();

        return $media;
    }

    function show($id)
    {
        $media = Media::query()->where("id",$id)->first();

        return response()->json([
            "status" => true,
            "message" => "media with id ".$id,
            "data" => $media
        ]);
    }

    function Store(Request $request)
    {
        $payload = request()->all();
        $validator = Validator::make($payload, [
            "id_masjid" => 'required',
            "media" => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
                "data" => null
            ]);
        }

        $file = $request->file("media");

        $mime = $file->getClientMimeType();
            $mimetype = explode("/",$mime);
            if($mimetype[0] != "image"&& $mimetype[0] != "video"){
                return response()->json([
                    "status" => false,
                    "message" => "media blocked ",
                    "data" => null
                ]);
            }
                $filename = $file->hashName();
                $file->move("media/".$mimetype[0]."/", $filename);
                $path = $request->getSchemeAndHttpHost() . "/media/".$mimetype[0]."/" . $filename;
                
                $payload['link'] = $path;
                $payload['name'] = $filename;
                $payload['mime'] = $mimetype[0];

                $media = Media::query()->create($payload);

        return response()->json([
            "status" => true,
            "message" => "success save media masjid with id ". $payload["id_masjid"],
            "data" => $media
        ]);
    }

    function Destroymedia(Request $request,$idmedia,$check=null)
    {
        $media = Media::query()->where("id", $idmedia)->first();
        if (!isset($media)) {
            return response()->json([
                "status" => false,
                "message" => "data not found",
                "data" => null
            ]);
        }

            $mediapath = str_replace($request->getSchemeAndHttpHost(), '', $media->link);
            $mediadel = public_path($mediapath);
            unlink($mediadel);
            $media->delete();

            if($check){
                return $media;
            }

        return response()->json([
            "status" => true,
            "message" => "data delete successfully",
            "data" => $media
        ]);
    }
}
