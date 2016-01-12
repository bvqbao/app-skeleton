## Introduction

This is a simple MVC framework built from the following packages:

- Illuminate Database (5.*),
- Illuminate Events (5.*),
- Illuminate Validation (5.*),
- Illuminate Translation (5.*),
- Slim Framework (^3.0),
- Monolog (^1.17),
- Aura Session (^2.0).

## Installation

1. Download the framework.
2. Upload the framework files to your server (optional).
3. Open ```.htaccess``` file, update ```RewriteBase``` (if necessary).
4. Run ```composer update``` to pull out the dependencies.
5. Setup your routes at ```app/routes.php```.

If you don't have a "real" web server installed, you can use the PHP's build-in server (for development only) as follows:
```php -S localhost:8080 -t public/```
