<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


### API на фреймворке Laravel

#### Подтянуть зависимости после клонирования репозитория командой в терминале:

`composer install`


### Доступы для БД:

- host => mysql80.hostland.ru
- port => 3306
- database => host1857549_forge
- username => host1857549_forge
- password => E6kUTYrYwZq2tN4QEtyzsbEBk3ie

таблицы: 'incomes', 'orders', 'sales', 'stocks'


### Примеры api запросов: 

#### к таблице 'incomes':

https://wb-api-master.polyphyletic-prints.ru/api/incomes?dateFrom=1900-01-01&dateTo=2100-01-01&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie

https://wb-api-master.polyphyletic-prints.ru/api/incomes?dateFrom=1900-01-01&dateTo=2100-01-01&page=1&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100

#### к таблице 'orders':

https://wb-api-master.polyphyletic-prints.ru/api/orders?dateFrom=1900-01-01&dateTo=2100-01-01&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie

https://wb-api-master.polyphyletic-prints.ru/api/orders?dateFrom=1900-01-01&dateTo=2100-01-01&page=1&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100

#### к таблице 'sales':

https://wb-api-master.polyphyletic-prints.ru/api/sales?dateFrom=1900-01-01&dateTo=2100-01-01&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie

https://wb-api-master.polyphyletic-prints.ru/api/sales?dateFrom=1900-01-01&dateTo=2100-01-01&page=1&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100

#### к таблице 'stocks':
https://wb-api-master.polyphyletic-prints.ru/api/stocks?dateFrom=2024-12-10&dateTo=2024-12-10&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie

https://wb-api-master.polyphyletic-prints.ru/api/stocks?dateFrom=2024-12-10&dateTo=2024-12-10&page=1&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100

!! к таблице 'stocks' в параметрах 'dateFrom' и 'dateTo' указывать текущую дату


### Создание дампов БД через вызов обработчика по ссылкам:

https://wb-api-master.polyphyletic-prints.ru/handle

посмотреть код обработчика можно здесь:

https://github.com/vm11091978/wb-api-master/blob/main/app/Http/Controllers/HandlerController.php

