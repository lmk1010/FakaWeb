<?php
declare(strict_types=1);

namespace App\Pay\Epay\Impl;

use App\Pay\Signature as SignatureInterface;

class Signature implements SignatureInterface
{
    public function verification(array $data, array $config): bool
    {
        if (!isset($data['sign'])) {
            return false;
        }

        $key = trim((string)($config['key'] ?? ''));
        if ($key === '') {
            return false;
        }

        $sign = (string)$data['sign'];
        unset($data['sign'], $data['sign_type']);

        $localSign = $this->buildSign($data, $key);
        return strtolower($sign) === strtolower($localSign);
    }

    private function buildSign(array $params, string $key): string
    {
        ksort($params);
        $pairs = [];
        foreach ($params as $k => $v) {
            if ($v === '' || $v === null || is_array($v)) {
                continue;
            }
            $pairs[] = $k . '=' . $v;
        }
        return md5(implode('&', $pairs) . $key);
    }
}
