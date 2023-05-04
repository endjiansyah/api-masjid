<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    function MediaMasjidbyID($id)
    {
        $media = Media::query()->where("id_masjid",$id)->get();

        $data = [
            "data" => $media
        ];

        return $data;
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
}
