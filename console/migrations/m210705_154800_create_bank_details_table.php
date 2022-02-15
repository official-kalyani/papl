<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_details}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrolement}}`
 */
class m210705_154800_create_bank_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_details}}', [
            'id' => $this->primaryKey(),
            'detail_id' => $this->integer(),
            'enrolement_id' => $this->string(10),
            'transaction_id' => $this->string(),
            'IFSC' => $this->string(),
            'name_passbook' => $this->string(),
            'name_bank' => $this->string(),
            'name_branch' => $this->string(),
            'pass_book_photo' => $this->string(), 
            'is_delete' => $this->boolean()->defaultValue('0')->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `enrolement_id`
        $this->createIndex(
            '{{%idx-bank_details-enrolement_id}}',
            '{{%bank_details}}',
            'enrolement_id'
        );

        // add foreign key for table `{{%enrolement}}`
        $this->addForeignKey(
            '{{%fk-bank_details-enrolement_id}}',
            '{{%bank_details}}',
            'enrolement_id',
            '{{%enrollment}}',
            'enrolement_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%enrolement}}`
        $this->dropForeignKey(
            '{{%fk-bank_details-enrolement_id}}',
            '{{%bank_details}}'
        );

        // drops index for column `enrolement_id`
        $this->dropIndex(
            '{{%idx-bank_details-enrolement_id}}',
            '{{%bank_details}}'
        );

        $this->dropTable('{{%bank_details}}');
    }
}
