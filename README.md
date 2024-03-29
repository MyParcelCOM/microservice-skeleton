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
- [Credentials](#credentials)
- [TODOs](#todos)
- [Response Stubs](#response-stubs)
- [Error handling](#error-handling)
- [Carrier specific shipment requirements](#carrier-specific-shipment-requirements)
- [Things to keep in mind](#things-to-keep-in-mind)
- [Commands](#commands)
- [Composer commands](#composer-commands)
- [Xdebug](#xdebug)
- [PhpStorm setup](#phpstorm-setup)
- [Visual Studio Code setup](#visual-studio-code-setup)

### Installation
The project uses Docker to run a local development environment. To install Docker, follow the steps in the [documentation](https://docs.myparcel.com/development/docker/).

### Setup
To setup the project (install composer dependencies, setup database, etc), run the following command:
```bash
./mp.sh setup
```

### Credentials
A few credentials are required to let carriers know who is making the request. The MyParcelCom API sends these credentials to the microservice in the `X-MYPARCELCOM-CREDENTIALS` header. These credentials are carrier specific, and usually consist of an `api-user` and `api-password`, or maybe an `api-key` for the carrier's API. The data in the `X-MYPARCELCOM-CREDENTIALS` header should be formatted in `JSON`, as the `ExtractCredentials` middleware in the microservice automatically converts this `JSON` to an array. It then passes the credentials array on to the implementation of the `CarrierApiGatewayInterface` through the `setCredentials()` method.
Persisting information such as the API url should be set in the `.env` file.

### TODOs
There are several `TODO` comments added to the codebase to help you get started on what to implement. There are also several tests to check if everything is working as it is supposed to. The endpoint tests are the starting point to check if an endpoint does what is required from the carrier specification.

#### Response Stubs
The endpoint tests should not make actual calls to the carrier. They use a `CarrierApiGatewayMock` or `HttpClientMock` class to mock external calls. To make sure the tests do work with actual data, stubs must be created for the carrier responses.

To create the response stub, just make a call to the carrier's endpoint with your favorite API tool (like Postman). Copy and paste the entire response of the carrier into a file inside the `/tests/Stubs` directory. The file name should be equal to the url you accessed with the exception that all `/` characters are replaced with `-`. It should also be prefixed with the http method followed by another `-` and end in `.stub`.

For example:
```
// This request:
GET 'shipping/shipment/235446474/label'

// Would be stored as:
`/tests/Stubs/get-shipping-shipment-235446474-label.stub`
```

### Error handling
Errors from the carrier should be transformed to [JSON API error objects](https://jsonapi.org/format/#error-objects). To get you started, an `AbstractErrorMapper` can be found in `app/Carrier/Errors/Mappers`. This `AbstractErrorMapper` can be extended and called from the `CarrierApiGateway` to parse carrier responses and transform it into exceptions the exception handler can transform to a valid JSON schema response.

### Carrier specific shipment requirements
Carriers tend to have specific requirements for a shipment request. Some carriers might require the shipment request to contain a description while others don't.
To preemptively validate a shipment request based on a set of defined rules, we use the Laravel form request validation.
When building a microservice, these rules should be added to make sure the request to the carrier is valid.
The rules should be added to the `shipmentRules()` method in the `/app/Http/ShipmentRequest.php` file.
For more information on Laravel's form request validation, see their documentation [here](https://laravel.com/docs/5.5/validation#form-request-validation).

#### Sanitization
When we pass `suspend_validation` in the `X-MYPARCELCOM-CREDENTIALS` header, this means that we want to bypass validation and always try to send the request.

It is a good idea to at least perform some sanitization in this case.
These sanitization rules should be added to the `sanitization()` or `sanitizationAfterValidation()` methods in the `/app/Http/ShipmentRequest.php` file.

The `sanitization()` rules will be processed first, after that the `shipmentRules()` will be validated, and finally the `sanitizationAferValidation()` rules will be processed. Why is this useful? You might want to strip spaces from the phone number before validating the length of the field for example.

However, if you need to do more intrusive sanitization, like limiting the amount of characters to the max. allowed characters, then you should do this in `sanitizationAfterValidation()` (because validation should fail if you're **not** using `suspend_validation` in this case).

### Things to keep in mind
- Labels must be returned as a base64 encoded string. If the carrier already returns a base64 encoded string make sure you don't encode it twice. The base64 encoded string should decode to a A6 sized PDF in landscape format. If the label is in portrait orientation rotate it 270 degrees so the top is on the left.
- If the microservice needs to store data (logs, labels, service tables, etc.) make sure to save it in `storage/`.
- Not all carriers have their own endpoint for credential verification. If they don't you can make a harmless request (such as a status update request) to test the credentials for the `validate-credentials` endpoint.
- All time must be set in UTC

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
- `./mp.sh setup` - Setup the development environment.
- `./mp.sh update` - Update the development environment.
- `./mp.sh upgrade` - Upgrade the dependencies.
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
To use Xdebug from your IDE you only have to configure the IDE. The used configuration can be found in `.env.xdebug`.

#### PhpStorm setup
Below are the steps to setup **Xdebug** on **PhpStorm**.

1. Go to `Settings -> Languages & Frameworks -> PHP -> Servers` and add a new server.
    1. Set a fancy name.
    2. For host and port use the address you use on your local machine to reach the api.
    3. Check `Use path mappings`
    4. Set the absolute path of the root directory to `/opt/microservice` and press enter.
    5. Apply/OK
2. Go to `Run -> Edit configurations` and add a new `PHP Remote Debug` configuration.
    1. Set a fancy name.
    2. Choose your previously created server.
    3. Set the IDE key to the `XDEBUG_IDE_KEY` in your `.env.xdebug`.

#### Visual Studio Code setup
Below are the steps to setup **Xdebug** on **Visual Studio Code**.

1. Install the `felixfbecker.php-debug` extension (you can use the integrated marketplace).
2. Go to the `Run` tab (`Ctrl-Shift-D`).
3. Create a `launch.json` configuration file and make it similar to this example:
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug on Docker App",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/opt/app": "${workspaceFolder}"
            },
            "hostname": "localhost",
            "xdebugSettings": {
                "max_data": 65535,
                "show_hidden": 1,
                "max_children": 100,
                "max_depth": 5
            }
        }
    ]
}
```
4. Check your `.env.xdebug` file and modify `XDEBUG_CONFIG` if necessary (the default configuration should work fine for WSL2/Docker).
5. You should be good to go. Start the debugger (`F5`) and test it out.

*Additional notes regarding `launch.json` file: Setting `hostname` to `localhost` is needed for WSL2/Docker at least. All `xdebugSettings` can be found here: https://xdebug.org/docs/dbgp#feature-names*