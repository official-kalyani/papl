<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%enrolement}}`
 */
class m210709_130627_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'enrolement_id' => $this->string(10),
            'voter_id_number' => $this->string(),
            'voter_copy_photo' => $this->string(),
            'dl_number' => $this->string(),
            'dl_expiry_date' => $this->string(),
            'drivinglicense_photo' => $this->string(),
            'pannumber' => $this->string(),
            'pan_photo' => $this->string(),
            'passportnumber' => $this->string(),
            'passport_expirydate' => $this->string(),
            'passport_photo' => $this->string(),
            'is_delete' => $this->Integer(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `enrolement_id`
        $this->createIndex(
            '{{%idx-document-enrolement_id}}',
            '{{%document}}',
            'enrolement_id'
        );

        // add foreign key for table `{{%enrolement}}`
        $this->addForeignKey(
            '{{%fk-document-enrolement_id}}',
            '{{%document}}',
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
            '{{%fk-document-enrolement_id}}',
            '{{%document}}'
        );

        // drops index for column `enrolement_id`
        $this->dropIndex(
            '{{%idx-document-enrolement_id}}',
            '{{%document}}'
        );

        $this->dropTable('{{%document}}');
    }
}
