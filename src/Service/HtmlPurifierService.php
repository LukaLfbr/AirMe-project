<?php

namespace App\Service;

namespace App\Service;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurifierService
{
    public function purify(string $data): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'b,i,u,a[href],ul,ol,li,p');
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($data);
    }

    public function purifyArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $this->purify($value);
            }
        }
        return $data;
    }
}