
# Hospital Back-End

## Requesitos

* PHP 8.2 ou superior
* MySQL 8 ou superior
* Composer

## Como rodar o projeto baixado
git clone git@github.com:PHenriquedossantos/hospital.git

## .ENV Variáveis 
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME= user name aqui
MAIL_PASSWORD= password aqui
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=email aqui
MAIL_FROM_NAME="${APP_NAME}"
```


Rodar as migrations
```
php artisan migrate
```

INICIAR O SERVER
```
php artisan server
```
## ROTAS

## LOGIN

Deverá ser enviado uma requisição do tipo POST para a rota login
com o seguinte json 
```
http://127.0.0.1:8000/api/login

```
```
{
  "email": "AppHosital_001@api.com",
  "password": "12345678"
}
```
```
http://127.0.0.1:8000/api/login

```
Response:
```
{
  "status_code": 200,
  "message": "success",
  "data": {
    "message": "Código de verificação enviado. Verifique seu e-mail.",
    "user": "AppHospital",
    "email": "AppHosital_001@api.com",
    "token": "44|FCYvlCgKW93Q3kh10OgKNXCEl0VHOHRXYpFHquoN454c1435"
  }
}
```

Será enviado também um código de acesso para o email do usuário para a autenticação 2fa que deverá ser usado para enviar uma requisição do tipo POST para a seguinte rota junto com o json (token de autenticacao gerado anteriormente juntamento com o code enviado por email)
```
http://127.0.0.1:8000/api/verify-two-factor

```
```
{
    "two_factor_code": "670990"
}
```
Response:
```
{
  "status_code": 200,
  "message": "success",
  "data": {
    "message": "Login bem-sucedido!",
    "token": "45|fQUEt5v603eOSwULzhpmcr8917CsoaFWK3KNOBLu73e5b0c8"
  }
}
```
Com o token da response, poderá ser feito novas requisições para as demais endpoints:

logout deverá ir com um json com email, senha do user, essa rota deverá ir com o bearer: token
```
http://127.0.0.1:8000/api/logout 
```

Register deverá ir com o seguinte json
```
{
    "name": "John Doe",
    "email": "johndsoe@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```
Register
```
http://127.0.0.1:8000/api/register
```
Lista de pacientes, o retorno será um array de pacientes. Essa rota deverá ir com o bearer: token
```
http://localhost:8000/api/pacientes?per_page=10
```
Pesquisa essa rota deverá ir com o bearer: token
```
http://localhost:8000/api/paciente/nome/JOAO
```

```
http://localhost:8000/api/paciente/detalhes/11
```

Importar xls, essa rota deverá ir com o bearer: token mais o arquivo xls.
```
 http://127.0.0.1:8000/api/importar
```
