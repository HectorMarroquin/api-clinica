<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Acerca de la Api Clinica.

Esto es una Api creado en Laravel para una clinica permite proveer de toda la lógica del negocio para realizar todas la funcionalidades que se desean cubrir. 

## Herrmianetas utilizadas.

> - Composer 
> - PHP 8.2.4
> - Apache
> - JWT
> - Laravel 10

## Pasos para su instalación.

1. `git clone https://github.com/HectorMarroquin/api-clinica.git`
2. `composer install`
3.  crear y configurar el `.env` con las conexiones de DB
4.  `php artisan migrate`
5.  `composer require tymon/jwt-auth`
6.  `php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`
7.  `php artisan jwt:secret`
8.  `php artisan serve`
9. modificar el .env el **APP_URL**

## Notas importantes.

