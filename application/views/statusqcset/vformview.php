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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">No Permintaan</label>
                        <div class="col-sm-12">                          
                           <input type="text" name="ispbb" class="form-control" value="<?= $data->i_spbb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Permintaan</label>
                        <div class="col-sm-12">
                            <input type="text" name="dspbb" class="form-control date" value="<?= $data->d_spbb;?>" readonly>
                        </div>
                    </div>    
                     <div class="form-group">
                        <label class="col-md-12">No Schedule</label>
                        <div class="col-sm-12">                          
                           <input type="text" name="ischedule" class="form-control" value="<?= $data->i_schedule;?>" readonly>
                           <input type="hidden" name="dschedule" class="form-control" value="<?= $data->d_schedule;?>" readonly>
                        </div>
                    </div> 
                    </div>
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="igudang" class="form-control" value="<?= $data->i_gudang;?>">
                             <input type="text" name="igudangfake" class="form-control" value="<?= $data->gudang;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremarkh" class="form-control" value="<?= $data->e_remark;?>" readonly>
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
                                    <?php echo $row->i_product;?>
                                </td>
                                <td class="col-sm-1" >
                                    <?php echo $row->e_product_name;?>  
                                </td>
                                <td class="col-sm-1" >  
                                    <?php echo $row->warna;?>  
                                </td>
                                <td class="col-sm-1" >  
                                    <?php echo $row->e_material_name;?>                       
                                </td>
                                <td class="col-sm-1">
                                    <?php echo $row->n_quantity;?>
                                </td>
                                <td class="col-sm-1">
                                    <?php echo $row->n_gelar;?>
                                </td> 
                                <td class="col-sm-1">
                                    <?php echo $row->n_set;?>
                                </td> 
                                <td class="col-sm-1">
                                    <?php echo $row->jumlah_gelar;?>
                                </td>
                                <td class="col-sm-1">
                                    <?php echo $row->panjang_kain;?>
                                </td>     
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
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