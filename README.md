## Mansion Wallet

This is a simple e-wallet web application based on the requirements proposed in the assessment document.
The base of the project is Laravel 6, combined with Bootstrap and jQuery. The project was then hosted on heroku,
as it provided a fast, and freе app deployment interface -> http://mansion-wallet.herokuapp.com/.

### Wallet Specifications

The wallet has a base currency in USD. Initially it is given 50 USD bonus investment. Users can deposit and
withdraw in EUR, GBP and USD currencies. There is a 0.03% fee for deposits and 0.3% for withdraws. The maximum deposit fee is
5 EUR (5.55 USD, 4,23 GBP) and the minimum withdraw fee is 0.50 EUR (0.55 USD, 0.42 GBP). For now the user can 
only change the wallet's name. See upcoming improvements [here](#improvements).

### Set-up for local use

The app requires git, laravel, mySQL and composer to run locally. Follow these steps to ensure correct system usage:

1) The first step is to clone the git repo locally on your PC -> `git clone https://github.com/Welith/mansion-wallet.git`
2) After installing [composer](https://getcomposer.org/download/) we will install all of the app's modules ->
`
composer install && composer update
` 
3) Next, we will migrate and seed our database (use [XAMPP](https://www.apachefriends.org/index.html) for Windows/Linux or [MAMP](https://www.mamp.info/en/downloads/) to install MySQL) ->
`php artisan migrate && php artisan db:seed`
4) Finally, we run the currency exchange script to get the latest [currencies](https://exchangeratesapi.io/) -> `php artisan schedule:run`.
5) Type `php artisan serve` and go to http://localhost:3000.


### Improvements

For now the wallet has only one base currency, where for the next update EUR and GBP base currencies will be
incorporated. A user profile dashboard will be implemented, containing graphs regarding deposit/withdraw activity.
An admin panel will be added for user control and system monitoring.
