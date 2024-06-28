# Greenhouse Automation Project

This project provides an automation system for greenhouse management, featuring a REST API backend developed in Laravel. The system allows users to manage containers and their microcontrollers, configure desired environmental values, and control relays to maintain optimal conditions. The backend is hosted on Azure and connects to an Azure SQL database, while the API supports various endpoints for interacting with the greenhouse system.

## Laravel Setup Guide

This guide provides step-by-step instructions for setting up a Laravel project. For more detailed information, please refer to the official Laravel documentation.

### Requirements

- PHP >= 7.4
- Composer

### Installation

1. **Clone Repository**: Clone this repository to your local machine.
    ```bash
    git clone <repository_url>
    ```
    The **Laravel** is inside of the frontend folder.

2. **Navigate to Project Directory**: Change your current directory to the project directory.
    ```bash
    cd <project_directory>
    ```

3. **Install Dependencies**: Install Composer dependencies.
    ```bash
    composer install
    ```

4. **Copy Environment File**: Create a copy of the `.env.example` file and rename it to `.env`.
    ```bash
    cp .env.example .env
    ```

5. **Generate Application Key**: Generate an application key.
    ```bash
    php artisan key:generate
    ```

6. **Configure Database**: Set up your database connection details in the `.env` file.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

7. **Run Migrations**: Execute database migrations to create the necessary tables.
    ```bash
    php artisan migrate
    ```

### Running the Application

To start a local development server, run the following command:
```bash
php artisan serve
```

Running Backend Docker
To pull and run the backend Docker image, use the following command:
```bash
docker pull lopes5555/greenhouse
docker run -p 8000:8000 lopes5555/greenhouse
```
### Attention: Running the backend on a server with an unknown IP address will not enable connectivity to the Azure SQL database due to firewall restrictions.

Greenhouse Automation API Documentation
This document outlines the REST API endpoints provided by the Greenhouse Automation system. The base URL for all endpoints is: https://hydrogrowthmanager.azurewebsites.net/

### Endpoints

1. **Set Desired Value**

```bash
URL: /automation/SetDesiredValue
Method: POST
Description: Sets the desired value for a specific container.
Request Body:
json
Copiar código
{
    "ContainerId": "string",
    "DesiredValue": float,
    "ValueType": "ReadingTypeEnum"
}
Response: Returns 200 OK upon successful setting of desired value.
```

2. **Request User Containers**

```bash
URL: /automation/RequestUserContainers
Method: POST
Description: Retrieves containers associated with a specific user.
Request Body:
json
{
    "UserId": "string"
}
Response: Returns a JSON array containing information about user's containers.
```

3. **Request Container Values**

```bash
URL: /automation/RequestContainerValues
Method: POST
Description: Retrieves the current values of a specific container.
Request Body:
json
{
    "ContainerId": "string"
}
Response: Returns a JSON array containing current values of the specified container.
```

4. **Request Container Desired Values**

```bash
URL: /automation/RequestContainerDesiredValues
Method: POST
Description: Retrieves the desired values configured for a specific container.
Request Body:
json
{
    "ContainerId": "string"
}
Response: Returns a JSON array containing desired values of the specified container.
```

5. **Request Container Microcontrollers**

```bash
URL: /automation/RequestContainerMicrocontrollers
Method: POST
Description: Retrieves microcontrollers associated with a specific container.
Request Body:
json
{
    "ContainerId": "string"
}
Response: Returns a JSON array containing information about microcontrollers associated with the specified container.
```

6. **Update Value**

```bash
URL: /microcontroller/UpdateValue
Method: POST
Description: Updates the reading value of a microcontroller.
Request Body:
json
{
    "MicrocontrollerId": "string",
    "ValueType": "ReadingTypeEnum",
    "Value": float
}
Response: Returns 200 OK upon successful update of the microcontroller value.
```

7. **Get Desired Value**

```bash
URL: /microcontroller/GetDesiredValue
Method: POST
Description: Retrieves the desired value configured for a microcontroller.
Request Body:
json
{
    "MicrocontrollerId": "string",
    "ValueType": "ReadingTypeEnum"
}
Response: Returns the desired value of the specified microcontroller in JSON format.
```

8. **Turn On Relay**

```bash
URL: /microcontroller/TurnOnRelay
Method: POST
Description: Turns on a relay connected to a microcontroller.
Request Body:
json
{
    "MicrocontrollerId": "string",
    "RelayType": "RelayTypeEnum"
}
Response: Returns 200 OK upon successful turning on of the relay.
```

### Data Models

1. **SetDesiredValueContent**

Description: Represents the data model for setting desired value for a container.
Properties:
- ContainerId: (String) Identifier of the container.
- DesiredValue: (float) Desired value to be set.
- ValueType: (enum) Type of the value to be set (ReadingTypeEnum).

2. **UpdateValueJsonContent**

Description: Represents the data model for updating microcontroller values.
Properties:
- MicrocontrollerId: (String) Identifier of the microcontroller.
- ValueType: (enum) Type of the value to be updated (ReadingTypeEnum).
- Value: (float) New value to be set.

3. **RequestDesiredValueJsonContent**

Description: Represents the data model for requesting desired value of a microcontroller.
Properties:
- MicrocontrollerId: (String) Identifier of the microcontroller.
- ValueType: (enum) Type of the value (ReadingTypeEnum).

4. **ChangeRelayStateJsonContent**

Description: Represents the data model for turning on a relay connected to a microcontroller.
Properties:
- MicrocontrollerId: (String) Identifier of the microcontroller.

5. **MicrocontrollerValueJsonContent**

Description: Represents the data model for current value of a microcontroller.
Properties:
- MicrocontrollerId: (String) Identifier of the microcontroller.
- ValueType: (enum) Type of the value (ReadingTypeEnum).
- Value: (float) Current value.

### Enumerations

1. **ReadingTypeEnum**

Description: Enumerates different types of readings supported.
Possible Values:
- PH / 1
- EL / 2
- TEMPERATURE / 4

2. **RelayTypeEnum**

Description: Enumerates different types of relay configurations.
Possible Values:
- BASIC_SOLUTION / 1
- ACID_SOLUTION / 2
- CE_SOLUTION / 4

### Errors

- 400 Bad Request: If the request is malformed or missing required parameters.
- 404 Not Found: If the requested resource (container, user, etc.) is not found.
- 500 Internal Server Error: If an unexpected error occurs on the server.

