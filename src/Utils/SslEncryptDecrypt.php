<?php

namespace Adopet\Utils;

class SslEncryptDecrypt
{

    public function encrypt(string $data): string
    {
        Enviroment::addEnv();

        $dataEncrypt = openssl_encrypt(
            $data,
            'aes-128-gcm',
            base64_decode(getenv('SSLPASSWORD')),
            $options = 0,
            base64_decode(getenv('IV')),
            $tag
        );
        // $this->tag = base64_encode($tag);
        //var_dump($tag); testes
        return base64_encode($dataEncrypt.$tag);
    }

    public function decrypt(string $cipherText): string
    {
        $cipherText = base64_decode($cipherText);
        $authTag = substr($cipherText, -16);
        //$tagLength = strlen($authTag); teste

        // var_dump($cipherText);
        // var_dump($authTag); testes
        // var_dump($tagLength);
        Enviroment::addEnv();

        return openssl_decrypt(
            substr($cipherText,0,-16),
            'aes-128-gcm',
            base64_decode(getenv('SSLPASSWORD')),
            $options = 0,
            base64_decode(getenv('IV')),
            $authTag
        );
    }
}