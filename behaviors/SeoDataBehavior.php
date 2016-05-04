<?php
namespace bl\seo\behaviors;

use bl\seo\entities\SeoData;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Class SeoDataBehavior
 * @author Gutsulyak Vadim <guts.vadim@gmail.com>
 * @package bl\seo
 *
 * @property BaseActiveRecord $owner
 */
class SeoDataBehavior extends Behavior
{
    /**
     * @var seoData SeoData
     */
    private $seoData;

    public function init()
    {
        $this->getSeoData();
    }

    public function getSeoTitle() {
        return $this->seoData->title;
    }

    public function setSeoTitle($title) {
        $this->seoData->title = $title;
    }

    public function getSeoDescription() {
        return $this->seoData->description;
    }

    public function setSeoDescription($description) {
        $this->seoData->description = $description;
    }

    public function getSeoKeywords() {
        return $this->seoData->keywords;
    }

    public function setSeoKeywords($keywords) {
        $this->seoData->keywords = $keywords;
    }

    public function getSeoUrl() {
        return $this->seoData->seo_url;
    }

    public function setSeoUrl($seoUrl) {
        $this->seoData->seo_url = $seoUrl;
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'getSeoData',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveSeoData',
            ActiveRecord::EVENT_AFTER_INSERT => 'saveSeoData',
        ];
    }

    public function getSeoData() {
        if($this->owner != null) {
            $this->seoData = SeoData::findOne([
                'entity_id' => $this->owner->getPrimaryKey(),
                'entity_name' => $this->owner->className()
            ]);
        }

        if($this->seoData == null) {
            $this->seoData = new SeoData();
        }
    }

    public function saveSeoData($event) {
        if(empty($this->seoData->entity_id)) {
            $this->seoData->entity_id = $this->owner->getPrimaryKey();
            $this->seoData->entity_name = $this->owner->className();
        }
        $this->seoData->save();
    }
}