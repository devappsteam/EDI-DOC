<?php

/**
 * Gera a assinatura HMAC-SHA256 em Base64
 * para o header X-Signature da Cielo.
 *
 * @param string $hmacKey Chave secreta HMAC
 * @param string $requestBody JSON exatamente como será enviado no body
 * @return string
 */
function generateHmac(string $hmacKey, string $requestBody): string
{
    if ($hmacKey === '') {
        throw new InvalidArgumentException('HMAC key não pode ser vazia.');
    }

    if ($requestBody === '') {
        throw new InvalidArgumentException('Request body não pode ser vazio.');
    }

    $hmacBytes = hash_hmac(
        'sha256',
        mb_convert_encoding($requestBody, 'UTF-8'),
        mb_convert_encoding($hmacKey, 'UTF-8'),
        true // retorna bytes binários, igual ao Java
    );

    return base64_encode($hmacBytes);
}


$hmacKey = 'CHAVE DO CLIENTE GERADA NO PORTAL DA CIELO';

$payload = [
    'merchantCode' => 'NUMERO DO PONTO DE VENDA',
    'fileType' => [3,4,15,16],
    'processType' => ['D'],
    'startDate' => '2026-06-17',
    'endDate' => '2026-06-17'
];

$requestBody = json_encode(
    $payload,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

$xSignature = generateHmac($hmacKey, $requestBody);

echo $xSignature;
