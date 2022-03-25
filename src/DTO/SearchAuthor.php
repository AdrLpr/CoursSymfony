<?php

declare(strict_types=1);

namespace App\DTO;

class SearchAuthor
{
    public ?string $name = null;

    public int $limit = 20;

    public int $page = 1;

    public string $sortBy = 'id';

    public string $direction = 'ASC';

}