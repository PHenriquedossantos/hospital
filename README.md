
# Título do Projeto

## Requesitos

* PHP 8.2 ou superior
* MySQL 8 ou superior
* Composer

## Como rodar o projeto baixado
```
git clone git@github.com:PHenriquedossantos/hospital.git
```
## .ENV
renomei o .example.env na raíz do projeto para .env
O projeto é apenas um teste, basta apenas renomear o .example.env para .env
Em um sistema real o .env não seria exposta dessa forma.

## Inicie o Docker com o seguinte comando
```
Docker-compose up -d

```

Rodar as migrations
```
docker exec -it hospital-php-1 php artisan migrate
                    ou
docker exec -it <nome_ou_id_do_container> /bin/bash
```

## ROTAS

Essa rota deverá ir com  arquivo.xlsx, rota para gravar no banco de dados os dados da planilha
```
POST:  http://localhost:800/api/importar
```

```
GET:  http://127.0.0.1:800/api/paciente/2
{
    "nome": "Maria Souza",
    "hospital": "Hospital Norte",
    "plano_saude": "Plano B"
}
```


