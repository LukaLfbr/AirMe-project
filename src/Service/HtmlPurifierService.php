<?php

namespace App\Service;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurifierService
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'b,i,u,a[href],ul,ol,li,p');

        // Définir le répertoire de cache accessible en écriture sur Platform.sh
        $cacheDir = '/tmp/htmlpurifier';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cacheDir);

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Purify a string of HTML, allowing only basic text formatting elements
     *
     * @param string $data The string to be purified
     *
     * @return string The purified string
     */
    public function purify(string $data): string
    {
        return $this->purifier->purify($data);
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
