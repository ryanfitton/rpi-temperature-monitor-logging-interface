# Temperature Monitor web interface and loging for DHT22 sensors

* Author: [Ryan Fitton](https://ryanfitton.co.uk)
* Last updated: 2020/07/13

## Notes
* This project is designed to be run on a Raspberry Pi (I have had this running on a RPi Zero without issues)
* Software required: Apache2, PHP 7, MySQL and Python3 - Read below for instructions on how to install these.
* Built based on an Adafruit compatable DHT22 sensor.

## About this project
* This is a simple project which allows logging of temperature and humidity data from a Raspberry Pi using a DHT22 sensor via CRON, with results displayed in an admin interface.
* Basic PHP and MySQLi code.
* Uses [Bootstrap 4.5.0](https://github.com/twbs/bootstrap/releases/tag/v4.5.0) for admin styling.
* Classes used: [PHP DB Class](https://codeshack.io/super-fast-php-mysql-database-class/) (MIT license) and [php-export-data by Eli Dickinson](http://github.com/elidickinson/php-export-data) (MIT license).
* Simple authentication for admin access using .htpasswd (This project has been designs to be run interally, mainly for hobbyists).
* Automated logging via CRON, a low as one sensor check per minute. Longer intervals can be set via the admin web interface.
* Basic 'install' file: ```/install.php``` has been built for an easy install experience (some terminal commads will be required).

## Install
This project has been tested against the most recent software packages running on [Raspberry Pi OS (May 2020)](https://www.raspberrypi.org/downloads/raspberry-pi-os/).
1. Update your packages and OS: ```sudo apt-get update && sudo apt-get upgrade```
1. Please install a basic web server using Apache2, PHP and MySQL. Optionally also install PHPMyAdmin if you're not comfortable with the MySQLI command line interface. Please use this guide [here](https://projects.raspberrypi.org/en/projects/lamp-web-server-with-wordpress) - follow up to the WordPress install, please stop after this as WordPress is not required for this project.
1. Install GIT:
    * In your terminal, run: ```sudo apt install git```.
1. Change into your Apache2 web directory: ```/var/www/html```.
1. Clone this repo ```git clone git@github.com:ryanfitton/rpi-temperature-monitor-logging-interface.git .``` (include the end full-stop).
1. Create a new empty database table. And add your database details to the ```config.php``` fil
1. Go to your browser, enter the IP address of your RPi and go to ```install.php```. e.g. [http://your_ip_address/install.php](http://your_ip_address/install.php).
1. Follow the steps on this install guide. The install process will install your database tables and prompt you to install further software for interaction with the temperature sensor.
1. After this is done, your will be able to access this Admin interface, e.g. [http://your_ip_address/admin/](http://your_ip_address/admin/).
    * The default .htpasswd authentication logins are:
        * Username: rpi-temp
        * Password: J*eoX3Xo6!0)