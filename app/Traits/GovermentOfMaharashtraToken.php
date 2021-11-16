<?php


namespace App\Traits;



trait GovermentOfMaharashtraToken
{
    public static function getTokenHash($param)
    {
        return md5("Goverment-OfMaharashtra-{$param}-");
    }

}
