<?php

namespace api\enums;

enum PostStatus: int
{
    case DELETED = 0;
    case ACTIVE = 1;
    case MODERATED = 2;
    case ARCHIVED = 3;

}
