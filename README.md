# MyParcel.com - Microservice Skeleton

This is a skeleton application for a microservice that facilitates the communication between the MyParcel.com API and a carrier API.

The skeleton application already handles most of the communication with the MyParcel.com API.
Most of the functionality that needs to be implemented is described in `TODO`s in the application.
It basically comes down to the following:

- Transform existing classes to valid post and get requests for the carrier API.
- Handle responses from the carrier API and map them to the existing classes.

## Content
- [Installation](#installation)
- [Setup](#setup)
- [TODOs](#todos)
- [Commands](#commands)
- [Xdebug](#xdebug)

### Installation
The project uses Docker to run a local development environment. To install Docker, follow the steps in the [wiki](https://staging-wiki.myparcel.com/development/docker/).

### Setup
To setup the project (install composer dependencies, setup database, etc), run the following command:
```bash
./mp.sh setup
```

### TODOs
There are several `TODO` comments added to the codebase to help you get started on what to implement. There are also several tests to check if everything is working as it is supposed to. The endpoint tests are the starting point to check if an endpoint does what is required from the carrier specification.

#### Response Stubs
The endpoint tests should not make actual calls to the carrier. They use a `CarrierApiGatewayMock` class to mock external calls. To make sure the tests do work with actual data, stubs must be created for the carrier responses.

To create the response stub, just make a call to the carrier's endpoint with your favorite API tool (like Postman). Copy and paste the entire response of the carrier into a file inside the `/tests/Stubs` directory. The file name should be equal to the url you accessed with the exception that all `/` characters are replaced with `-`. It should also be prefixed with the http method followed by another `-` and end in `.stub`.

For example:
```
// This request:
GET 'shipping/shipment/235446474/label'

// Would be stored as:
`/tests/Stubs/get-shipping-shipment-235446474-label.stub`
```

### Commands
To start the containers, run:
```bash
./mp.sh up
```

To stop the containers, run:
```bash
./mp.sh stop
```

The following commands are available:
- `./mp.sh` - List running containers (for this repo).
- `./mp.sh up` - Start the containers.
- `./mp.sh down` - Stop the containers.
- `./mp.sh prune` - Prune project volumes.
- `./mp.sh setup` - Setup the development environment.
- `./mp.sh update` - Update the development environment.
- `./mp.sh composer <args>` - Run composer inside the api container.
- `./mp.sh artisan <args>` - Run artisan commands inside the api container.
- `./mp.sh test <args>` - Run phpunit tests.
- `./mp.sh test pudo|shipment|status` - Test if one of the endpoints is correctly implemented.
- `./mp.sh test skeleton` - Test if the skeleton works correctly.
- `./mp.sh microservice <args>` - Run any command on the carrier container (nginx + php).
- `./mp.sh <args>` - Run any [docker-compose](https://docs.docker.com/compose/reference/overview/) command.

#### Composer commands
A few composer scripts have been defined, you can call these using the following commands:
- `./mp.sh composer check-style` - Check if the code is PSR-2 compliant.
- `./mp.sh composer fix-style` - Automatically fix non-PSR-2 code (not all errors can be automatically fixed).

> **TIP:** You will run many ./mp.sh commands. Alias all the things!
```bash
# ~/.bashrc

alias mp="./mp.sh"
```

### Xdebug
To use Xdebug from your IDE you only have to configure the IDE. The used configuration can be found in `docker/app/conf/xdebug.ini`. Below are the steps to setup **PhpStorm**.

#### PhpStorm setup
1. Go to `Settings -> Languages & Frameworks -> PHP -> Servers` and add a new server.
    1. Set a fancy name.
    2. For host and port use the address you use on your local machine to reach the api.
    3. Check `Use path mappings`
    4. Set the absolute path of the root directory to `/opt/microservice` and press enter.
    5. Apply/OK
2. Go to `Run -> Edit configurations` and add a new `PHP Remote Debug` configuration.
    1. Set a fancy name.
    2. Choose your previously created server.
    3. Set the Ide key to the `XDEBUG_IDE_KEY` in your `.env`.
