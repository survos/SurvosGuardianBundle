<?php

// load and translate news

declare(strict_types=1);

namespace Survos\TheGuardianBundle\Service;

use Guardian\Entity\Content;
use Guardian\Entity\ContentAPIEntity;
use Guardian\Entity\Tags;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Guardian\GuardianAPI;
class TheGuardianService
{
    public function __construct(
        private ?string $apiKey=null,
        private array $config=[],
        private ?GuardianAPI $guardianAPI=null,
        private ?CacheInterface $cache=null,
        private ?LoggerInterface $logger=null,
        private ?SluggerInterface $asciiSlugger=null,
    )
    {
        $this->guardianAPI = new GuardianAPI($this->apiKey);
    }

    public function fetch(ContentAPIEntity $apiEntity)
    {

        ($r = new \ReflectionMethod($apiEntity, 'buildUrl'))
            ->setAccessible(true);
        $url = $r->invoke($apiEntity);
        $key = hash('xxh3', $url);
        $response = $this->cache->get($key, function(CacheItem $item) use ($url, $apiEntity)
        {
            $item->expiresAfter(600);
            $results = $apiEntity->makeApiCall($url);
            return json_decode($results)->response;
        } );
        return $response;
    }


    public function content(?string $q=null)
    {

    }

    public function contentApi(): Content
    {
        return $this->guardianAPI->content();
    }

    public function tagsApi(): Tags
    {
        return $this->guardianAPI->tags();
    }



}
