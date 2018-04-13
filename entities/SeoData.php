<?php
namespace maks757\seo\entities;

use yii\db\ActiveRecord;

/**
 * @author Gutsulyak Vadim <guts.vadim@gmail.com>
 *
 * @property int $id
 * @property string entity_id
 * @property string entity_name
 * @property string seo_url
 * @property string title
 * @property string keywords
 * @property string description
 * @property string $seo_content
 * @property int $meta_robots_id
 * @property SeoMetaRobots metaRobots
 */
class SeoData extends ActiveRecord
{
    public static function tableName() {
        return 'seo_data';
    }

    public function getMetaRobots() {
        return $this->hasOne('maks757\seo\entities\SeoMetaRobots', ['id', 'meta_robots_id']);
    }
}