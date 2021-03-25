# Player Account Client
This package is used as a standardized way to communicate with the Player Account service. The client implements
a fluent interface.
## Client setup
```php
// Getting a client instance
$playerAccount = PlayerAccount::create($playerAccountBaseURL);

// Setting basic auth credentials
$playerAccount->auth($username, $password);

// Telling the client to use Request Insurance
$playerAccount->useRequestInsurance();
```

## Usage
```php
// Setting payload
$playerAccount->withPayload(array $payload);

// Triggering a user incident
$playerAccount->withPayload([
    'type'          => 'TheIncidentType',
    'admin_user_id' => 1234,
    'reason'        => 'A really good reason'
])->incident($userId);

// Updating attributes of a user
$playerAccount->withPayload([
    'admin_user_id'    => 1234,
    'user_attribute_1' => 'Becomes this',
    'user_attribute_2' => 'Becomes that'
])->update($userId);
```

Project was initially created by:

- Thomas Wogelius (THWO)
