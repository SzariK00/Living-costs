# Application for managing personal budget (costs of everyday life).
>Autor: Adrian Jelonek |  E-mail: ajelonek(at)gmail.com

## Project is finished. 
You can visit online version: [www.e-budzet.pl](https://www.e-budzet.pl "E-budzet.pl")
### The whole idea:
1. First of all you have to register,
2. Then you will be able to add your recent expense (e.g. groceries receipt) via simple form,
3. Of course you are able to add expenses on everyday basis,
4. Your expenses are stored in a application database,
5. Application summarizes your expenses in table, helping you to indicate expenses types which should be changed in future,
6. Application simple dashboard helps you embrace costs of everyday life.

### Technologies behind:
Project is powered by WordPress. WP is mainly used for user registration process. No default themes or WP plugins are used in this project.

- PHP OOP is based on simplified Active Directory pattern.
- CanvasJS framework is used for dashboard purposes.
- Bootstrap is used for styling purposes (incl. RWD).
- SASS is used for own styles.
- GULP is used for automation of working process.


### You can search for code samples in [wp-content/themes/basic](/tree/master/wp-content/themes/basic). For example:
- Class directory - there is a simple CRUD,
- functions.php file - there are WP actions and form validations,
- page-formularz-wydatkow.php file - we add expenses here,
- page-zestawienie-wydatkow.php file - we search for expenses here.

Files with "-view" extension are for separation the logic code from presentation code.
