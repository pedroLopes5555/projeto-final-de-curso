# projeto-final-de-curso


RUNING BACKEND DOCKER:
Running the backend on a server with an unknown IP address will not enable connectivity to the Azure SQL database due to firewall restrictions.






Greenhouse Automation API Documentation
This document outlines the REST API endpoints provided by the Greenhouse Automation system.
The base URL for all endpoints is: https://hydrogrowthmanager.azurewebsites.net/

Endpoints
Frontend Calls
1. Set Desired Value
URL: /automation/SetDesiredValue
Method: POST
Description: Sets the desired value for a specific container.
Request Body:
Json:
{
"ContainerId": "string",
"DesiredValue": float,
"ValueType": "ReadingTypeEnum"
}
Example:
{
    "ContainerId": "B8BA1960-4275-415B-9AAF-9858E32FCA9E",
    "ValueType": 1,
    "DesiredValue": 200
}

Response: Returns 200 OK upon successful setting of desired value.

2. Request User Containers
URL: /automation/RequestUserContainers
Method: POST
Description: Retrieves containers associated with a specific user.
Request Body:
Json:
"string"
Example:
"8EB08444-2B3A-4BDD-A85E-90337AAB11F1"

Response: Returns a JSON array containing information about user's containers.


4. Request Container Values
URL: /automation/RequestContainerValues
Method: POST
Description: Retrieves the current values of a specific container.
Request Body:
Json:
"string"
Example:
"0EF9BFD1-18B9-4A11-B83E-2E265089FD1A"

Response: Returns a JSON array containing current values of the specified container.

5. Request Container Desired Values
URL: /automation/RequestContainerDesiredValues
Method: POST
Description: Retrieves the desired values configured for a specific container.
Request Body:
Json:
"string"
Example:
"0EF9BFD1-18B9-4A11-B83E-2E265089FD1A"

Response: Returns a JSON array containing desired values of the specified container.


6. Request Container Microcontrollers
URL: /automation/RequestContainerMicrocontrollers
Method: POST
Description: Retrieves microcontrollers associated with a specific container.
Request Body:
Json
"string"
Example:
"0EF9BFD1-18B9-4A11-B83E-2E265089FD1A"

Response: Returns a JSON array containing information about microcontrollers associated with the specified container.



Microcontroller Calls
1. Update Value
URL: /microcontroller/UpdateValue
Method: POST
Description: Updates the reading value of a microcontroller.
Request Body:
Json:
{
    "MicrocontrollerId": "string",
    "ValueType": "ReadingTypeEnum",
    "Value": float
}
Example:
{
    "microcontrollerId": "119c2ddc-a233-4787-95d4-99a7474feaa4",
    "valueType": 2,
    "value": 1000
}

Response: Returns 200 OK upon successful update of the microcontroller value.


2. Get Desired Value
URL: /microcontroller/GetDesiredValue
Method: POST
Description: Retrieves the desired value configured for a microcontroller.
Request Body:
Json
{
    "MicrocontrollerId": "string",
    "ValueType": "ReadingTypeEnum"
}
Example:
{
    "microcontrollerId": "34:85:18:7B:0E:C8",
    "valueType": 1
}

Response: Returns the desired value of the specified microcontroller in JSON format.


4. Turn On Relay
URL: /microcontroller/TurnOnRelay
Method: POST
Description: Turns on a relay connected to a microcontroller.
Request Body:
Json
{
    "MicrocontrollerId": "string",
    "RelayType": "RelayTypeEnum"
}

Example:
{
    "microcontrollerId": "119c2ddc-a233-4787-95d4-99a7474feaa4",
    "RelayType": 2,
}

Response: Returns 200 OK upon successful turning on of the relay.

Data Models
1. SetDesiredValueContent
Description: Represents the data model for setting desired value for a container.
Properties:
ContainerId: (String) Identifier of the container.
DesiredValue: (float) Desired value to be set.
ValueType: (enum) Type of the value to be set (ReadingTypeEnum).

2. UpdateValueJsonContent
Description: Represents the data model for updating microcontroller values.
Properties:
MicrocontrollerId: (String) Identifier of the microcontroller.
ValueType: (enum) Type of the value to be updated (ReadingTypeEnum).
Value: (float) New value to be set.

3. RequestDesiredValueJsonContent
Description: Represents the data model for requesting desired value of a microcontroller.
Properties:
MicrocontrollerId: (String) Identifier of the microcontroller.
ValueType: (enum) Type of the value (ReadingTypeEnum).

4. ChangeRelayStateJsonContent
Description: Represents the data model for turning on a relay connected to a microcontroller.
Properties:
MicrocontrollerId: (String) Identifier of the microcontroller.
RelayType: (enum) Type of the relay (RelayTypeEnum).

5. MicrocontrollerValueJsonContent
Description: Represents the data model for current value of a microcontroller.
Properties:
MicrocontrollerId: (String) Identifier of the microcontroller.
ValueType: (enum) Type of the value (ReadingTypeEnum).
Value: (float) Current value.

6. ReadingTypeEnum
Description: Enumerates different types of readings supported.
Possible Values:
PH / 1
EL / 2
TEMPERATURE / 4

7. RelayTypeEnum
Description: Enumerates different types of relay configurations.
Possible Values:
BASIC_SOLUTION / 1
ACID_SOLUTION / 2
CE_SOLUTION / 4

Errors
400 Bad Request: If the request is malformed or missing required parameters.
404 Not Found: If the requested resource (container, user, etc.) is not found.
500 Internal Server Error: If an unexpected error occurs on the server.






