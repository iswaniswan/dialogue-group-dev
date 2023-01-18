<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Level Perusahaan</label>
                        <label class="col-md-8">Nama Level Perusahaan</label>
                        <div class="col-sm-4">

                            <input type="text"  id="ilevel" name="ilevel" class="form-control" required="" autocomplete="off" maxlength="15" onkeyup="gede(this);clearcode(this);" value="<?= $data->i_level; ?>" readonly>

                             <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                         <div class="col-sm-8">
                            <input type="text" name="elevel" class="form-control" required="" onkeyup="gede(this);clearname(this);" value="<?= $data->e_level_name; ?>" readonly>
                        </div>
                    </div>                                 
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                             <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                     <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode terdiri dari 5 (lima) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama </span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada kode sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : PLV01, PLV02, dst</span>
                    </div>
            </div>
        </div>
    </div>
</div>