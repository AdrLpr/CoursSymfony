<?php

declare(strict_types=1);

namespace App\DTO;


class SearchBookAdmin
{

    public ?string $title = null;

    public int $limit = 20;

    public int $page = 1;

    public string $sortBy = 'id';

    public string $direction = 'ASC';

    public ?string $authorName = null;

    public int $prixMin = 0;

    public int $prixMax = 9999;

}
