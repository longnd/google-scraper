# google-scraper
A simple PHP scraper that makes use of the [Symfony](https://symfony.com/) web framework.

This application supports extracting data from the Google search results page.

## Deploying

### On your local:
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