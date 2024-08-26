<?php

namespace App\Entity;

enum FlashType: string
{
    case STANDARD = 'standard';
    case GOLDEN_TICKET = 'golden_ticket';
    case NULL_TICKET = 'null_ticket';
}
