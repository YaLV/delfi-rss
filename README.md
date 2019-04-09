## Delfi RSS reader with user registration ##


### Installation ###
* Set up Database
* run command: __composer install__
* copy .env.example as .env (if composer fails to do so)
* run command: __php artisan key:generate__
* edit .env file and specify DB connection data:
    * DB_CONNECTION
    * DB_HOST
    * DB_PORT
    * DB_DATABASE
    * DB_USERNAME
    * DB_PASSWORD
* edit .env file and specify mail server configuration:
    * MAIL_DRIVER
    * MAIL_HOST
    * MAIL_PORT
    * MAIL_USERNAME
    * MAIL_PASSWORD
    * MAIL_ENCRYPTION
    * MAIL_FROM_ADDRESS
    * MAIL_FROM_NAME
* edit .env file and specify APP_URL
* run command: __php artisan migrate__
* (optional) edit config/settings.php for configuration