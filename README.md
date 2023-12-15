# Secret Santa API

## Descrição

O Secret Santa é uma aplicação dedicada à organização de festas de amigo secreto. Ela gerencia informações sobre a festa, lista de participantes e os desejos de cada participante.

## Configuração do ambiente Docker

1. Clone o repositório para o seu ambiente local: 
```git clone git@github.com:GabrielOliveira1996/Secret-Santa.git```

2. Configure um arquivo .env para definir as variáveis de ambiente necessárias. Na raiz do projeto, há um arquivo chamado .env.example. Faça uma cópia desse arquivo e renomeie para .env. Preencha as configurações do banco de dados da seguinte forma:
```
- DB_CONNECTION=mysql
- DB_HOST=db
- DB_PORT=3306
- DB_DATABASE=secret_santa
- DB_USERNAME=root
- DB_PASSWORD=123
```
Nota: Essas configurações de banco de dados são destinadas apenas para uso local durante o desenvolvimento. Elas serão alteradas ao mover para um ambiente de produção.

3. Construa a imagem do Docker. Na raiz do projeto, execute o comando:

```docker compose up -d```

4. Instale as dependências do composer:

- Entre no container usando o comando na raiz do projeto:
```docker compose exec app bash```

- Em seguida, use o comando: 
```composer install```

5. Execute as migrações para criar as tabelas no banco de dados:

- Já dentro do container use o comando: 
```php artisan migrate```

6. Inicie o servidor:

- Já dentro do container use o comando: 
```php artisan serve --host 0.0.0.0```

Agora, o servidor estará em execução e pronto para o uso da API.

## Rotas API

Rota para criação de festa:

```Route::POST - url(http://localhost:8700/api/create-party)```
