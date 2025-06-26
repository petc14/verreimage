# VerreImage

This project is a small PHP MVC application. It does not ship with any Composer
dependencies. If you plan to add packages via Composer, run `composer install`
so that the `vendor/autoload.php` file is generated. The initialization script
will load this file automatically when present.

## Database setup

The repository includes an SQL schema in `sql/schema.sql`. Import it into your
MySQL server to create the required tables:

```bash
mysql -u root -p < sql/schema.sql
```

