# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Documentation for workwork

## Step 1

###Create an empty database

## Step 2

###Clone repo to local environment

## Step 3

###Create a .env file in your repo and follow these config example:  

APP_ENV=local  
APP_DEBUG=true  
APP_KEY=[run php artisan key:generate]  
APP_URL=https://[your localhost domain]  
APP_OTHER_URL=https://[your localhost domain]  
LOG_CHANNEL=daily  

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=[your database name]  
DB_USERNAME=[your db username]  
DB_PASSWORD=[your db user password]  

CACHE_DRIVER=file  
SESSION_DRIVER=file  
QUEUE_DRIVER=sync  

REDIS_HOST=127.0.0.1  
REDIS_PASSWORD=null  
REDIS_PORT=6379  

SESSION_DOMAIN=  
SESSION_OTHER_DOMAIN=  

FACEBOOK_CLIENT_ID=1578992975731734  
FACEBOOK_SECRET_KEY=0cf182a8fe4ec8f076c75d7271181ffa  
FACEBOOK_REDIRECT_CALLBACK=https://dev.workwork.my/callback  
FACEBOOK_REDIRECT_OTHER_CALLBACK=https://dev.workwork.my/callback  

MAIL_DRIVER=mailgun  
MAIL_HOST=mailtrap.io  
MAIL_PORT=2525  
MAIL_USERNAME=null  
MAIL_PASSWORD=null  
MAIL_ENCRYPTION=null  

MAIL_SENDER_SMTP1=postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org  
MAILGUN_DOMAIN1=sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org  
MAILGUN_SECRET1=key-0c5c926c68e155db7c0a8c712ca6834a  

MAIL_SENDER_SMTP=postmaster@workwork.my  
MAILGUN_DOMAIN=workwork.my  
MAILGUN_SECRET=key-0c5c926c68e155db7c0a8c712ca6834a  

ALGOLIA_APP_ID=DXWCE2Q6H1  
ALGOLIA_API_KEY=e94b13c47a20a2a03d5de4a6ed10c5eb  
ALGOLIA_SEARCH_KEY=2f0e4f4b170e55a09e152a2d9677734a  
ALGOLIA_INDEX=adverts  
ALGOLIA_INDEX_ASC=adverts_salary_asc  
ALGOLIA_INDEX_DESC=adverts_salary_desc  

TWILIO_ACC_ID=AC7931f766c3a838eb713d5e43da4a7882  
TWILIO_AUTH_TOKEN=aa66dacb62d665324438672da3639ed0  

BRAINTREE_ENV=sandbox  
BRAINTREE_MERCHANT_ID=h3shp8gpp8gk3ydf  
BRAINTREE_PUBLIC_KEY=xfn7f8hh4p6ky95w  
BRAINTREE_PRIVATE_KEY=fd3fb9672e086b050e2482e6b4d22c3e  

SITE_URL=https://[your local domain]  

## Step 4 

###run these commands:  

composer update  
composer dump-autoload  
npm install  
npm run dev  
php artisan migrate  
php artisan cache:clear  
php artisan db:seed <-- (only do this once)  