<?php

namespace IShopClient\Model;

class Producer
{
    private int $id;
    private string $name;
    private string $site_url;
    private string $logo_filename;
    private int $ordering;
    private string $source_id;

    public function __construct(
        int $id,
        string $name,
        string $site_url,
        string $logo_filename,
        int $ordering,
        string $source_id
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->site_url = $site_url;
        $this->logo_filename = $logo_filename;
        $this->ordering = $ordering;
        $this->source_id = $source_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSiteUrl(): string
    {
        return $this->site_url;
    }

    public function getLogoFilename(): string
    {
        return $this->logo_filename;
    }

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    public function getSourceId(): string
    {
        return $this->source_id;
    }
}
