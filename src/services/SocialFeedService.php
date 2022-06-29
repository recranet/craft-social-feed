<?php
/**
 * Social Feed plugin for Craft CMS 3.x
 *
 * Display social media feeds in Craft templates.
 *
 * @link      https://www.elloro.nl
 * @copyright Copyright (c) 2022 Elloro
 */

namespace elloro\socialfeed\services;

use elloro\socialfeed\SocialFeed;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr16CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * SocialFeedService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Elloro
 * @package   SocialFeed
 * @since     1.0.0
 */
class SocialFeedService extends Component
{
    private Client $client;

    private function createClientHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $stack->push(new CacheMiddleware(
            new GreedyCacheStrategy(
                new Psr16CacheStorage(
                    new Psr16Cache(
						new FilesystemAdapter('social-feed', 900, Craft::$app->path->getRuntimePath())
					)
                ),
                900
            )
        ), 'cache');

        return $stack;
    }

    private function getClient(): Client
    {
        if (!isset($this->client)) {
            $apiKey = SocialFeed::getInstance()->getSettings()->apiKey;

            $this->client = new Client([
                'handler' => $this->createClientHandlerStack(),
                'base_uri' => 'https://social.elloro.nl/api/',
                'timeout' => 5,
                'headers' => [
                    'X-AUTH-TOKEN' => $apiKey,
                ],
            ]);
        }

        return $this->client;
    }

    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     SocialFeed::$plugin->socialFeedService->exampleService()
     *
     * @param mixed $pageId
     * @param array $params
     *
     * @return array
     */
    public function getPosts($pageId, array $params): array
    {
        $uri = sprintf('social_pages/%s/posts', $pageId);

        $query = [];

        if (isset($params['order'])) {
            if (!in_array($params['order'], ['ASC', 'DESC'], true)) {
                throw new \InvalidArgumentException('Order can be either "ASC" or "DESC"');
            }

            $query['order'] = strtolower($params['order']);
        }

        if (isset($params['limit'])) {
            $query['limit'] = $params['limit'];
        }

        if (isset($params['offset'])) {
            $query['offset'] = $params['offset'];
        }

        try {
            $response = $this->getClient()->get($uri, [
                'query' => $query,
            ]);
        } catch (TransferException $e) {
            Craft::error($e->getMessage(), 'social-feed');

            return [];
        }

        if (false !== $result = json_decode($response->getBody(), true)) {
            return $result;
        }

        return [];
    }
}
