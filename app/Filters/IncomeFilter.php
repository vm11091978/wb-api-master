<?php

namespace App\Filters;

use App\Models\Income;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class IncomeFilter implements FilterInterface
{
    use SqlFormingTrait;

    public static function searchByRequest (FormRequest $request): Builder
    {
        $keys = Income::query()
            ->selectRaw('JSON_KEYS(json) as keysJson')
            ->first();

        $sqlStr = self::keysToSql($keys);

        return Income::query()
            ->select("date")
            ->selectRaw("$sqlStr")
            ->whereBetween('date', [
                $request->dateFrom,
                $request->dateTo,
            ]);
    }
}