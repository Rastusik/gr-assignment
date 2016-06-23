GR Assignment
======

There are some minor changes present compared to the assignment, e.g. login with username, not with id.
Also a user unfriendly thing - you have to manually create a password for a newly created user in the edit view.

## How to run

You need to have [composer](https://getcomposer.org/) installed to run this code. The code is written in PHP 7 
and has been tested on Postgres 9.5

```bash

# this step will ask you for the database credentials
composer install

# creates database tables
./bin/console doctrine:migrations:migrate 
```

# OR import the /gr.sql file

In this case the admin user password is admin.