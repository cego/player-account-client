<?php

namespace Cego\PlayerAccount\Enumerations;

class Endpoints
{
    public const UPDATE = 'api/v1/user/%s';
    public const ADD_FLAG = 'api/v1/user/%s/flag/add';
    public const BATCH_ADD_FLAG = 'api/v1/user/flag/add';
    public const BATCH_REMOVE_FLAG = 'api/v1/user/flag/remove';
    public const REMOVE_FLAG = 'api/v1/user/%s/flag/remove';
    public const INCIDENT = 'api/v1/user/%s/incident';
    public const BATCH_INCIDENT = 'api/v1/user/incident';
    public const USERS = 'api/v1/user';
    public const USER = 'api/v1/user/%s';
}
