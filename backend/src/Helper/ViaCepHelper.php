<?php

namespace App\Helper;

class ViaCepHelper
{
    public static function fetch(string $cep): array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $response = file_get_contents($url);
        if ($response === false) {
            return [];
        }

        return json_decode($response, true);
    }
}
