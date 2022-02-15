<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acl_mapping}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%acl}}`
 */
class m210712_102757_create_acl_mapping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acl_mapping}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'acl_id' => $this->integer(),
            'created_at' => $this->dateTime()->defaultValue(null),
            'updated_at' => $this->dateTime()->defaultValue(null),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-acl_mapping-user_id}}',
            '{{%acl_mapping}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-acl_mapping-user_id}}',
            '{{%acl_mapping}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `acl_id`
        $this->createIndex(
            '{{%idx-acl_mapping-acl_id}}',
            '{{%acl_mapping}}',
            'acl_id'
        );

        // add foreign key for table `{{%acl}}`
        $this->addForeignKey(
            '{{%fk-acl_mapping-acl_id}}',
            '{{%acl_mapping}}',
            'acl_id',
            '{{%acl}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-acl_mapping-user_id}}',
            '{{%acl_mapping}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-acl_mapping-user_id}}',
            '{{%acl_mapping}}'
        );

        // drops foreign key for table `{{%acl}}`
        $this->dropForeignKey(
            '{{%fk-acl_mapping-acl_id}}',
            '{{%acl_mapping}}'
        );

        // drops index for column `acl_id`
        $this->dropIndex(
            '{{%idx-acl_mapping-acl_id}}',
            '{{%acl_mapping}}'
        );

        $this->dropTable('{{%acl_mapping}}');
    }
}
