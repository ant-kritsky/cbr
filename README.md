# Установка

После клонирования репозитория выполнить команду:

$ composer install

Изменить настройки доступа к Redis и RabbitMQ в /config.php

Настроить nginx(на апаче не успел проверить) на index.php в корневой дирректории:

    location / {
        # try to serve file directly, fallback to index.php
        try_files $uri /index.php$is_args$args;
    }

# Использование

**Пример запроса на получение курса**:

    GET /?date=2023-10-12&currency=usd&base=rur 

Параметры:

**date** - Дата в формате yyyy-mm-dd (обязательный)

**currency** - Код валюты курс которой требуется получить (обязательный)

**base** - Код базовой валюты (по умолчанию RUR)


**Запуск воркера:**

$ php console.php fetch:currency-rates 
