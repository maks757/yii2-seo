# yii-seo
Yii framework seo optimization module

yii migrate --migrationPath=@vendor/maks757/yii2-seo/migrations

### ActiveRecord config behaviors
```php
public function behaviors()
{
    return [
        'seo_data' => [
            'class' => 'maks757\seo\behaviors\SeoDataBehavior',
            'generation_field' => 'title'
        ],
        //...
    ];
}
```

add rules

```php
public function rules()
{
    return [
        //...
        [['seo_url', 'seo_title',], 'string', 'max' => 255],
        [['seo_keywords',], 'string', 'max' => 512],
        [['seo_description',], 'string', 'max' => 1024],
    ];
}
```

you can add properties

```php
/**
 * @property string $seo_url
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_descriptin
 */
```

and add input to form 

```php
<?= $form->field($model, 'seo_title')->textInput() ?>
<?= $form->field($model, 'seo_url')->textInput() ?>
<?= $form->field($model, 'seo_keywords')->textarea() ?>
<?= $form->field($model, 'seo_description')->textarea() ?>
```