<?php
public function actionQualification(){
        
        if(!empty(Yii::$app->request->get('enrolementid')) ){
          $enrolmentid=Yii::$app->request->get('enrolementid');  
        }elseif (!empty(Yii::$app->request->get('papl_id'))) {
            $enrolmentid=Yii::$app->request->get('papl_id'); 
        }else{
            $enrolmentid=Yii::$app->request->post('enrolementid');
        }


        $qualification_model = new Qualification();
        $tag='';
         $msg ='';
        if ($qualification_model->load($this->request->post())) {
         
          $input_qualification = Yii::$app->request->post();
          foreach ($input_qualification['Qualification'] as $key =>$qualification) {
            // echo '<pre>';print_r(Yii::$app->request->post());die();
            
              $qualification_model =Qualification::find()->where(['enrolement_id'=>$input_qualification['enrolementid']])->one();
                    if (empty($qualification_model)) {


                        $qualification_model = new Qualification();
                        $qualification_model->enrolement_id=$enrolmentid;
                        if ($key == 'university_name') {
                            $qualification_model->university_name=$qualification;
                        }
                        if ($key == 'college_name') {
                           $qualification_model->college_name=$qualification;
                        }
                        if ($key == 'year_of_passout') {
                           $qualification_model->year_of_passout=$qualification;
                        }
                         if ($key == 'year_of_passout') {
                           $qualification_model->year_of_passout=$qualification;
                        }
                        if ($key == 'division_percent') {
                           $qualification_model->division_percent=$qualification;
                        }
                        if ($key == 'highest_qualification') {
                           $qualification_model->highest_qualification=$qualification;
                        }
                        $qualification_model->created_by=Yii::$app->user->identity->id;
                        $qualification_model->updated_by=Yii::$app->user->identity->id;
                         $qualification_documents = UploadedFile::getInstances($qualification_model, 'qualification_document');
                        
                         // echo "<pre>";var_dump($qualification_documents);
                            if (isset($qualification_documents)) {
                                $basepath = Yii::getAlias('@storage');
                                $randnum = Yii::$app->security->generateRandomString();
                                $file = '/upload/' . $randnum .'.'. $qualification_documents[$i]->extension;
                                $path = $basepath . $file;
                                $qualification_documents[$i]->saveAs($path);
                                $qualification_model->qualification_document=$file;
                            }
                    }else{
                        $qualification_model =Qualification::find()->where(['enrolement_id'=>$input_qualification['enrolementid']])->one();
                       if ($key == 'university_name') {
                            $qualification_model->university_name=$qualification;
                        }
                        if ($key == 'college_name') {
                           $qualification_model->college_name=$qualification;
                        }
                        if ($key == 'year_of_passout') {
                           $qualification_model->year_of_passout=$qualification;
                        }
                         if ($key == 'year_of_passout') {
                           $qualification_model->year_of_passout=$qualification;
                        }
                        if ($key == 'division_percent') {
                           $qualification_model->division_percent=$qualification;
                        }
                        if ($key == 'highest_qualification') {
                           $qualification_model->highest_qualification=$qualification;
                        }
                        $qualification_model->updated_by = Yii::$app->user->identity->id;
                        $qualification_documents = UploadedFile::getInstances($qualification_model, 'qualification_document');
                        
                        
                         
                            if (isset($qualification_documents)) {
                         //        foreach ($qualification_documents as $qualification_document_v) {
                         //     echo "<pre>";print_r($qualification_document_v);die();
                         // }
                                $basepath = Yii::getAlias('@storage');
                                $randnum = Yii::$app->security->generateRandomString();
                                $file = '/upload/' . $randnum .'.'. $qualification_documents->extension;
                                $path = $basepath . $file;
                                $qualification_documents[$i]->saveAs($path);
                                $qualification_model->qualification_document=$file;
                            }

                    }
                   
                    

                    if ($qualification_model->save()) {                    
                        
                        
                    } else {
                        // echo $attendance['papl_id'];
                        var_dump($qualification_model->getErrors());
                    }
          }




           

            if($msg == ''){
                if(Yii::$app->request->post('Qualification'))
                    $tag='internal';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag]);
            }else{
                $tag='qualification';
                return $this->redirect(['enrole', 'enrolementid' => $enrolmentid,'tag'=>$tag,'msg' => $msg]);
            }
                
            }
            
  
    }


    ESI - MONTHLY MC FILE
    RETURN EPF (2021-22 LANJIGARH  PF)