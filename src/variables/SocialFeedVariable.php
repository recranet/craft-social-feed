<?php
/**
 * Social Feed plugin for Craft CMS 3.x
 *
 * Display social media feeds in Craft templates.
 *
 * @link      https://www.elloro.nl
 * @copyright Copyright (c) 2022 Elloro
 */

namespace elloro\socialfeed\variables;

use elloro\socialfeed\SocialFeed;

/**
 * Social Feed Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.socialFeed }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Elloro
 * @package   SocialFeed
 * @since     1.0.0
 */
class SocialFeedVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.socialFeed.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.socialFeed.exampleVariable(twigValue) }}
     *
     * @param mixed $pageId
     * @param array $params
     *
     * @return array
     */
    public function getPosts($pageId, array $params = array()): array
    {
        return SocialFeed::$plugin->socialFeedService->getPosts($pageId, $params);
    }
}
