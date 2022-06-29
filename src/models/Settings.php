<?php
/**
 * Social Feed plugin for Craft CMS 3.x
 *
 * Display social media feeds in Craft templates.
 *
 * @link      https://www.elloro.nl
 * @copyright Copyright (c) 2022 Elloro
 */

namespace elloro\socialfeed\models;

use craft\base\Model;

/**
 * Settings Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Elloro
 * @package   SocialFeed
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $apiKey;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['apiKey'], 'required'],
        ];
    }
}
