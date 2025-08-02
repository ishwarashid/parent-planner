<?php

namespace App\Enums;

enum Role: string
{
    case PARENT = 'parent';
    case CO_PARENT = 'co-parent';
    case NANNY = 'nanny';
    case GRANDPARENT = 'grandparent';
    case GUARDIAN = 'guardian';
}