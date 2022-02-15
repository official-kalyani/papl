  $(document).ready(function(){
    
    setTimeout(function() {
      if($('#enrollment-plant_id').val()){
        $('#master-import').prop('disabled', false);
      }
    }, 1000);
   //Plant select
    $('#enrollment-plant_id').on('change', function() {
        if(this.value){
            $('#master-import').prop('disabled', false);
        }else{
            $('#master-import').prop('disabled', true);
        }
      });
  
  //Qualification data Sample Download
  $("#qualificationSample").click(function(){
    
    var plant_id=$("#enrollment-plant_id").val();
    if(plant_id){
      //alert(plant_id);
      $.ajax({url:"qualification_sample?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeID").attr('src', results);
                      //console.log(results);
                    
            }
        });
    }else{
      alert('Choose Plant');
    }
    
  });

  //Internal data Sample Download
  $("#internalSample").click(function(){
    
    var plant_id=$("#enrollment-plant_id").val();
    if(plant_id){
      //alert(plant_id);
      $.ajax({url:"internal_sample?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeID").attr('src', results);
                      //console.log(results);
                    
            }
        });
    }else{
      alert('Choose Plant');
    }
    
  });

  //Bank data Sample Download
    $("#bankSample").click(function(){
    
      var plant_id=$("#enrollment-plant_id").val();
      if(plant_id){
        //alert(plant_id);
        $.ajax({url:"bank_sample?plant_id="+plant_id,
              success:function(results)
              { 
                $("#iframeID").attr('src', results);
                        //console.log(results);
                      
              }
          });
      }else{
        alert('Choose Plant');
      }
      
    });
  //Nominee data Sample Download
  $("#nomineeSample").click(function(){
    
    var plant_id=$("#enrollment-plant_id").val();
    if(plant_id){
      //alert(plant_id);
      $.ajax({url:"nominee_sample?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeID").attr('src', results);
                      //console.log(results);
                    
            }
        });
    }else{
      alert('Choose Plant');
    }
    
  });

  //Family data Sample Download
  $("#familySample").click(function(){
    
    var plant_id=$("#enrollment-plant_id").val();
    if(plant_id){
      //alert(plant_id);
      $.ajax({url:"family_sample?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeID").attr('src', results);
                      //console.log(results);
                    
            }
        });
    }else{
      alert('Choose Plant');
    }
    
  });

  //EPF/ESIC data Sample Download
  $("#epfSample").click(function(){
    
    var plant_id=$("#enrollment-plant_id").val();
    if(plant_id){
      //alert(plant_id);
      $.ajax({url:"epf_sample?plant_id="+plant_id,
            success:function(results)
            { 
              $("#iframeID").attr('src', results);
                      //console.log(results);
                    
            }
        });
    }else{
      alert('Choose Plant');
    }
    
  });
     
   //Master Data Import 
   $('#master_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     //alert('Hello');
     var plant_id=$("#enrollment-plant_id").val();
     var formData = new FormData($('#master_import_form')[0]);
     $.ajax({  
      url:"master_import?plant_id="+plant_id,  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#master_import_form')[0].reset();  
       $('#master_import_Modal').modal('hide');  
       alert(results);
       //$('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    }); 
    
    //Qualification Data Import
    $('#qualification_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#qualification_import_form')[0]);
     $.ajax({  
      url:"qualification_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#qualification_import_form')[0].reset();  
       $('#qualification_import_Modal').modal('hide');  
       alert(results);
       //$('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });
    
    //Internal Data Import
    $('#internal_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#internal_import_form')[0]);
     $.ajax({  
      url:"internal_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#internal_import_form')[0].reset();  
       $('#internal_import_Modal').modal('hide');  
       alert(results);
       $('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });
  
    //Bank Data Import
    $('#bank_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#bank_import_form')[0]);
     $.ajax({  
      url:"bank_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#bank_import_form')[0].reset();  
       $('#bank_import_Modal').modal('hide');  
       $('#msg_Modal').modal('show'); 
       $('#msg').html(results);
       $('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });

    //Nominee Data Import
    $('#nominee_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#nominee_import_form')[0]);
     $.ajax({  
      url:"nominee_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#nominee_import_form')[0].reset();  
       $('#nominee_import_Modal').modal('hide');  
       alert(results);
       $('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });

    //Family Data Import
    $('#family_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#family_import_form')[0]);
     $.ajax({  
      url:"family_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#family_import_form')[0].reset();  
       $('#family_import_Modal').modal('hide');  
       alert(results);
       $('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });

    //EPF/ESIC Data Import
    $('#epf_import_form').submit(function(e){
      e.preventDefault(); 
      e.stopImmediatePropagation();
     
     var formData = new FormData($('#epf_import_form')[0]);
     $.ajax({  
      url:"epf_import",  
      method:"POST",  
      data:formData,  
      contentType: false,
      processData: false,
      cache: false,
      beforeSend:function(data){  
       $('#import').val("Importing"); 
       //console.log(data);	 
      },  
      success:function(results){ 
          //console.log(results);
       $('#epf_import_form')[0].reset();  
       $('#epf_import_Modal').modal('hide');  
       alert(results);
       $('#nav-qualification-tab"]').toggleClass('active');//This is not working
      }  
     });
      return false;   
    });

   });