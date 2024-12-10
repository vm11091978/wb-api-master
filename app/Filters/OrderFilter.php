<?php

namespace App\Filters;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter implements FilterInterface
{
    use SqlFormingTrait;

    public static function searchByRequest (FormRequest $request): Builder
    {
        $keys = Order::query()
            ->selectRaw('JSON_KEYS(json) as keysJson')
            ->first();

        $sqlStr = self::keysToSql($keys);

        return Order::query()
            ->select("date")
            ->selectRaw("$sqlStr")
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}