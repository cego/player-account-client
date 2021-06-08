<?php

namespace Cego\PlayerAccount\Enumerations;

class Endpoints
{
    public const UPDATE = 'api/v1/user/%s';
    public const ADD_FLAG = 'api/v1/user/%s/flag/add';
    public const REMOVE_FLAG = 'api/v1/user/%s/flag/remove';
    public const INCIDENT = 'api/v1/user/%s/incident';
    public const USERS = 'api/v1/user';
}
