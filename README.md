Консольная команда для добавления записей из CSV файла в БД MySql
Синтаксис

import:command <format> <file> [--test] [--clear]
format определяет ридер для парсинга файла (с помощью фабрики). Опция --clear - для очистки таблицы.

Фабрика ImportFactory определяет ридер для заданного формата
Сервис ImportService валидирует объекты и сохраняет их в БД (при необходимости). Также выполняет очистку таблицы.
Слушатели ParseErrorListener и ProductFailListener логируют не импортированные объекты и ошибки парсинга.

Для парсинга использован Ddeboer Data Import Bundle
Для миграции использован DoctrineMigrationsBundle