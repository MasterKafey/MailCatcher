# Mail Catcher - Email Sending Test Tool

Mail Catcher is designed to streamline email testing in your development workflow. This advanced tool not only captures emails sent from your applications but also allows the creation of multiple inboxes, each associated with different projects. Perfect for developers and testers who need a comprehensive and flexible email testing environment.

## Features

- Multiple Inboxes: Create separate inboxes for different projects, enhancing organization and testing accuracy.

- Real-Time Email Capture: Intercepts and stores emails for instant review.

- Intuitive Web Interface: View captured emails in a user-friendly web interface, supporting HTML emails.

- Full Compatibility with Symfony 6.4: Tailored to integrate seamlessly with the latest Symfony framework.

- Easy Docker Setup: Quick and straightforward installation and configuration using Docker.

- Environment Configuration Flexibility: Customize your setup with a comprehensive list of environment variables.

## Database creation and migration.
When the application detects the need to create or update your database, the web interface will block you on the maintenance page. It will ask you to define the "UPDATE_CODE" environment variable if you have not already done so and will make the necessary changes if you give it the "UPDATE_CODE".

You can also just run the "composer update-database" command in the container at the root of the project if you don't want to work with the "UPDATE_CODE"

## Environment variables

### Database Configuration
You need a SQL (MySQL, MariaDB, PostgreSQL) database to store data likes emails, user's accounts, projects.<br><br>

#### DATABASE_NAME
The name of the database used by the application.

Default: *mail_catcher*

Exemple: 
- *DATABASE_NAME=mail_catcher*
<br><br>
#### DATABASE_HOST
The hostname or IP address of the database server.

Default: *localhost*

Exemple:
- *DATABASE_HOST=192.168.1.1*
- *DATABASE_HOST=mysql_container_name*
<br><br>
#### DATABASE_PORT
The port number on which the database server is listening.

Default: *3306*

Exemple:
- *DATABASE_PORT=5432*
<br><br>
#### DATABASE_USER
The username for accessing the database.

Default: *mail_catcher*

Exemples: 
- *DATABASE_USER=my_super_username*
- *DATABASE_USER=root*
<br><br>
#### DATABASE_PASSWORD
The password for the database user.
No default value

Exemple:
- *DATABASE_PASSWORD=azerty*
<br><br>
#### DATABASE_TYPE 
The type of database being used (mysql or postgresql).

Default: *mysql*

Exemple:
- *DATABASE_TYPE=postgresql*
<br><br>
#### DATABASE_VERSION
The version of the database server.

Default: *5.7.40*

Exemple:
- *DATABASE_VERSION=18*
<br><br>
### SMPT Server configuration
#### SMPT_ADDRESS
Specifies the address and port for the SMTP server.
Used as uri parameter [here](https://github.com/reactphp/socket/blob/1.x/src/SocketServer.php).

Default: *0.0.0.0:2525*

Exemple: 
- *SMTP_ADDRESS=127.0.0.1:2525*
<br><br>
#### DOMAIN_NAME
Used in the SMTP server's welcome message when establishing a connection.

Default: *example.com*

Exemple: 
- *DOMAIN_NAME=google.com*
<br><br>
#### UPDATE_CODE
A special code used to trigger database migrations via the web interface.

No default value

Exemple: 
- *UPDATE_CODE=abc123*
<br><br>
#### MAILER_DSN
Configures the mail transport (DSN) for sending emails. Set this if you want to send emails after retrieving them. You will need to click on the send button of the email you want to send

Default value: *null://null* (disable this feature)

Exemple: 
- *MAIL_DSN=smtp://127.0.0.1:2525*
<br><br>
## docker-compose.yaml exemple

```yaml
version: '3.7'

services:
    app:
        container_name: app.mail-catcher
        image: masterkafei/mail-catcher:latest
        ports:
            - 80:80
            - 2525:2525
        environment:
            DATABASE_HOST: database
            DATABASE_NAME: ${DATABASE_NAME:-mail_catcher}
            DATABASE_USER: ${DATABASE_USER:-mail_catcher}
            DATABASE_PASSWORD: ${DATABASE_PASSWORD}
            UPDATE_CODE: ${UPDATE_CODE:-}
        networks:
            - database

    database:
        container_name: database.mail-catcher
        image: mariadb:lts
        ports:
            - 3306:3306
        environment:
            MARIADB_RANDOM_ROOT_PASSWORD: true
            MARIADB_DATABASE: ${DATABASE_NAME:-mail_catcher}
            MARIADB_USER: ${DATABASE_USER:-mail_catcher}
            MARIADB_PASSWORD: ${DATABASE_PASSWORD}
        networks:
            - database

networks:
    database:
```
