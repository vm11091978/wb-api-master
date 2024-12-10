<?php

namespace App\Filters;

use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class SaleFilter implements FilterInterface
{
    use SqlFormingTrait;

    public static function searchByRequest (FormRequest $request): Builder
    {
        $keys = Sale::query()
            ->selectRaw('JSON_KEYS(json) as keysJson')
            ->first();

        $sqlStr = self::keysToSql($keys);

        return Sale::query()
            ->select("date")
            ->selectRaw("$sqlStr")
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}