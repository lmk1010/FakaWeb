<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Base\View\Manage;
use App\Interceptor\ManageSession;
use Kernel\Annotation\Interceptor;
use Kernel\Exception\JSONException;
use Kernel\Exception\ViewException;

#[Interceptor(ManageSession::class)]
class Store extends Manage
{
    private const MARKET_DISABLED_MSG = "应用市场已禁用，当前仅支持本地插件。";

    /**
     * @throws ViewException
     */
    public function index(): string
    {
        return $this->render("店铺共享", "Shared/Store.html");
    }


    /**
     * @return string
     * @throws ViewException
     * @throws JSONException
     */
    public function home(): string
    {
        throw new JSONException(self::MARKET_DISABLED_MSG);
    }


    /**
     * @throws ViewException
     * @throws JSONException
     */
    public function developer(): string
    {
        throw new JSONException(self::MARKET_DISABLED_MSG);
    }
}
