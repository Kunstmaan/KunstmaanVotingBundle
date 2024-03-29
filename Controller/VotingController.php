<?php

namespace Kunstmaan\VotingBundle\Controller;

use Kunstmaan\VotingBundle\Event\Events;
use Kunstmaan\VotingBundle\Event\Facebook\FacebookLikeEvent;
use Kunstmaan\VotingBundle\Event\Facebook\FacebookSendEvent;
use Kunstmaan\VotingBundle\Event\LinkedIn\LinkedInShareEvent;
use Kunstmaan\VotingBundle\Event\UpDown\DownVoteEvent;
use Kunstmaan\VotingBundle\Event\UpDown\UpVoteEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @final since 5.9
 */
class VotingController extends Controller
{
    /**
     * @Route("/voting-upvote", name="voting_upvote")
     * @Template("@KunstmaanVoting/UpDown/voted.html.twig")
     */
    public function upVoteAction(Request $request)
    {
        $reference = $request->get('reference');
        $value = $request->get('value');
        $this->dispatch(new UpVoteEvent($request, $reference, $value), Events::VOTE_UP);
    }

    /**
     * @Route("/voting-downvote", name="voting_downvote")
     * @Template("@KunstmaanVoting/UpDown/voted.html.twig")
     */
    public function downVoteAction(Request $request)
    {
        $reference = $request->get('reference');
        $value = $request->get('value');
        $this->dispatch(new DownVoteEvent($request, $reference, $value), Events::VOTE_DOWN);
    }

    /**
     * @Route("/voting-facebooklike", name="voting_facebooklike")
     */
    public function facebookLikeAction(Request $request)
    {
        $response = $request->get('response');
        $value = $request->get('value');
        $this->dispatch(new FacebookLikeEvent($request, $response, $value), Events::FACEBOOK_LIKE);
    }

    /**
     * @Route("/voting-facebooksend", name="voting_facebooksend")
     */
    public function facebookSendAction(Request $request)
    {
        $response = $request->get('response');
        $value = $request->get('value');
        $this->dispatch(new FacebookSendEvent($request, $response, $value), Events::FACEBOOK_SEND);
    }

    /**
     * @Route("/voting-linkedinshare", name="voting_linkedinshare")
     */
    public function linkedInShareAction(Request $request)
    {
        $reference = $request->get('reference');
        $value = $request->get('value');
        $this->dispatch(new LinkedInShareEvent($request, $reference, $value), Events::LINKEDIN_SHARE);
    }

    /**
     * @param object $event
     *
     * @return object
     */
    private function dispatch($event, string $eventName)
    {
        $eventDispatcher = $this->container->get('event_dispatcher');
        if (class_exists(LegacyEventDispatcherProxy::class)) {
            $eventDispatcher = LegacyEventDispatcherProxy::decorate($eventDispatcher);

            return $eventDispatcher->dispatch($event, $eventName);
        }

        return $eventDispatcher->dispatch($eventName, $event);
    }
}
