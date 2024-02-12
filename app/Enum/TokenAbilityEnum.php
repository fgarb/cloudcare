<?php

namespace App\Enum;

/**
 * Map Token abilities for Sanctum to an enum
 */

enum TokenAbilityEnum : string
{
    case ISSUE_ACCESS_TOKEN = 'issue-access-token';
    case ACCESS_API = 'access-api';
}
