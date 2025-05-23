<?php

namespace App\DTO;

class DomainResultDTO
{
    public string $domain;
    public string $status;

    public function __construct(string $domain, string $status)
    {
        $this->domain = $domain;
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'status' => $this->status,
        ];
    }
}
