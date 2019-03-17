# Google scraper
A simple PHP scraper that makes use of the [Symfony](https://symfony.com/) web framework.

This application supports extracting data from the Google search results page.

[![Build Status](https://travis-ci.com/longnd/google-scraper.svg?token=YtVwfd3RAgKquYqTmUWB&branch=master)](https://travis-ci.com/longnd/google-scraper)
## Deploying

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/longnd/google-scraper/tree/master)

### Or, setting up your local :
```sh
$ git clone git@github.com:longnd/google-scraper.git 
$ cd google-scraper
$ composer install
$ yarn install
```

* Setting up the environment variables:  
Copy the `.env` file to create your own `.env.local` file and override the environment settings on your local machine: Database connection, mailer ...
```sh
$ cp .env .env.local
``` 

* Execute the database migrations:
```sh
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate 
```

* Build the assets:  
Symfony uses [Webpack Encore](https://www.npmjs.com/package/@symfony/webpack-encore) to integrate [Webpack](https://webpack.js.org/) to the application. Run this command to build the assets:
```sh
# compile assets once
$ yarn encore dev

# or, recompile assets automatically when files change
$ yarn encore dev --watch
```

### Demo
[https://gg-scraper.herokuapp.com/](https://gg-scraper.herokuapp.com/)

Notice: This demo runs on a [free Heroku dyno](https://devcenter.heroku.com/articles/free-dyno-hours) which will sleep if it receives no web traffic in a 30-minute period so you might expect a short delay for the first try.

### More info
This app was built on these things:
- [Symfony](https://symfony.com/) 4 web framework
- [PSR-1](https://www.php-fig.org/psr/psr-1/) and [PSR-2](https://www.php-fig.org/psr/psr-2/) as coding standards - verified by [PHP Coding standard fixer](https://cs.symfony.com/)
- [Travis CI](https://travis-ci.org) to build & test 
- [Heroku](https://heroku.com) to run the app
