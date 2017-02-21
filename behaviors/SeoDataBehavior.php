<?php
namespace maks757\seo\behaviors;

use maks757\seo\entities\SeoData;
use Codeception\Exception\ConfigurationException;
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

    public $generation = true;
    public $generation_field = 'name';

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

        $name_field = $this->generation_field;
        if(!empty($this->seoData) && $this->generation && empty($this->getSeoUrl()) && !empty($this->owner->$name_field)) {
            if(empty($this->generation_field))
                throw new ConfigurationException('field `generation_field` the empty');
            $url = str_replace(' ', '-', preg_replace('/[^a-zA-ZА-Яа-я0-9\sчЧсСхХтТьЬрРюЮэЭыЫіІуУшШ]/', '', trim($this->owner->$name_field)));
            $this->setSeoUrl($url);
        }

        if(!empty($this->seoData) && $this->generation && empty($this->getSeoTitle()) && !empty($this->owner->$name_field)) {
            if(empty($this->generation_field))
                throw new ConfigurationException('field `generation_field` the empty');
            $this->setSeoTitle(trim($this->owner->$name_field));
        }

        $this->seoData->save();
    }
}