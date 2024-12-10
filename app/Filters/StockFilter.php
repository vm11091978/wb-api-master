<?php

namespace App\Filters;

use App\Models\Stock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class StockFilter implements FilterInterface
{
    use SqlFormingTrait;

    public static function searchByRequest (FormRequest $request): Builder
    {
        $keys = Stock::query()
            ->selectRaw('JSON_KEYS(json) as keysJson')
            ->first();

        $sqlStr = self::keysToSql($keys);

        return Stock::query()
            ->selectRaw("$sqlStr")
            ->whereBetween('created_at', [
                $request->dateFrom,
                Carbon::tomorrow()->format('Y-m-d H:i:s'),
            ]);
    }
}