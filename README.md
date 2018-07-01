# Application for managing personal budget (costs of everyday life).
>Autor: Adrian Jelonek |  E-mail: ajelonek(at)gmail.com

## Project is finished. 
You can visit online version: [www.e-budzet.pl](https://www.e-budzet.pl "E-budzet.pl")
### The whole idea:
1. You have to register and be logged in,
2. You can add your recent expenses to database (e.g. groceries receipt) via simple form,
3. You are allowed to add expenses on everyday basis,
4. Application summarizes your expenses in table,
4. Application's dashboard helps you embrace costs of everyday life,
6. Your expenses are stored in database until you decide to delete account.

### Technologies behind:
Project is powered by WordPress. WP is mainly used for user registration process. No default themes are used in this project. Only one external plugin is installed (WP MAIL SMTP), allowing WordPress to send mails.

- PHP OOP is based on simplified Active Directory pattern.
- MySQL database stores data.
- CanvasJS library is used for dashboard creation.
- Bootstrap is used for styling purposes (incl. RWD).
- SASS is used for own styles.
- GULP is used for automation of working process.

### You can search for code samples in [wp-content/themes/basic](../master/wp-content/themes/basic). For example:
- Class directory - there is a simple CRUD,
- functions.php file - there are WP actions and form validations,
- page-formularz-wydatkow.php file - we add expenses here,
- page-zestawienie-wydatkow.php file - we search for expenses here.

Files with "-view" extension are for separation of logic from presentation.
