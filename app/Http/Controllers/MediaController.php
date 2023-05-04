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

    
}
