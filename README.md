# Micro component for getting JWT

What's inside?
---------------
Up new environment:

`make install`

See all make commands

`make help`

Full test circle

`make test`

Execute tests:

`tests-unit` 
`tests-integration`

Static code analysis:

`make style`

Code style fixer:

`make coding-standards-fixer`

Code style checker (PHP CS Fixer and PHP_CodeSniffer):

`make coding-standards`

Psalm is a static analysis tool for finding errors in PHP applications, built on top of PHP Parser:

`make psalm`

PHPStan focuses on finding errors in your code without actually running it.

`make phpstan`

**Generate ssl keys**

For generate ssl keys use symfony secret (app.secret) for pasphrase, from config/parameters.yaml file

```  
  $ mkdir -p var/jwt
  $ openssl genrsa -out var/jwt/private.pem -aes256 4096
  $ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```
