<?php

namespace App\Filters;

trait SqlFormingTrait
{
    static function keysToSql($keys): string
    {
        // Если ячейка "json" типа json таблицы БД пуста, вернём пустую строку
        if (! $keys) {
            return '';
        }

        // Получим массив всех ключей ячейки "json" типа json таблицы БД 
        $keysJson = json_decode($keys->keysJson, true);

        $sqlArr = [];
        foreach ($keysJson as $keyJson) {
            $sqlArr[] = 'JSON_UNQUOTE(JSON_EXTRACT(json, "$.' . $keyJson . '")) as ' . $keyJson;
        }

        return implode(', ', $sqlArr);
    }
}