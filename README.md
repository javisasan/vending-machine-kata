# vending-machine-kata

## What is this repo about

The idea of this repository is to solve the Vending Machine Kata, modelling a vending machine which takes money and gives you items.

The vending machine accepts money in the form of coins of values 0.05, 0.10, 0.25 and 1.

The machine can sell three items: Water = 0.65, Juice = 1.00, Soda = 1.50.

## Some decisions

Decided to use PHP and Symfony in order to create an Hexagonal application with a couple of Bounded Contexts, one for `Vending` (machine) and another one for the `SharedKernel`.

The main `Vending` bounded context has the three layers:

- Application: in charge of receiving the requests from Infrastructure and orchestrate calls to Domain (through interfaces) and return results to Infrastructure (if it's a Query).
- Domain: Has the Business Logic and decisions regarding it. Also has the interfaces needed in the other two layers.
- Infrastructure: Receives the requests from outside through the Controller, generates a Command or Query which is dispatched and returns the result to outside.

Code has been covered with Unit Tests using [PHPUnit](https://phpunit.de/) and [Prophecy](https://github.com/phpspec/prophecy-phpunit).

## How to run the application

### Raise the environment

This repository makes use of the `make` tool for helping with the most common actions.

In order to raise the application, clone the repository wherever you want and execute the following command:

```
make dev
```

(Or use Docker Compose command, `docker compose up -d`)

Once the command execution finishes, you should be able to use the application.

There are other `make` commands available:

| Command      | Used for |
| ----------- | ----------- |
| make help | Show a brief help of all the commands available |
| make dev | compose up dev environment and apply JSON fixtures |
| make nodev | compose down dev environment |
| make shell | enter the PHP container |
| make unit | run unit tests |
| make cache | execute cache:clear |
| make tree | show git log tree |
| make purge | removes ALL docker containers, images and volumes in dev machine |
| make logs | show logs from PHP container (-f mode) |

### Execute Tests

In order to execute Unit Tests, you can use the following command (as described above):

```
make unit
```

### Test the Application

Once the application has started, it exposes port `80` for receiving requests.

> :warning: If you have any other service listening in port `80`, you will have to stop it.

The base URL for the application is `http://localhost`, and these are the available endpoints:

|  | Endpoint | Use Case | Parameters |
| ----------- | ----------- | ----------- | ----------- |
| GET | / | Shows the Status of the Vending Machine: Inserted Credit, Items and Exchange. | None |
| POST | /service | Services the machine with the given Items Inventory and Exchange. | JSON data |
| GET | /credit/{coin} | User inserts a coin. | The `value` of the `coin` |
| GET | /refund | Refunds the coins inserted to the User. | None |
| GET | /buy/{selector} | Buys an Item. | The `item` selector |

This application can be tested using [Postman](https://www.postman.com/) or a similar tool, but you can also use `curl` from the command line to test it.

### Testing / (status) Endpoint

CURL syntax example:

```
curl --location 'http://localhost'
```

Will return a JSON with all the state of the machine:
- Total amount of inserted coins
- Items available, their price and their quantity
- Exchange available, coins and quantity

### Testing /service Endpoint

CURL syntax example:

```
curl --location 'http://localhost/service' \
--header 'Content-Type: application/json' \
--data '{
    "inventory": [
        {
            "selector": "water",
            "price": 0.65,
            "quantity": 4
        },
        {
            "selector": "juice",
            "price": 1.00,
            "quantity": 5
        },
        {
            "selector": "soda",
            "price": 1.50,
            "quantity": 6
        }
    ],
    "exchange": [
        {
            "value": 1,
            "quantity": 3
        },
        {
            "value": 0.25,
            "quantity": 4
        },
        {
            "value": 0.1,
            "quantity": 5
        },
        {
            "value": 0.05,
            "quantity": 7
        }
    ]
}'
```

After executing this query, the machine will have products and exchange.

### Testing /credit/{coin} Endpoint

CURL syntax example:

```
curl --location 'http://localhost/credit/0.25'
```

User by the User in order to insert a coin.

### Testing /refund Endpoint

CURL syntax example:

```
curl --location 'http://localhost/refund'
```

The User uses this endpoint to get back all the inserted coins.

### Testing /buy/{selector} Endpoint

CURL syntax example:

```
curl --location 'http://localhost/buy/soda'
```

Buys the product, and returns the product and a list of coins (change).
