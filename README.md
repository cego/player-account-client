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
// Updating attributes of a user
$playerAccount->update($userId, [
    'admin_user_id'    => 123,
    'user_attribute_1' => 'Becomes this',
    'user_attribute_2' => 'Becomes that'
]);

// Adding a flag to a user
$playerAccount->addFlag($userId, [
    'type'          => 'TheFlagType',
    'admin_user_id' => 123,
    'reason'        => 'The player needs the flag'
]);

// Removing a flag from a user
$playerAccount->removeFlag($userId, [
    'type'          => 'TheFlagType',
    'admin_user_id' => 123,
    'reason'        => 'The player must have the flag removed'
]);

// Triggering a user incident
$playerAccount->incident($userId, [
    'type'          => 'TheIncidentType',
    'admin_user_id' => 123,
    'reason'        => 'Something happened that triggered the incident'
]);
```

Project was initially created by:

- Thomas Wogelius (THWO)
