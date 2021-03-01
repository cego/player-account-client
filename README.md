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
$playerAccount->incident($userId, $incident);
```


Project was initially created by:

- Thomas Wogelius (THWO)
