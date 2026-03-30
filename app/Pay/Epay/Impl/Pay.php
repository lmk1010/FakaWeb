<?php
declare(strict_types=1);

namespace App\Pay\Epay\Impl;

use App\Entity\PayEntity;
use App\Pay\Base;
use App\Pay\Pay as PayInterface;
use Kernel\Exception\JSONException;

class Pay extends Base implements PayInterface
{
    public function trade(): PayEntity
    {
        $api = rtrim((string)($this->config['api'] ?? ''), '/');
        $pid = trim((string)($this->config['pid'] ?? ''));
        $key = trim((string)($this->config['key'] ?? ''));

        if ($api === '' || $pid === '' || $key === '') {
            throw new JSONException('易支付配置不完整，请检查接口地址、PID 和 PKEY');
        }

        $params = [
            'pid' => $pid,
            'type' => $this->code ?: 'alipay',
            'out_trade_no' => $this->tradeNo,
            'notify_url' => $this->callbackUrl,
            'return_url' => $this->returnUrl,
            'name' => (string)($this->config['order_name'] ?? '订单支付'),
            'money' => number_format($this->amount, 2, '.', ''),
        ];

        $siteName = trim((string)($this->config['sitename'] ?? ''));
        if ($siteName !== '') {
            $params['sitename'] = $siteName;
        }

        $params['sign'] = $this->buildSign($params, $key);
        $params['sign_type'] = strtoupper((string)($this->config['sign_type'] ?? 'MD5'));

        $entity = new PayEntity();
        $entity->setType(PayInterface::TYPE_REDIRECT);
        $entity->setUrl($api . '/submit.php?' . http_build_query($params));
        return $entity;
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
