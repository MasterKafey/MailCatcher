<?php

namespace App\Entity;

enum MemberRole: string
{
    case VIEWER = 'VIEWER';
    case EDITOR = 'EDITOR';
    case OWNER = 'OWNER';
}