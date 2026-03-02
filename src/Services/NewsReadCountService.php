<?php

/**
 * Contao - News most read bundle
 *
 * Created by MEN AT WORK Werbeagentur GmbH
 *
 * @copyright  MEN AT WORK Werbeagentur GmbH 2018
 *
 * @author     Sven Meierhans <meierhans@men-at-work.de>
 * @author     Stefan Heimes <heimes@men-at-work.de>
 */

namespace MenAtWork\NewsMostReadBundle\Services;

use Contao\Database;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class NewsReadCountService
 *
 * @package MenAtWork\NewsMostReadBundle\Services
 */
class NewsReadCountService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Session name.
     */
    const NEWS_COUNT_SESSION_BAG = 'news_count_session_bag';

    /**
     * NewsReadCountService constructor.
     *
     * @param Session $session
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Adds a news id to the session bag which stores all news id's read in this current session.
     *
     * @param int $item The news model id.
     *
     * @return bool Returns true, if the entry was added successfully.
     */
    private function getSession(): ?SessionInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request ? $request->getSession() : null;
    }

    public function add($newsId)
    {
        $session = $this->getSession();
        if (!$session) {
            return false;
        }

        $newsRead = $session->get(self::NEWS_COUNT_SESSION_BAG, []);

        if (in_array($newsId, $newsRead)) {
            return false;
        }

        $newsRead[] = $newsId;
        $session->set(self::NEWS_COUNT_SESSION_BAG, $newsRead);

        return true;
    }

    /**
     * Checks if a given news id exists in the current session bag.
     *
     * @param int $newsId The news model id.
     *
     * @return bool True if the news model id already exists.
     */
    public function hasItem($newsId)
    {
        $session = $this->getSession();
        if (!$session) {
            return false;
        }

        $newsRead = $session->get(self::NEWS_COUNT_SESSION_BAG, []);

        return in_array($newsId, $newsRead);
    }
}
