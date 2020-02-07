<p align="center">
    <img src="http://i.imgur.com/EwYWc4Q.png" height="78">
</p>

<p align="center">
    <a href="https://travis-ci.org/Hackathonners/swap?branch=develop"><img src="https://travis-ci.org/Hackathonners/swap.svg?branch=develop" alt="TravisCI Status"></a>
    <a href='https://coveralls.io/github/Hackathonners/swap?branch=develop'><img src='https://coveralls.io/repos/github/Hackathonners/swap/badge.svg?branch=develop' alt='Coverage Status' /></a>
    <a href="https://scrutinizer-ci.com/g/Hackathonners/swap/"><img src="https://scrutinizer-ci.com/g/Hackathonners/swap/badges/quality-score.png" alt="ScrutinizerCI Status"></a>
</p>

## About Swap

Swap simplifies the control of enrollments and shifts exchanges for both students and teachers. The project is developed considering the following features:

- Students enroll in the available courses;
- Shifts are assigned to enrollments by teachers;
- Students propose and confirm shift exchanges among them.

This project was developed with the Board of Directors of the University of Minho Informatics Engineering Integrated Masters  and counts on several Hackathonners who either concluded the aforementioned Masters or are in the process of doing so.

## Requirements

- [PHP](http://php.net/) 7.4+
- PostgreSQL database
- [Composer](https://getcomposer.org/) - Installs package dependencies
- [NodeJS](https://nodejs.org/en/) - Provides NPM to install node packages
- [Yarn](https://yarnpkg.com/lang/en/) - Provides a fast, reliable and secure node package manager.

## Installation

- Clone or download this repository.
- Rename `.env.example` to `.env` and fill the options
> **Note**: This project sends e-mails. Therefore, ensure that the e-mail driver is specified.

- Install project dependencies:
```
composer install
yarn install
```

- Generate application key:
```
php artisan key:generate
```

- Migrate and seed the database:
```
php artisan migrate
php artisan db:seed
```

- Build assets (e.g. in development environment)
```
npm run dev
```

- Start local server
```
php artisan serve
```

## License
The Swap project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
