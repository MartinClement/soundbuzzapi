# soundbuzzAPI
## Simple symfony 4 REST API for soundbuzz plateformes
### Author : Clément Martin - IPSSI ASID12

### Requiered :
- PHP ^7 ( required for symfony 4 )
- Composer ( installing bundles and dependencies )

### Instalation :
- Clone repository via http
```
git clone https://github.com/MartinClement/soundbuzzAPI.git

```

- Clone via ssh
```
git clone git@github.com:MartinClement/soundbuzzAPI.git

```

- Install composer if needed
```
cd <project_dir> 

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

```

- Install dependencies
```
### in your project root

php composer.phar install

```

## Before testing

Don't forget to setup your db and your mailing server to test register validation in the **.env** file.

- Populate the db with fixtures
```
php bin/console doctrine:schema:update
php bin/console doctrine:fixtures:load

```

- Quick run

```
### in your project root

php bin/console server:run

```





