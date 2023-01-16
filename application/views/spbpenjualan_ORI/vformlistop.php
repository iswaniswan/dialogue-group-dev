<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            <?php if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-list"></i> &nbsp;<?= $title_list; ?></a>
            <?php } ?>
        </div>            
        <div class="panel-body table-responsive">
            <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/transfer'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-10">
                <div class="form-group row">
                    <label class="col-md-2">Date OP From</label><label class="col-md-2">Date OP To</label><label class="col-md-8">Distributor</label>
                    <div class="col-sm-2">
                        <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" >
                    </div>
                    <div class="col-sm-4">
                        <select name="idcustomer" id="idcustomer" class="form-control select2" data-placeholder="Pilih Distributor">
                            <option value="SD" selected>SEMUA DISTRIBUTOR</option>
                            <?php if ($customer) {
                                foreach ($customer as $row) {
                                    if ($row->id == $idcustomer) { ?>
                                        <option value="<?= $row->id;?>" selected><?= $row->e_customer_name;?></option>
                                    <?php }else { ?>
                                        <option value="<?= $row->id;?>"><?= $row->e_customer_name;?></option>
                                    <?php }
                                }
                            } ?>
                        </select>
                        <!-- <input class="form-control" readonly="" type="hidden" name="icustomer" id="icustomer" value="<?= $customer;?>" onchange="return cekout(this.value);"> -->
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-sm btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                    </div>
                </div>
            </div>
        </form>
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/prosesdata'), 'update' => '#main', 'type' => 'post', 'id' => 'formclose', 'class' => 'form-horizontal')); ?>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="3%;">No</th>
                    <th>No. OP</th>
                    <th>Tgl. OP</th>
                    <th>Distributor</th>
                   <!--  <th>TOP</th> -->
                    <th>Area</th>
                    <th>Keterangan</th>
                    <th width="3%;">Act</th>
                </tr>
            </thead>
            <tbody>                
            </tbody>
        </table>
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                &nbsp;&nbsp;
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Check All</span>
                </label>
            </div>
        </div>
    </form>
        <div class="col-md-12">
            <div class="form-group">
                <span class="notekode"><b>N O T E : </b></span><br>
                <span class="notekode">* Data Order Pembelian (OP) Yang Akan Diproses Harus Distributor Yang Sama!</span><br>
                <span class="notekode">* Data Order Pembelian (OP) Yang Ditampilkan, Hanya Yang Belum Ada Direferensi Surat Pesanan Barang (SPB) Distributor!</span><br>
                <span class="notekode">* Transfer Surat Pesanan Barang (SPB) Distributor, Tidak Bisa Beda Halaman!</span><br>
                <span class="notekode">* Untuk Yang Berbeda Halaman, Silahkan Rubah <b>"Tampilkan Jumlah Data"</b> Yang Dimunculkan!</span>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',null,0);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/datatransfer/<?= $dfrom.'/'.$dto.'/'.$idcustomer;?>');
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    $("#submit").click(function(event) {
        if ($("#formclose input:checkbox:checked").length > 0){
            return true;
        }else{
            swal('Maaf :(','Pilih data minimal satu!','error');
            return false;
        }
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>