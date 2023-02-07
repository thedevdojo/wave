# Laravel sql_require_primary_key workaround

This package was created to work around an issue when using Laravel's schema builder with a
MySQL server that enforces primary keys (DigitalOcean Managed Databases for example).

## The problem

This issue occurs because the `sql_require_primary_key` flag in MySQL prevents the creation
of tables without a primary key. Because of the way Laravel's Schema builder assigns primary
keys to non-serial columns, he schema builder will first create the table without a primary
key, then immediately alter the table to assign a primary key.

### Example

```php
Schema::create('my_table', function (Blueprint $table) {
    $table->string('id')->primary();
});
```

The above call to the schema builder will create the below queries:

```sql
create table `my_table` (`id` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `my_table` add primary key `my_table_id_primary`(`id`);
```

If your MySQL server has `sql_require_primary_key` on, you will receive an error like this:

```
SQLSTATE[HY000]: General error: 3750 Unable to create or change a table without a primary key, when the system variable 'sql_require_primary_key' is set. Add a primary key to the table or unset this variable to avoid this message. Note that tables without a primary key can cause performance problems in row-based replication, so please consult your DBA before changing this setting.
```

## The solution

Installing this package will prevent this issue from occuring while still ensuring that primary
keys have been set on every table.

It works by listening for Laravel's migration events, and parsing the queries that are going
to be run to check if primary keys have been assigned to each table, and will throw an exception
if it finds a missing primary key.

It then temporarily disables `sql_require_primary_key`, runs your migration, then restores
`sql_require_primary_key` to its original value.

## Installation

This package can be installed via composer:

```
composer require chrisnharvey/laravel-sql-require-primary-key
```

If you have automatic package discovery enabled, then you're done. Otherwise, you will need to
register the following service provider:

```php
ChrisHavey\LaravelSqlRequirePrimaryKey\ServiceProvider::class
```

## Usage

Add the following config key to your MySQL database config.

```php
'require_primary_key' => true
```

## Disabling checks for a single migration

In most cases, you will not need this, but sometimes (particularly with raw queries) this package
may not detect primary keys and throw an exception. To disable these checks, add the following property
to the migration:

```php
public $skipPrimaryKeyChecks = true;
```