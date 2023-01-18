<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="eareaname" name="eareaname" class="form-control date" value="<?= $eareaname;?>">
                            <input type="hidden" name="periode" id="periode" value="<?= $iperiode; ?>">
                            <input type="hidden" name="irvtype" id="irvtype" value="02"><input type="hidden" name="iarea" id="iarea" value="<?= $iarea; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Periode Bank Masuk</label>
                        <!-- <label class="col-md-6">Bulan</label><label class="col-md-6">Tahun</label> -->
                        <div class="col-sm-6">
                            <select class="form-control" disabled="">
                                <option value=""></option>
                                <option value='01' <?php if ($bulan=='01') {
                                    echo "selected";} ?>>Januari</option>
                                <option value='02' <?php if ($bulan=='02') {
                                    echo "selected";} ?>>Februari</option>
                                <option value='03' <?php if ($bulan=='03') {
                                    echo "selected";} ?>>Maret</option>
                                <option value='04' <?php if ($bulan=='04') {
                                    echo "selected";} ?>>April</option>
                                <option value='05' <?php if ($bulan=='05') {
                                    echo "selected";} ?>>Mei</option>
                                <option value='06' <?php if ($bulan=='06') {
                                    echo "selected";} ?>>Juni</option>
                                <option value='07' <?php if ($bulan=='07') {
                                    echo "selected";} ?>>Juli</option>
                                <option value='08' <?php if ($bulan=='08') {
                                    echo "selected";} ?>>Agustus</option>
                                <option value='09' <?php if ($bulan=='09') {
                                    echo "selected";} ?>>September</option>
                                <option value='10' <?php if ($bulan=='10') {
                                    echo "selected";} ?>>Oktober</option>
                                <option value='11' <?php if ($bulan=='11') {
                                    echo "selected";} ?>>November</option>
                                <option value='12' <?php if ($bulan=='12') {
                                    echo "selected";} ?>>Desember</option>
                            </select>
                            <input type="hidden" name="iperiodebl" id="iperiodebl" value="<?= $bulan;?>">
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" disabled="">
                                <option value=""></option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($tahun==$i) {
                                    echo "selected";} ?>><?= $i;?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="iperiodeth" id="iperiodeth" value="<?= $tahun;?>">
                        </div>
                    </div>                         
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" id="print" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-print"></i>&nbsp;&nbsp;Cetak
                            </button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-primary btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/proses/<?= $iarea."/".$eareaname."/".$tanggal."/".$bulan."/".$tahun."/".$ibank."/".$ebankname."/"; ?>","#main");'><i class="fa fa-refresh"></i>&nbsp;&nbsp;Reload
                            </button>&nbsp;&nbsp;                               
                            <button type="button" id="addrow" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Bank</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="ebankname" name="ebankname" class="form-control" value="<?= $ebankname;?>">
                            <input type='hidden' name="ibank" id="ibank" value="<?= $ibank; ?>">
                            <input type='hidden' name="icoabank" id="icoabank" value="<?= $icoabank; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="dbank" name="dbank" class="form-control" value="<?= $tanggal;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Saldo</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="vsaldo" name="vsaldo" class="form-control" value="<?= number_format($saldo);?>">
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledetail" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 25%;">CoA</th>
                                <th style="text-align: center; width: 10%;">Tanggal</th>
                                <th style="text-align: center; width: 12%;">Area</th>
                                <th style="text-align: center; width: 15%;">Ku/Giro/Tunai</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center; width: 11%;">Jumlah</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        $("#tabledetail").attr("hidden", false);
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td><select type="text" id="icoa'+xx+ '" class="form-control" name="icoa'+xx+'" onchange="getcoa('+xx+');"><input type="hidden" id="ecoaname'+xx+ '" class="form-control" name="ecoaname'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="tgl'+xx+'" readonly class="form-control date" name="tgl'+xx+'" onchange="cektgl()";></td>';
        cols += '<td><select type="text" class="form-control" name="iarea'+xx+'" id="iarea'+xx+'"><?php if ($area) {foreach ($area as $key) { ?><option value="<?= $key->i_area;?>" <?php if ($iarea==$key->i_area) { echo "selected";} ?>><?= $key->e_area_name;?></option><?php }; } ?></select></td>';
        cols += '<td><select id="igiro'+xx+'" class="form-control" name="igiro'+xx+'"></select></td>';        
        cols += '<td><input id="edescription'+xx+'" class="form-control" name="edescription'+xx+'"></td>';
        cols += '<td><input id="vbank'+xx+'" class="form-control" name="vbank'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledetail").append(newRow);
        $('#icoa'+xx).select2({
            placeholder: 'Cari CoA',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/coa/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        showCalendar('#tgl'+xx);
        $('#iarea'+xx).select2({
            placeholder: 'Cari Area'
        });


        $('#igiro'+xx).select2({
            placeholder: 'Cari Giro',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/giro/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea   = $('#iarea'+xx).val();
                    var tgl     = $('#tgl'+xx).val();
                    var query   = {
                        q       : params.term,
                        iarea   : iarea,
                        tgl     : tgl
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $("#tabledetail").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    function cektanggal(){
        jmls=document.getElementById('jml').value;
        for(i=1;i<=jmls;i++){
            dbukti=document.getElementById('tgl'+i).value;
            dkk=document.getElementById('dbank').value;
            dtmp=dbukti.split('-');
            thnbk=dtmp[2];
            blnbk=dtmp[1];
            hrbk =dtmp[0];
            dtmp=dkk.split('-');
            thnkk=dtmp[2];
            blnkk=dtmp[1];
            hrkk =dtmp[0];
            if( thnbk>thnkk ){
                swal('Tanggal bukti tidak boleh lebih dari tanggal voucher !!!');
                document.getElementById('tgl'+i).value='';
            }else if (thnbk==thnkk){
                if( blnbk>blnkk ){
                    swal('Tanggal bukti tidak boleh lebih dari tanggal voucher !!!');
                    document.getElementById('tgl'+i).value='';
                }else if( blnbk==blnkk ){
                    if( hrbk>hrkk ){
                        swal('Tanggal bukti tidak boleh lebih dari tanggal voucher !!!');
                        document.getElementById('tgl'+i).value='';
                    }
                }
            }
        }
    }

    function cektgl() {
        cektanggal();
    }

    function getcoa(i){
        var icoa = $('#icoa'+i).val();
        $.ajax({
            type: "post",
            data: {
                'icoa': icoa
            },
            url: '<?= base_url($folder.'/cform/getcoa'); ?>',
            dataType: "json",
            success: function (data) {
                $("#ecoaname"+i).val(data[0].e_coa_name);      
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function dipales(){
        if((document.getElementById("iperiodeth").value!='') && (document.getElementById("iperiodebl").value!='') && (document.getElementById("iarea").value!='')) {
            a = document.getElementById("jml").value;
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("icoa"+i).value=='') || (document.getElementById("tgl"+i).value=='') || (document.getElementById("edescription"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    }
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>