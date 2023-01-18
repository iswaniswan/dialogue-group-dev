<?php $data = $proses->row(); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Kelompok Barang</label>
                        <label class="col-md-4">Jenis Barang</label>
                        <label class="col-md-4">Kode Barang</label>
                        <div class="col-sm-4">
                            <?php if ($ikodekelompok!='semua' && $ikodekelompok!='') {
                                $kelompok = $kelompok->e_nama;
                            }else{
                                $kelompok = 'Semua Kategori';
                            }?>
                            <input type="text" class="form-control" required="" onkeyup="gede(this)" value="<?= $kelompok;?>" readonly> 
                        </div>
                        <div class="col-sm-4">
                            <?php if ($ikodejenis!='semua' && $ikodejenis!='') {
                                $jenis = $jenis->name;
                            }else{
                                $jenis = 'Semua Jenis';
                            }?>
                            <input type="text" class="form-control" required="" onkeyup="gede(this)" value="<?= $jenis;?>" readonly> 
                        </div>
                        <div class="col-sm-4">
                            <?php if ($iproduct!='semua' && $iproduct!='') {
                                $product = $product->id.' - '.$product->name;
                            }else{
                                $product = 'Semua Barang';
                            }?>
                            <input type="text" class="form-control" required="" onkeyup="gede(this)" value="<?= $product;?>" readonly> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">&nbsp;</label>
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="myTable" class="table table-bordered" cellspacing="0"  width="100%">
                <thead>
                    <tr>  
                        <th class="text-center" style="width: 4%;">No</th>                          
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center" style="width: 15%;">Harga</th>
                        <th class="text-center" style="width: 15%;">Berlaku Mulai</th>
                        <th class="text-center" style="width: 4%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?$i = 0;
                    foreach ($proses->result() as $row) {
                        $i++;?>
                        <tr>
                            <td class="text-center">
                                <?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td>
                                <input type="text" id="kodebrg<?=$i;?>" class="form-control" name="kodebrg<?=$i;?>"value="<?= $row->i_product_motif; ?>"readonly >
                            </td>
                            <td>
                                <input type="text" id="namabrg<?=$i;?>" class="form-control" name="namabrg<?=$i;?>"value="<?= $row->e_product_basename; ?>"readonly>
                                <input type="hidden" id="icolor<?=$i;?>" class="form-control" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>"readonly>
                            </td>
                            <td>
                                <input type="text" id="harga<?=$i;?>" class="form-control text-right" name="harga<?=$i;?>"value="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this)">
                            </td>
                            <td>
                                <input type="text" id="dberlaku<?=$i;?>" class="form-control date" name="dberlaku<?=$i;?>" value="" onchange="cektanggal(this.value);" readonly >
                                <input type="hidden" id="dberlakuold<?=$i;?>" class="form-control date" name="dberlakuold<?=$i;?>" value="<?= $row->d_berlaku_old; ?>" readonly >
                            </td>
                            <td class="text-center" style="width:2%;">
                                <input type="checkbox" name="cek<?= $i; ?>" id="cek<?= $i; ?>">
                            </td> 
                        </tr>
                        <?}?>
                    </tbody>
                </table>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>"readonly>
            </div>
        </div>
    </div>
</div>
<script>
    function cektanggal(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            berlaku  = $('#dberlaku'+i).val();
            berlakuold = $('#dberlakuold'+i).val();
            if (berlakuold != "") {
                if (berlaku < berlakuold) {
                    swal('Harga Barang Sampai Tanggal Tersebut Sudah Tersedia');
                    $('#dberlaku'+i).val("");
                    break;
                }
            }
        }
    }

    $(document).ready( function () {
        $('#myTable').DataTable();
        $('.select2').select2();
        showCalendar('.date');    
    });

    $('.dataTables_paginate').on('click', function() {
        $('.select2').select2();
        showCalendar('.date');
    });


    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function validasi(){
        var s=0;
        var i = document.getElementById("jml").value;
        var maxpil = 1;
        var jml = $("input[type=checkbox]:checked").length;
        var textinputs = document.querySelectorAll('input[type=checkbox]'); 
        var empty = [].filter.call( textinputs, function( el ) {
            return !el.checked
        });

        if (textinputs.length == empty.length) {
            swal("Barang Belum dipilih !!");
            return false;
        }else{
            return true;
        }
    }    
</script>
