<!DOCTYPE html>
<html lang="en">
<head>
  <title>My List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function(){
     $('#chk_all').click(function(){
         if(this.checked)
             $(".chkbox").prop("checked", true);
            else
            $(".chkbox").prop("checked", false);
      });    
      var submitUpload = jQuery("#submitUpload");
      var closeUpload = jQuery("#closeUpload");
      var upload_listen = jQuery("#upload");
      var modal = jQuery(".dialog");
      upload_listen.on("click",function(){
      modal.fadeIn(300);  
      });
      closeUpload.on("click",function(){
      modal.fadeOut(300);  
      });
        
        $("#upload_src").on('submit', function(e){ e.preventDefault();     
            let text = "Are you sure that you wanted to this ? if yes click OK else click cancel.";
               if (confirm(text) == true) {	                                          
                      $.ajax({
                       url: 'uploadFile.php',
                        type: 'POST',
                        data: new FormData(this),
                        async: true,
                        success: function(dataRes) {     
                            alert(dataRes);   
                            window.location.reload();
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                       }); 
               }
         });      
        
         $("#del_list").click(function(){
          let text = "Are you sure you wated to delete selected file from the list  ? if yes click OK else click cancel.";
          if (confirm(text) == true) {		
              var listId = new Array();
              $("input:checked").each(function() {
                  listId.push($(this).val());
              });		
              let del_list = "";		
              $.post("uploadFile.php",{del_list : del_list , listId : listId},function(sdlDtat){
                  alert(sdlDtat);
                  window.location.reload();	
	       });	
        }
        });
        
      });
      

    function download(type, fn, dl) {
    var elt = document.getElementById('list');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
    XLSX.writeFile(wb, fn || ('Contact List.' + (type || 'xlsx')));
    } 
  </script>
  <style>
  /*my popup dialog */
        .dialog {
            display: none;
            position: fixed;
            font-family: Arial, Helvetica, sans-serif;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 99999;
            height: 100%;
            width: 100%;
        }
        .dialog_conent{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            width: 80%;
            padding: 20px;
            border-radius: 20px;
        }
        
        .dialog_text{
          color:black;  
        }       
  </style>
  
</head>
<body>

<div class="container mt-3">
  <h2>How to upload and download excel (xlsx) file from the database !!!</h2><hr/>
  <h3>My Contact List</h3>
  
<botton class="btn btn-success" onclick="download('xlsx')">Download</botton> 
<botton class="btn btn-warning" id="upload">Upload</botton>   
<button class="btn btn-danger" type="button" id="del_list">Delete</button>  
 <hr/>  
<?php 
  require_once("uploadFile.php");    
  $run = new class() extends gen_function{

      function __construct(){   
        parent::__construct();
      }
          
      function connectivity(){   
        return $this->pdo;
      }
        
    public function home(){ ?>
    <div id="list">
    <table class="table table-dark">
        <thead>
          <tr>
          <th><input type="checkbox" id="chk_all"/> All</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
    <?php 
    $prevQuery = "SELECT * FROM `users`";
    $prevResult = $this->pdo->query($prevQuery); 
    if($prevResult->rowCount() > 0){ $row = $prevResult->fetchAll(PDO::FETCH_OBJ); 
        foreach($row as $data){ ?>
          <tr>
          <td class="center"><input type="checkbox" name="chk[]" class="chkbox"  value="<?=$data->id;?>"/></td>    
            <td><?=$data->firstName;?></td>
            <td><?=$data->lastName;?></td>
            <td><?=$data->email;?></td>
          </tr>        
    <?php } ?>
        
    <?php }else{ ?>
    <tr>
    <td colspan="3"><center>No record</center></td>
    </tr>  
    <?php } ?>                      
        </tbody>
      </table>        
    </div>
    <?php }    
    
};

      $run->home();    
    ?>
 
</div>

<div class="dialog upload">
<div class="dialog_conent text-center">
<h3>Upload</h3>    
<form id="upload_src" enctype="multipart/form-data">
<input type="hidden" name="upload">
<input type="file" name="dataFile" required>
<br>     
<hr/>
<button type="submit" class="btn btn-success" id="submitUpload">Submit</button>    
<button type="button" class="btn btn-danger" id="closeUpload">Close</button>   
</form> 
</div>
</div> 

</body>
</html>