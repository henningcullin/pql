# PQL

A sqlx inspired library for php. It is made for ease of use with sqlite and php.

### Contents:
- 1 Features
- 2 Examples
- 3 Setup

## 1 Features

### pql::query() 

Build a query with prepared statements and params

### pql::query_as()

Build a query with prepared statements, params and map the results to a class

### pql->fetch_one()

Execute the query and fetch only one of the results

### pql->fetch_all()

Execute the query and fetch all of the results

## 2 Examples

### pql::query()

#### Basic example

```php
$result = pql::query('SELECT * FROM machine')->fetch_all($conn);
```
#### Example with params

```php
$result = pql::query(
  'SELECT * FROM machine WHERE id = ?',
  4
)->fetch_one($conn);
```
### pql::query_as()

#### Basic example

```php
$result = pql::query_as(
  Machine::class,
  'SELECT * FROM machine'
)->fetch_all($conn);
```

#### Example with params

```php
$result = pql::query_as(
  Machine::class,
  'SELECT * FROM machine WHERE id <> ?',
  2
)->fetch_all($conn);
```

## 3 Setup

I am not quite sure of what exactly you need to do. But here is what I do to enable the SQLite3 extension in php:

#### Make sure the following are in your php.ini file:

```
extension=pdo_sqlite
extension=sqlite3
extension=php_pdo.dll
extension=php_pdo_sqlite.dll
```

#### Make sure you do the following:

Add the '(XAMPDIR)\php\ext' directory to your PATH ENV.
