<?php

namespace App\System;

enum SystemState: int
{
    /** Initial state */
    case BACKLOG = 0;
    /** Intermediate state */
    case TO_DO = 1;
    /** Intermediate state */
    case IN_PROGRESS = 2;
    /** Final state */
    case COMPLETED = 3;
    /** Final state */
    case CANCELED = 4;
}
