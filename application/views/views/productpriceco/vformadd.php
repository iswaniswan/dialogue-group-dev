<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group">
                    <label class="col-md-12">Kode Barang</label>
                    <div class="col-sm-12">
                        <select name="iproduct" id="iproduct" class="form-control" required>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Nama Barang</label>
                    <div class="col-sm-12">
                        <input type="text" id="eproductname" name="eproductname" class="form-control" required=""
                        readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Kode Grade</label>
                    <div class="col-sm-12">
                        <select name="iproductgrade" id="iproductgrade" class="form-control select2" required>
                            <option value="A">GRADE A</option>
                            <option value="B">GRADE B</option>
                        </select>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Harga</th>
                            <th>Harga Counter</th>
                            <th>Harga Netto</th>
                            <th>Kode Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input class="form-control" name="ipricegroup" id="ipricegroup" type="text" maxlength='2' onkeyup="gede(this);" required></td>
                            <td><input class="form-control" name="vproductretail" id="vproductretail" onkeyup="hitung();" autocomplete="off" type="number" min="0" required></td>
                            <td><input class="form-control" readonly name="vproductmill" id="vproductmill" value="" onkeyup="reformat(this);" required="">
                                <input type='hidden' name="nmargin" id="nmargin" value="">
                            </td>
                            <td><select name="ipricegroupco" id="ipricegroupco" class="form-control select2" required="" onchange="getharga(this.value);">
                                <option>-- Pilih Group --</option>
                                <?php if($data_groupco->num_rows() > 0){
                                    foreach ($data_groupco->result() as $row) { ?>
                                        <option value="<?= $row->i_price_groupco; ?>"><?= $row->e_price_groupconame; ?></option>
                                    <?php } } ?> 
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
            </form>
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
        $('.select2').select2();
        $('#iproduct').select2({
            placeholder: 'Pilih Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).on("change", function(e) {
            var kode = $('#iproduct').text();
            kode = kode.split("-");
            $('#eproductname').val(kode[1]);
        });
    });

    function hitung(){        
        if(document.getElementById("vproductretail").value!='' && document.getElementById("nmargin").value!=''){          
            retail=document.getElementById("vproductretail").value;          
            margin=document.getElementById("nmargin").value;          
            x=(retail*margin)/100;          
            z=retail-x;      
            document.getElementById("vproductmill").value=z;//formatcemua(z);
        }
    }

    function getharga(igroup){
        $.ajax({
            type: "post",
            data: {
                'igroup': igroup
            },
            url: '<?= base_url($folder.'/cform/getharga'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipricegroupco').val(data[0].i_price_groupco);
                $('#nmargin').val(data[0].n_margin);
                hitung();
            },
            error: function () {
                alert('Error :)');
            }
        });
    }
</script>