<?php

use yii\db\Migration;

class m160314_142552_seo_data extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%seo_data}}', [
            'id' => $this->primaryKey(),
            'entity_id' =>  $this->string(255)->notNull(),
            'entity_name' => $this->string(255)->notNull(),
            'seo_url' => $this->text(),
            'title' => $this->text(),
            'keywords' => $this->text(),
            'description' => $this->text(),
            'seo_content' => $this->text(),
            'meta_robots_id' => $this->integer()
        ]);

        $this->createIndex('seo_data_entity', '{{%seo_data}}', ['entity_id', 'entity_name'], true);

        $this->createTable('{{%seo_meta_robots}}', [
            'id' => $this->primaryKey(),
            'value' => $this->string(255)
        ]);
        $this->insert('{{%seo_meta_robots}}', [
            'value' => 'noindex,follow'
        ]);
        $this->insert('{{%seo_meta_robots}}', [
            'value' => 'noindex,nofollow'
        ]);

        $this->addForeignKey(
            'seo_data_meta_robots',
            '{{%seo_data}}', 'meta_robots_id',
            '{{%seo_meta_robots}}', 'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%seo_data}}');
        $this->dropTable('{{%seo_meta_robots}}');
    }
}
