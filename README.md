# REST API

### Authorization
Every endpoint (except /login and /register) requires for you to pass the authentication token of type bearer in the Authorization header.
Example: 
> Authorization: bearer kl2jlk332lkj3kjlk3f3f90uf38ugoaseg

### POST /user/register
Example request: 
> {"name":"USER","pass":123}

### POST /user/login
Returns an authorization token.
Example request: 
> {"name":"USER","pass":123}

### GET /user/devices/all
Returns a JSON string of authorized user's devices,their UUID's and associated data.

### POST /device/new
Creates a new device with or without given parameters (fields) for the authorized user.
Example request:
> {"name":"Device 1","parameters":["temperature","humidity"]}

### DELETE /device/delete
Deletes device with a given name.
Example request: 
> {"name":"Device 1"}

### PUT /device/addparameter
Adds one or more parameters to an existing device.
Example request:
> {"name":"Device 1","parameters":["current","power"]}

### DELETE /device/deleteparameter
Deletes one or more parameters from an existing device.
Example request:
> {"name":"Device 1","parameters":["current","power"]}

### PUT /device/putdata
Appends data to device's existing parameter/parameters data array.
Example request:
> {"name":"Living Room Thermometer","temperature":13,"humidity":70}

### GET /device/getdata/{device+name}
Returns JSON data string for given device.

