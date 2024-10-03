<?php

namespace App\Entity;

enum FlashType: string
{
    case STANDARD = 'standard';
    case GOLDEN_TICKET = 'golden_ticket';
    case FATAL_ERROR = 'fatal_error';
}
