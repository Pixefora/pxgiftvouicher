<?php

namespace pxgiftvoucher\classes\help;

final class HelperTools
{

    public static function setLangStringsConfig(string $key): array {
        $return = [];

        $languages = \Language::getLanguages(true, false , true );


        foreach ($languages as $lang){

           $return[$lang]  = \Configuration::get($key,$lang);
        }


        return $return;


    }

}