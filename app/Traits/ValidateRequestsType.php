<?php

namespace App\Traits;

trait validateRequestsType
{
    private function validateRequestsType(String $type):bool
    {
        return in_array($type,['received','sent']);
    }
}
