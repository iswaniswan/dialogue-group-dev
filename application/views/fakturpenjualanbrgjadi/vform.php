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
                        <label class="col-md-6">Nomor Order Pembelian</label>
                        <div class="col-sm-6">                           
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getiop(this.value);"> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                           <select name="iop" id="iop" class="form-control select2" onchange="getibtb(this.value);"> 
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Nomor Bukti Terima Barang</label>
                        <label class="col-md-6">Nomor Dokumen Supplier</label>
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
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>    
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $("#iop").attr("disabled", true);
        $("#ibtb").attr("disabled", true);
        $("#isj").attr("disabled", true);
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
        $("#iop").attr("disabled", false);
        $("#ibtb").attr("disabled", false);
        $("#isj").attr("disabled", false);
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
        var isupplier = $('#isupplier').val();
        var iop = $('#iop').val();
        if (isupplier == '' || isupplier == null || iop == 'IOP' || iop == 'IOP') {
            swal("Data Masih Kosong!");
            return false;
        }else {
            return true
        }
    }    
</script>