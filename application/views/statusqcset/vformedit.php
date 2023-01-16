<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No Permintaan</label><label class="col-md-6">Tanggal Permintaan</label>
                        <div class="col-sm-6">                          
                           <input type="text" name="ispbb" class="form-control" value="<?= $data->i_spbb;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="dspbb" class="form-control date" value="<?= $data->d_spbb;?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-12">Tanggal Permintaan</label>
                        <div class="col-sm-12">
                            <input type="text" name="dspbb" class="form-control date" value="<?= $data->d_spbb;?>" readonly>
                        </div>
                    </div>     -->
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>

                            <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>       
                    </div> 
                   
                </div>
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label><label class="col-md-6">No Schedule</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="igudang" class="form-control" value="<?= $data->i_gudang;?>">
                             <input type="text" name="igudangfake" class="form-control" value="<?= $data->gudang;?>" readonly>
                        </div>
                        <div class="col-sm-6">                          
                           <input type="text" name="ischedule" class="form-control" value="<?= $data->i_schedule;?>" readonly>
                           <input type="hidden" name="dschedule" class="form-control" value="<?= $data->d_schedule;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremarkh" class="form-control" value="<?= $data->e_remark;?>">
                        </div>
                    </div> 
                    </div>                 
                     
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Warna</th>
                                    <th>Kode Bahan Baku</th>
                                    <th>Nama Bahan Baku</th>
                                    <th>Qty</th>
                                    <th>Gelar</th>
                                    <th>Set</th>
                                    <th>Jml Gelar</th>
                                    <th>Panjang Kain</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                                foreach ($datadetail as $row) {
                                $i++;?>
                                <tr>
                                <td>
                                    <?php echo $i;?>
                                </td>              
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1" >  
                                    <input style ="width:300px" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly class="form-control">
                                     <input style ="width:200px" type="hidden" id="fbisbisan<?=$i;?>" name="fbisbisan<?=$i;?>"value="<?= $row->f_bisbisan; ?>" readonly> 
                                </td>
                                <td class="col-sm-1" >  
                                    <input style ="width:100px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly>
                                    <input style ="width:100px" type="text" id="icolorname<?=$i;?>" name="icolorname<?=$i;?>"value="<?= $row->warna; ?>" readonly class="form-control">    
                                </td>
                                <td class="col-sm-1" >  
                                <input style ="width:200px" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td class="col-sm-1" >  
                                    
                                    <input style ="width:300px" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly class="form-control">                       
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" onkeyup="hitungnilai3(this.value,'$i')">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" type="text" id="vgelar<?=$i;?>" name="vgelar<?=$i;?>"value="<?= $row->n_gelar; ?>" readonly class="form-control">
                                </td> 
                                <td class="col-sm-1">
                                    <input style ="width:70px" type="text" id="vset<?=$i;?>" name="vset<?=$i;?>"value="<?= $row->n_set; ?>" readonly class="form-control">
                                </td> 
                                <td class="col-sm-1">
                                    <input style ="width:70px" class="form-control" type="text" id="jumgelar<?=$i;?>" name="jumgelar<?=$i;?>"value="<?= $row->jumlah_gelar; ?>" readonly onkeyup=hitungnilai2(this.value);>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" class="form-control" type="text" id="pjgkain<?=$i;?>" name="pjgkain<?=$i;?>"value="<?= $row->panjang_kain; ?>" readonly onkeyup=hitungnilai(this.value);>
                                </td>     
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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
 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });

$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});
    
function hitungnilai(isi,jml){   
        i=jml;
        pjgkain=formatulang(document.getElementById("pjgkain"+i).value);
        ngelar=formatulang(document.getElementById("vgelar"+i).value);
        nset=formatulang(document.getElementById("vset"+i).value);
        if(pjgkain=='')pjgkain=0;
        gelar=((parseFloat(pjgkain)*100)/parseFloat(ngelar))/100;
        hasil=(((parseFloat(pjgkain)*100)/parseFloat(ngelar))*parseFloat(nset))/100;
        document.getElementById("jumgelar"+i).value=(gelar).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 

  function hitungnilai2(isi,jml){   
        i=jml;
        jumgelar=formatulang(document.getElementById("jumgelar"+i).value);
        vgelar=formatulang(document.getElementById("vgelar"+i).value);
        vset=formatulang(document.getElementById("vset"+i).value);
        if(jumgelar=='')jumgelar=0;
        pjgkain=parseFloat(jumgelar)*parseFloat(vgelar);
        hasil=parseFloat(jumgelar)*parseFloat(vset);
        document.getElementById("pjgkain"+i).value=(pjgkain).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 

  function hitungnilai3(isi,jml){   
      i=jml;   
      qty=formatulang(document.getElementById("nquantity"+i).value);
      alert(qty);
      vgelar=formatulang(document.getElementById("vgelar"+i).value);
      vset=formatulang(document.getElementById("vset"+i).value);
      if(qty=='')qty=0;
      jmlgelar=parseFloat(qty)/parseFloat(vset);
      pjngkain=(parseFloat(qty)/parseFloat(vset))*parseFloat(vgelar);
      document.getElementById("jumgelar"+i).value=(jmlgelar).toFixed(2);
      document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
  } 
</script>