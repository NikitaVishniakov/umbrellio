# Тестовое задание PHP 

По заданию было создано REST JSON API:
- фреймворк Laravel 5.6
- php 7.2.8 
- СУБД PostgreSQL 10.4

Дополнительно был использован PHP HTTP client
- guzzle/guzzle  


## API ENDPOINTS
 - Создание нового поста: POST /post. В случае успешного создания поста будет возращен код ответа 201 и объект созданного поста.
 - Создание отзыва к посту: POST /post/{post_id}/review . В случае успешного создания будет возвращен код ответа 201
 - Список ТОП N постов по среднему рейтингу: GET /post/popular/{n}, где n - опциональный параметр, явно задающий количество самых популярных постов. По умолчанию будет выведено 5 самых популяных постов. Код ответа 200
 - Список IP с которых были созданы посты более чем одним пользователем: GET /ip/users - JSON обект со списоком IP и логинами пользователей, код ответа 200 в случае успеха, 204, если нет таких IP.


## Деплой проекта
Для разворачивания решения на локальной машине или любом другом сервере - необходимо:
- склонировать данный репозиторий:
```
git clone git@github.com:NikitaVishniakov/umbrellio.git
```

- Затем развернуть Laravel через composer install.
```console
foo@bar:../project_dist$ composer install
```
- Отредактировать .env файл и переключить его на работу с PostgreSQL


### Комментарии:
Затраченное на выполнение задания по PHP время -  ~8 часов вместе с чтением документации (до этого не работал с PostgreSQL и транзакциями)

Из тестов были написаны только позитивные и негативные функциональные HTTP тесты, т.к. в данном случае не видел смысла в написании Unit тестов. Тесты находятся в **/tests/Feature**

Фреймворк Laravel был использован т.к. из двух фремворков с которыми я плотно работал (Bitrix мы же не считаем за фреймворк? :) ) - он в большей мере подходит для создания API, нежели CakePHP.


##Зaдaниe нa знaниe SQL:
дaнa тaблицa users видa - id, group_id

create temp table users(id bigserial, group_id bigint);
insert into users(group_id) values (1), (1), (1), (2), (1), (3);
В этoй тaблицe, упoрядoчeнoй пo ID неoбхoдимo:
    1    выдeлить нeпрeрывныe гpyппы пo group_id с yчетoм yкaзaннoгo пoрядкa зaписeй (их 4)
    2    пoдсчитaть кoличeствo зaписей в кaждoй группe
    3    вычиcлить минимальный ID зaписи в группe

#### Решение задания SQL
```
SELECT add_group AS min_id, group_id, COUNT(id) AS count 
FROM (select *,
        CASE 
            WHEN LEAD(group_id,1,NULL) OVER(PARTITION BY group_id ORDER BY id) = group_id
            THEN MIN(id) OVER(PARTITION BY group_id ORDER BY id)
            ELSE id
        END add_group
        FROM users
        ORDER BY id) sq
GROUP BY real_group, group_id
ORDER BY min_id;
```
