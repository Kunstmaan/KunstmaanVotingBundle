<?php

namespace Kunstmaan\VotingBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Kunstmaan\VotingBundle\Event\EventInterface;
use Kunstmaan\VotingBundle\Event\Facebook\FacebookLikeEvent;
use Kunstmaan\VotingBundle\Event\Facebook\FacebookSendEvent;
use Kunstmaan\VotingBundle\Event\LinkedIn\LinkedInShareEvent;
use Kunstmaan\VotingBundle\Event\UpDown\DownVoteEvent;
use Kunstmaan\VotingBundle\Event\UpDown\UpVoteEvent;

/**
 * Helper class get repository for an event
 */
class RepositoryResolver
{
    /**
     * Entity manager
     */
    protected $em;

    /**
     * @param object $em entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return repository for event
     *
     * @param EventInterface $event event
     *
     * @return EntityRepository
     */
    public function getRepositoryForEvent($event)
    {
        $repository = null;

        if ($event instanceof DownVoteEvent) {
            $repository = $this->getRepository('Kunstmaan\VotingBundle\Entity\UpDown\DownVote');
        }

        if ($event instanceof UpVoteEvent) {
            $repository = $this->getRepository('Kunstmaan\VotingBundle\Entity\UpDown\UpVote');
        }

        if ($event instanceof LinkedInShareEvent) {
            $repository = $this->getRepository('Kunstmaan\VotingBundle\Entity\LinkedIn\LinkedInShare');
        }

        if ($event instanceof FacebookLikeEvent) {
            $repository = $this->getRepository('Kunstmaan\VotingBundle\Entity\Facebook\FacebookLike');
        }

        if ($event instanceof FacebookSendEvent) {
            $repository = $this->getRepository('Kunstmaan\VotingBundle\Entity\Facebook\FacebookSend');
        }

        return $repository;
    }

    /**
     * Return a repository By name
     *
     * @param string $name name
     *
     * @return EntityRepository
     */
    protected function getRepository($name)
    {
        return $this->em->getRepository($name);
    }
}
