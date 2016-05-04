<?php
namespace bl\seo;

use bl\seo\entities\SeoData;
use yii\base\Object;
use yii\db\ActiveRecordInterface;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

/**
 * @author Gutsulyak Vadim <guts.vadim@gmail.com>
 */
class SeoUrlRule extends Object implements UrlRuleInterface
{
    public $modelClass;
    public $prefix = '';
    public $route;
    public $params = [];
    public $condition = [];

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        /* @var $modelClass ActiveRecordInterface */

        if(empty($this->route)) {
            return false;
        }
        if(empty($this->modelClass) || !class_exists($this->modelClass)) {
            return false;
        }
        else {
            $modelClass = $this->modelClass;
        }

        $pathInfo = $request->getPathInfo();

        if(!empty($this->prefix)) {
            if(strpos($pathInfo, $this->prefix) === 0) {
                $pathInfo = substr($pathInfo, strlen($this->prefix));
            }
            else {
                return false;
            }
        }

        $routes = explode('/', $pathInfo);

        if(count($routes) == 1) {
            $seoData = SeoData::find()
                ->where([
                    'entity_name' => $modelClass,
                    'seo_url' => $routes[0]
                ])->one();


            if(!empty($seoData)) {
                $condition = [];

                $condition[$modelClass::primaryKey()[0]] = $seoData->entity_id;

                if(!empty($this->condition)) {
                    foreach($this->condition as $key => $value) {
                        if(is_callable($value)) {
                            $value = $value();
                        }
                        $condition[$key] = $value;
                    }
                }

                $model = $modelClass::find()
                    ->where($condition)
                    ->one();

                if(!empty($model)) {
                    $params = [];
                    if(!empty($this->params)) {
                        foreach($this->params as $key => $param) {
                            $params[$key] = $model->$param;
                        }
                    }
                    return [
                        $this->route,
                        $params
                    ];
                }
            }
        }
        return false;
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        /* @var $modelClass ActiveRecordInterface */

        if(empty($this->route)) {
            return false;
        }
        if(empty($this->modelClass) || !class_exists($this->modelClass)) {
            return false;
        }
        else {
            $modelClass = $this->modelClass;
        }

        if($route == $this->route) {
            $condition = [];

            if(!empty($this->params)) {
                foreach($this->params as $paramName => $param) {
                    if(isset($params[$paramName])) {
                        $condition[$param] = $params[$paramName];
                    }
                }
            }

            if(!empty($this->condition)) {
                foreach($this->condition as $key => $value) {
                    if(is_callable($value)) {
                        $value = $value();
                    }
                    $condition[$key] = $value;
                }
            }

            $model = $modelClass::find()
                ->where($condition)
                ->one();

            if($model) {
                $seoData = SeoData::find()
                    ->where([
                        'entity_name' => $this->modelClass,
                        'entity_id' => $model->getPrimaryKey()
                    ])
                    ->one();

                if($seoData) {
                    return $this->prefix . $seoData->seo_url;
                }
            }
        }
        return false;
    }
}