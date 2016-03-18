<?php
namespace bl\seo\entities;

use yii\db\ActiveRecord;

/**
 * @author Gutsulyak Vadim <guts.vadim@gmail.com>
 */
class SeoMetaRobots extends ActiveRecord
{
    public static function tableName()
    {
        return 'seo_data_meta_robots';
    }

}