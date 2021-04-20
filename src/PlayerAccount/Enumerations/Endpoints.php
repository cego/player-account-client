<?php

namespace Cego\PlayerAccount\Enumerations;

class Endpoints
{
    const UPDATE = 'api/v1/user/%s';
    const ADD_FLAG = 'api/v1/user/%s/flag/add';
    const REMOVE_FLAG = 'api/v1/user/%s/flag/remove';
    const INCIDENT = 'api/v1/user/%s/incident';
}
