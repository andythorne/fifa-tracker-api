<?php

namespace App\Response;

use League\Fractal\Resource\ResourceInterface;
use Pagerfanta\Pagerfanta;

class FractalResponse
{
    /** @var ResourceInterface */
    private $data;

    /** @var Pagerfanta|null */
    private $pagination;

    public function __construct(ResourceInterface $data, ?Pagerfanta $pagination = null)
    {
        $this->data = $data;
        $this->pagination = $pagination;
    }

    public function getData(): ResourceInterface
    {
        return $this->data;
    }

    public function getPagination(): Pagerfanta
    {
        return $this->pagination;
    }
}
