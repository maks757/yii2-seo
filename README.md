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
        [['seoUrl', 'seoTitle',], 'string', 'max' => 255],
        [['seoKeywords',], 'string', 'max' => 512],
        [['seoDescription',], 'string', 'max' => 1024],
    ];
}
```

you can add properties

```php
/**
 * @property string $seoUrl
 * @property string $seoTitle
 * @property string $seoKeywords
 * @property string $seoDescriptin
 */
```

and add input to form 

```php
<?= $form->field($model, 'seoUrl')->textInput() ?>
<?= $form->field($model, 'seoTitle')->textInput() ?>
<?= $form->field($model, 'seoDescription')->textarea() ?>
<?= $form->field($model, 'seoKeywords')->textarea() ?>
```
