<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-6">Supplier</label>
                    <label class="col-md-6">No Order Pembelian</label>
                    <div class="col-sm-6">                           
                        <select name="isupplier" id="isupplier" class="form-control select2" onchange="getiop(this.value);"> 
                        </select>
                    </div>
                    <div class="col-sm-6">
                       <select name="iop" id="iop" class="form-control select2" onchange="getibtb(this.value);"> 
                        </select>
                    </div>
                </div> 
               <!-- <div class="form-group">
                     <label class="col-md-6">Jenis Pembelian</label>
                     <div class="col-sm-6">
                         <input type="hidden" name="ipaymenttype" id="ipaymenttype" class="form-control">
                        <input type="text" name="epaymenttype" id="epaymenttype" class="form-control" readonly>
                         <select name="ipaymenttype" class="form-control select2" readonly>
                            <option value="">Pilih Jenis Pembelian</option>
                            <option value="0">Cash</option>
                            <option value="1">Kredit</option> 
                        </select>
                    </div>
                </div>         -->  
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>                    
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 <div class="form-group row">
                    <label class="col-md-6">No Bukti Terima Barang</label>
                    <label class="col-md-6">No Dokumen Supplier</label>
                    <div class="col-sm-6">                           
                        <select name="ibtb" id="ibtb" class="form-control select2" onchange="getidoksup(this.value);"> 
                        </select>
                    </div>
                    <div class="col-sm-6">
                         <select name="isj" id="isj" class="form-control select2"> 
                        </select>
                    </div>
                </div>     
        </div>
    </div>
        </form>
    </div>
</div>
<script>
// $("form").submit(function (event) {
//     event.preventDefault();
// });
    
$(document).ready(function () {
    $(".select2").select2();
 });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/supplier'); ?>',
          dataType: 'json',
          delay: 250,          
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      })
});

function get(isupplier) {
        var isupplier = $('#isupplier').val();
        $.ajax({
            type: "post",
            data: {
                'isupplier': isupplier
            },
            url: '<?= base_url($folder.'/cform/getipayment'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipaymenttype').val(data[0].i_jenis_pembelian);
                $('#epaymenttype').val(data[0].epayment);
            },
            error: function () {
                alert('Error :)');
            }
        });
}

function getiop(isupplier) {
var isupplier = $('#isupplier').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getiop');?>",
        data:"isupplier="+isupplier,
        dataType: 'json',
        success: function(data){
            $("#iop").html(data.kop);

            getibtb('IOP');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getibtb(iop) {
var isupplier = $('#isupplier').val();
var iop = $('#iop').val();

    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getibtb');?>",
        data:{
            'isupplier': isupplier,
            'iop':iop,
        },
        dataType: 'json',
        success: function(data){
            $("#ibtb").html(data.kop);

            getidoksup('IBTB');
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getidoksup(ibtb) {
var isupplier = $('#isupplier').val();
var iop = $('#iop').val();
var ibtb = $('#ibtb').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getidoksup');?>",
        data:{
            'isupplier': isupplier,
            'iop':iop,
            'ibtb':ibtb,
        },
        dataType: 'json',
        success: function(data){
            $("#isj").html(data.kop);

            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=select2]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('isupplier').value=='') {
        swal("Pilih Supplier!");
        return false;
    }if (document.getElementById('ipaymenttype').value=='') {
        swal("Pilih Jenis Pembelian!");
        return false;
    }else {
        return true
    }
}    
</script>