<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%enrollment}}`.
 */
class m210702_083402_create_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%enrollment}}', [
            'id' => $this->primaryKey(),
            'enrolement_id'=>$this->string(10)->unique(),
            'adhar_name' => $this->string(200),
            'father_husband_name' => $this->string(200),
            'relation_with_employee' => $this->string(150),
            'adhar_number' => $this->double(16),
            'browse_adhar' => $this->string(),
            'browse_pp_photo' => $this->string(),
            'gender' => $this->string(),
            'marital_status' => $this->string(),
            'mobile_with_adhar' => $this->string(),
            'dob' => $this->date(),
            'permanent_addrs' => $this->string(),
            'permanent_state' => $this->string(),
            'permanent_district' => $this->string(),
            'permanent_ps' => $this->string(),
            'permanent_po' => $this->string(),
            'permanent_village' => $this->string(),
            'permanent_block' => $this->string(),
            'permanent_tehsil' => $this->string(),
            'permanent_GP' => $this->string(),
            'permanent_pincode' => $this->integer(6),
            'permanent_mobile_number' => $this->string(),
            'present_address' => $this->string(),
            'present_state'=> $this->string(),
            'present_district' => $this->string(),
            'present_ps' => $this->string(),
            'present_po' => $this->string(),
            'present_village' => $this->string(),
            'present_block' => $this->string(),
            'present_tehsil' => $this->string(),
            'present_gp' => $this->string(),
            'present_pincode' => $this->integer(6),
            'present_mobile_number' => $this->integer(10),
            'blood_group' => $this->string(),
            'ID_mark_employee' => $this->string(),
            'nationality' => $this->string(),
            'religion' => $this->string(),
            'caste' => $this->string(25),
            'referenced_remark'=>$this->string(),
            'status'=>$this->tinyInteger()->comment('0=>no action, 1=> transfer_to plant,2=>approved_by_plant_mgr,3=>epf_esi created,4=>rejected,5=>parmanent'),
            'comment'=> $this->string(),
            'is_delete' => $this->boolean(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->createIndex(
            '{{%idx-enrolement-enrolement_id}}',
            '{{%enrollment}}',
            'enrolement_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%enrollment}}');
    }
}
