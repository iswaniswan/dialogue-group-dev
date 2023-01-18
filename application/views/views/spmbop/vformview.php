<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
      <div class="panel-body table-responsive">
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
             <th>No SPMB</th>
             <th>Tanggal SPMB</th>
             <th>Area</th>
             <th>Action</th>
           </tr>
         </thead>
         <tbody>
         </tbody>
       </table>
     </div>
   </div>
 </div>
</div>
<script>
  $(document).ready(function () {
    datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/');
  });

  function cek(ispmb) {
    swal({   
      title: "Request OP Untuk "+ispmb+"?",   
      text: "Anda akan masuk ke menu selanjutnya!",   
      type: "warning",  
      showCancelButton: true,   
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: "Ya, lanjutkan!",     
      cancelButtonText: "Tidak, Siap SJ!",  
      closeOnConfirm: false,   
      closeOnCancel: false 
    }, function(isConfirm){   
      if (isConfirm) {
        swal({
          title: "Ok!",
          type: "success",
          showConfirmButton: false,
          timer: 400
        });
        show('<?= $folder;?>/cform/edit/'+ispmb,'#main');   
      } else {     
        swal({   
          title: "Siap SJ Untuk "+ispmb+" ?",   
          text: "Anda akan melanjutkan ke Siap SJ!",   
          type: "warning",   
          showCancelButton: true,   
          confirmButtonColor: "#DD6B55", 
          confirmButtonText: "Ya, siap SJ!",
          cancelButtonText: "Tidak, batalkan!",   
          closeOnConfirm: false,   
          closeOnCancel: false 
        }, function(isConfirm){   
          if (isConfirm) { 
            $.ajax({
              type: "post",
              data: {
                'ispmb'  : ispmb
              },
              url: '<?= base_url($folder.'/cform/siapsj'); ?>',
              dataType: "json",
              success: function (data) {
                swal({
                  title: "Ok!",
                  text: "No. SPMB "+ispmb+" sudah siap SJ :)",
                  type: "success",
                  showConfirmButton: false,
                  timer: 1200
                });                
                show('<?= $folder;?>/cform/','#main');   
              },
              error: function () {
                swal("Maaf", "Data gagal diupdate :(", "error");
              }
            });
          } else {     
            swal("Dibatalkan", "Anda membatalkan :)", "error");
            swal({
              title: "Dibatalkan",
              text: "Anda membatalkan :)",
              type: "error",
              showConfirmButton: false,
              timer: 800
            });      
          } 
        });
      } 
    });
  }
</script>