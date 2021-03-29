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
// Triggering a user incident
$playerAccount->incident($userId, [
    'type'          => 'TheIncidentType',
    'admin_user_id' => $adminUserId,
    'reason'        => 'A really good reason'
]);

// Updating attributes of a user
$playerAccount->update($userId, [
    'admin_user_id'    => $adminUserId,
    'user_attribute_1' => 'Becomes this',
    'user_attribute_2' => 'Becomes that'
]);
```

Project was initially created by:

- Thomas Wogelius (THWO)
