<?php

namespace App\Service;

namespace App\Service;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurifierService
{
    /**
     * Purify a string of HTML, allowing only basic text formatting elements
     *
     * @param string $data The string to be purified
     *
     * @return string The purified string
     */
    public function purify(string $data): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'b,i,u,a[href],ul,ol,li,p');
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($data);
    }

    /**
     * Purify an array of strings, allowing only basic text formatting elements
     *
     * @param array $data The array to be purified
     *
     * @return array The purified array
     */
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