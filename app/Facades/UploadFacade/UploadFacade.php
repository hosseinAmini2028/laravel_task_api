<?php

namespace App\Facades\UploadFacade;

class UploadFacade
{
    public function uploadFile($image, $path)
    {
        $myimage =  date('YmdHi') . $image->getClientOriginalName();
        $image->move(public_path("images/{$path}/"), $myimage);
        return asset("images/{$path}/" . $myimage);
    }

    public function deleteFile($file)
    {

        $server_url = (isSecure() ? 'https://' : 'http://') .  $_SERVER['HTTP_HOST'];
        $file_url = substr($file, strlen($server_url) + 1);

        unlink($file_url);

        return $file_url;

    }
}
