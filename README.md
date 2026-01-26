# Social Feed for Craft CMS

Display social media feeds in Craft templates.

## Requirements

- Craft CMS ^5.0
- PHP >= 8.1
- ext-json

## Installation

Install via Composer:

```bash
composer require elloro/craft-social-feed
```

Then install the plugin from the Craft CMS control panel under **Settings > Plugins**, or via the CLI:

```bash
php craft plugin/install social-feed
```

## Configuration

The plugin requires an API key for the social feed service. Create a `config/social-feed.php` file:

```php
<?php

return [
    'apiKey' => getenv('SOCIAL_FEED_API_KEY'),
];
```

| Setting  | Type   | Required | Description                                  |
|----------|--------|----------|----------------------------------------------|
| `apiKey` | string | Yes      | API key for the social feed service           |

The plugin connects to the API at https://social.elloro.nl. You can verify your API key by making a test request:

```bash
curl -H "X-AUTH-TOKEN: $SOCIAL_FEED_API_KEY" https://social.elloro.nl/api/social_pages/{SOCIAL_PAGE_ID}/posts
```

## Usage

The plugin exposes a `craft.socialFeed` template variable with a `getPosts` method.

```twig
{% set posts = craft.socialFeed.getPosts(pageId, params) %}
```

### Parameters

| Parameter | Type   | Required | Description                          |
|-----------|--------|----------|--------------------------------------|
| `pageId`  | mixed  | Yes      | The ID of the social media page      |
| `params`  | array  | No       | Query parameters (see below)         |

### Query Parameters

| Parameter | Type    | Description                                      |
|-----------|---------|--------------------------------------------------|
| `order`   | string  | Sort order: `"ASC"` or `"DESC"`                  |
| `limit`   | integer | Maximum number of posts to return                |
| `offset`  | integer | Number of posts to skip (for pagination)         |

### Example

```twig
{% set posts = craft.socialFeed.getPosts('my-page-id', {
    order: 'DESC',
    limit: 10,
    offset: 0,
}) %}

{% for post in posts %}
    <article>
        {{ post.content }}
    </article>
{% endfor %}
```

## Caching

API responses are cached for 15 minutes using a filesystem-based cache stored in Craft's runtime directory. No additional configuration is needed.

## License

MIT