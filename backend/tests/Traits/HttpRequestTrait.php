<?php

/**
 * @copyright  Copyright (c) 2025 Code Chip (https://codechip.com.br)
 * @author     Will <willvix@outlook.com>
 * @Link       https://github.com/code-chip
 */

namespace Tests\Traits;

trait HttpRequestTrait
{
    private const BASE_URL = 'http://localhost:80';

    public function httpRequest(string $method, string $path, array $body = []): array
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => self::BASE_URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ];

        if (!empty($body)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['status' => $status, 'body' => $response];
    }
}