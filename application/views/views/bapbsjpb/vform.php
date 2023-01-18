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
                        <label class="col-md-3">Tanggal BAPB</label>
                        <div class="col-sm-3">
                            <input type="text" readonly id= "dbapb" name="dbapb" class="form-control date" value="<?= date('d-m-Y');?>">
                            <input id="ibapb" name="ibapb" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-3">
                            <input type="text" id= "iarea" name="area" readonly="" class="form-control" value="PB">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nilai BAPB</label>
                        <div class="col-sm-3">
                            <input id="vbapb" name="vbapb" class="form-control" required="" 
                            readonly value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">No SJPB</th>
                                <th style="text-align: center; width: 15%;">Tanggal SJPB</th>
                                <th style="text-align: center; width: 15%;">Jml</th>
                                <th style="text-align: center;">Keterangan</th>
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
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select  type="text" id="isj'+xx+ '" class="form-control" name="isj'+xx+'" onchange="getsj('+xx+');"></td>';
        cols += '<td><input type="text" id="dsjx'+xx+'" type="text" class="form-control" name="dsjx'+xx+'" readonly><input type="hidden" id="dsj'+xx+'" type="text" class="form-control" name="dsj'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="vsj'+xx+'" class="form-control" name="vsj'+xx+'" readonly value="0" style="text-align: right;"></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#isj'+xx).select2({
            placeholder: 'Cari No SJPB',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datasj/'); ?>',
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
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });
    
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 0, 5);
    });

    function getsj(id){
        ada=false;
        var a = $('#isj'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#isj'+i).val()) && (i!=x)){
                alert ("No SJPB : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'isj'  : a
                },
                url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vbapb').val());
                    $('#dsjx'+id).val(data[0].dsjpb);
                    $('#dsj'+id).val(data[0].d_sjpb);
                    $('#vsj'+id).val(formatcemua(data[0].v_sjpb));
                    $('#vbapb').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_sjpb))));
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#isj'+id).html('');
            $('#isj'+id).val('');
        }
    }

    function dipales(a){ 
        if((document.getElementById("dbapb").value!='') &&
            (document.getElementById("iarea").value!='') &&
            (document.getElementById("jml").value!='')
            ){   
            if(a==0){
                alert('Isi data item minimal 1 !!!');
                return false;
            }else{                
                for(i=1;i<=a;i++){                    
                    if((document.getElementById("isj"+i).value=='') ||
                        (document.getElementById("dsj"+i).value=='') ||
                        (document.getElementById("vsj"+i).value=='')){
                        alert('Data item masih ada yang salah !!!');                    
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            alert('Data header masih ada yang salah !!!');
            return false;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>