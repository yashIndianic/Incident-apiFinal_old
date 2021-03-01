
##### Requirements #####

* PHP >= 7.4

##Step for setup project

* Clone this repository by typing `git clone https://github.com/yashIndianic/Incident-api.git {YOUR_PROJECT_NAME}` 
* type `cd {YOUR_PROJECT_NAME}`
* type `composer install`
* type `cp env.example .env`

* Update *.env* file :
   * set DB_CONNECTION
   * set DB_DATABASE
   * set DB_USERNAME
   * set DB_PASSWORD
* type `php artisan migrate` to create and populate tables.
* type `php artisan db:seed` to seed the default data.
* type `php artisan serve`.
Now your project is ready.

### POSTMAN COLLECTION ###
* https://www.getpostman.com/collections/78c6202ea8d7488d5438

### Who do I talk to? ###

* **Developer Name:** IndiaNIC