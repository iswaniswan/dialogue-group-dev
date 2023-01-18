<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>                    
                <div class="form-group">
                        <label class="col-md-12">Kode Warna WIP</label>
                        <div class="col-sm-6">
                            <input type="text" name="iwarna" class="form-control" maxlength="5"  value="<?= $data->i_warna; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikodebrg" class="form-control" maxlength="60"  value="<?= $data->i_kodebrg.'-'.$data->e_namabrg; ?>" readonly>
                        </div>
                    </div> 
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Warna</th>                          
                        </tr>
                    </thead>
                    <tbody>
                        <?$i = 0;
                            foreach ($data2 as $row) {
                            $i++;?>
                            <tr>
                            <td class="col-sm-1" >  
                                <input type="text" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color.'-'.$row->nama; ?>" readonly>                            
                            </td>   
                            </tr>                            
                            <?}?>
                    </tbody>
                </table>
            </div>            
        </form>
    </div>
</div>
<script> 
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>