<?php

namespace App\Request\Search;

use App\Request\ParamConverter\DTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchRequest implements DTOInterface
{
    /**
     * @var int|string|null
     *
     * @Assert\GreaterThanOrEqual(1)
     */
    private $page = 1;

    /**
     * @var int|string|null
     *
     * @Assert\GreaterThanOrEqual(10)
     * @Assert\LessThanOrEqual(100)
     */
    private $pageSize = 10;

    public static function normalizePayload(array $payload) {
        return [
            'page' => (int) $payload['page'] ?? 1,
            'pageSize' => (int) $payload['pageSize'] ?? 10,
        ];
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage($page): void
    {
        if (!$page) {
            return;
        }

        $this->page = (int) $page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setPageSize($pageSize): void
    {
        if (!$pageSize) {
            return;
        }

        $this->pageSize = (int) $pageSize;
    }
}
