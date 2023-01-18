<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
        </div>
        <div class="white-box">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>
            <h1 class="box-title m-b-0" style="text-align: center;"><?= "BERITA ACARA PEMERIKSAAN KAS";?></h1>
            <table class="tablesaw table-bordered table-hover table" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="5" style="text-align: center;" style="color: black;">
                        <b>Nama Perusahaan : <?= $nama_company; ?><br>
                            Tanggal : <?= $date;?><br>
                            Waktu : <?= $time;?></b>
                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td rowspan="9"><label class="control-label">Uang Kertas</label></td>
                        <td style="text-align: center;"><label class="control-label">Pecahan</label></td>
                        <td style="text-align: center;"><label class="control-label">Jumlah (LBR/KPG)</label></td>
                        <td style="text-align: center;">&nbsp;</td>
                        <td style="text-align: center;"><label class="control-label">Total Rupiah</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">100.000</label></td>
                        <td style="text-align: right;">
                            <input class="form-control" id="txtkertas100000" name="txtkertas100000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto($('#txtkertas100000').val(), '100000','totalkertas100000','hdtotalkertas100000');"  value="0">
                        </td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas100000" name="totalkertas100000" disabled value="0" >
                                <input type="hidden" id="hdtotalkertas100000" name="hdtotalkertas100000">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">50.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas50000" name="txtkertas50000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas50000').value, '50000','totalkertas50000','hdtotalkertas50000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas50000" name="totalkertas50000" disabled value="0">
                                <input type="hidden" id="hdtotalkertas50000" name="hdtotalkertas50000">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">20.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas20000" name="txtkertas20000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas20000').value, '20000','totalkertas20000','hdtotalkertas20000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas20000" name="totalkertas20000" value="0" disabled >
                            </div>

                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas20000" name="hdtotalkertas20000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">10.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas10000" name="txtkertas10000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas10000').value, '10000','totalkertas10000','hdtotalkertas10000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas10000" name="totalkertas10000" value="0" disabled >
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas10000" name="hdtotalkertas10000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">5.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas5000" name="txtkertas5000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas5000').value, '5000','totalkertas5000','hdtotalkertas5000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas5000" name="totalkertas5000" value="0" disabled >
                            </div>

                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas5000" name="hdtotalkertas5000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">2.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas2000" name="txtkertas2000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas2000').value, '2000','totalkertas2000','hdtotalkertas2000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas2000" name="totalkertas2000" value="0" disabled >
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas2000" name="hdtotalkertas2000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">1.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas1000" name="txtkertas1000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas1000').value, '1000','totalkertas1000','hdtotalkertas1000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas1000" name="totalkertas1000" value="0" disabled>
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas1000" name="hdtotalkertas1000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">500</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtkertas500" name="txtkertas500" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtkertas500').value, '500','totalkertas500','hdtotalkertas500');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkertas500" name="totalkertas500" value="0" disabled >
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotalkertas500" name="hdtotalkertas500">
                    </tr>
                    <tr align = "center">
                        <td rowspan="6"><label class="control-label">Uang Logam</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">1.000</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtlogam1000" name="txtlogam1000" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtlogam1000').value, '1000','totallogam1000','hdtotallogam1000');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallogam1000" name="totallogam1000" value="0" disabled value="0">
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotallogam1000" name="hdtotallogam1000">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">500</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtlogam500" name="txtlogam500" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtlogam500').value, '500','totallogam500','hdtotallogam500');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallogam500" name="totallogam500" value="0" disabled>
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotallogam500" name="hdtotallogam500">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">200</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtlogam200" name="txtlogam200" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtlogam200').value, '200','totallogam200','hdtotallogam200');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallogam200" name="totallogam200" value="0" disabled>
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotallogam200" name="hdtotallogam200">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">100</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtlogam100" name="txtlogam100" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtlogam100').value, '100','totallogam100','hdtotallogam100');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallogam100" name="totallogam100" value="0" disabled>
                            </div>
                        </td>
                        <input class="form-control" type="hidden" id="hdtotallogam100" name="hdtotallogam100">
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">50</label></td>
                        <td style="text-align: right;"><input class="form-control" id="txtlogam50" name="txtlogam50" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="return hitungkananauto(document.getElementById('txtlogam50').value, '50','totallogam50','hdtotallogam50');" value="0"></td>
                        <td style="text-align: center;"><label class="control-label">=</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallogam50" name="totallogam50" value="0" disabled>
                            </div>
                        </td>
                        <input type="hidden" id="hdtotallogam50" name="hdtotallogam50">
                    </tr>
                    <tr align = "center">
                        <td rowspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">Saldo Kas Di ATM</label></td>
                        <td style="text-align: center;">&nbsp;</td>
                        <td style="text-align: center;">
                            <label class="control-label">&nbsp;&nbsp;</label>
                        </td>                    
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalatm" name="totalatm" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalfisik()" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><label class="control-label">Lain-Lain</label></td>
                        <td style="text-align: center;">&nbsp;</td>
                        <td style="text-align: center;"><label class="control-label">&nbsp;&nbsp;</label></td>               
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totallain" name="totallain" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalfisik()" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4"><label class="control-label col-md-4"">TOTAL PHISIK UANG</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalphisik" name="totalphisik" value="0" disabled>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-4">NOTA BELUM DIBIAYAKAN DARI</label>
                            <div class="col-md-4">
                                <input name="dfrom1" id="dfrom1" readonly="" class="form-control date"> 
                            </div>
                            <div class="col-md-4">
                                <input name="dto1" id="dto1" readonly="" class="form-control date">
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalnota" name="totalnota" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungdana()" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <label class="control-label col-md-4">KASBON GANTUNG</label>
                            <div class="col-md-4">
                                <input name="dfrom2" id="dfrom2" readonly="" class="form-control date"> 
                            </div>
                            <div class="col-md-4">
                                <input name="dto2" id="dto2" readonly="" class="form-control date">
                            </div>
                        </td>  
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalkasbon" name="totalkasbon" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungdana()" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><label class="control-label col-md-4"">TOTAL DANA YANG ADA</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totaldanaada" name="totaldanaada" value="0" disabled>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><label class="control-label col-md-4"">DANA SEHARUSNYA</label></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totaldanaharus" name="totaldanaharus" onkeypress="return hanyaAngka(event);" onpaste="return false;" value="0" onkeyup="hitungselisih()">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><label class="control-label col-md-4"">SELISIH LEBIH/KURANG</label></td>                
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalselisih" name="totalselisih" value="0" disabled>
                            </div>
                        </td>
                    </tr>
                    <tr>                    
                        <td colspan="4">
                            <label class="control-label col-md-4"">SALDO BANK TANGGAL</label>
                            <div class="col-md-4">
                                <input name="dfrombank" id="dfrombank" readonly="" class="form-control date"> 
                            </div>
                        </td>
                        <td colspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <label class="control-label col-md-3">REKENING 1 :</label>
                            <div class="col-md-9">
                                <select class="form-control" name = "rekbank1" id = "rekbank1">
                                    <option value="" selected>Pilih Bank</option>
                                    <?php if ($bankcoa) {
                                        foreach ($bankcoa as $key ) {
                                            echo "<option value='".$key->i_coa."+".$key->e_bank_name."'>".$key->e_bank_name."</option>";    
                                        } 
                                    } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="saldobank1" name="saldobank1" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalbank(document.getElementById('rekbank1').value, 'saldobank1');"  value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <label class="control-label col-md-3">REKENING 2 :</label>
                            <div class="col-md-9">
                                <select class="form-control" name = "rekbank2" id = "rekbank2" onchange="valbank();">
                                    <option value="" selected>Pilih Bank</option>
                                    <?php if ($bankcoa) {
                                        foreach ($bankcoa as $key ) {
                                            echo "<option value='".$key->i_coa."+".$key->e_bank_name."'>".$key->e_bank_name."</option>";    
                                        } 
                                    } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="saldobank2" name="saldobank2" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalbank(document.getElementById('rekbank2').value, 'saldobank2');" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <label class="control-label col-md-3">REKENING 3 :</label>
                            <div class="col-md-9">
                                <select class="form-control" name = "rekbank3" id = "rekbank3" onchange="valbank();">
                                    <option value="" selected>Pilih Bank</option>
                                    <?php if ($bankcoa) {
                                        foreach ($bankcoa as $key ) {
                                            echo "<option value='".$key->i_coa."+".$key->e_bank_name."'>".$key->e_bank_name."</option>";    
                                        } 
                                    } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="saldobank3" name="saldobank3" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalbank(document.getElementById('rekbank3').value, 'saldobank3');" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <label class="control-label col-md-3">REKENING 4 :</label>
                            <div class="col-md-9">
                                <select class="form-control" name = "rekbank4" id = "rekbank4" onchange="valbank();">
                                    <option value="" selected>Pilih Bank</option>
                                    <?php if ($bankcoa) {
                                        foreach ($bankcoa as $key ) {
                                            echo "<option value='".$key->i_coa."+".$key->e_bank_name."'>".$key->e_bank_name."</option>";    
                                        } 
                                    } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="saldobank4" name="saldobank4" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalbank(document.getElementById('rekbank4').value, 'saldobank4');" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <label class="control-label col-md-3">REKENING 5 :</label>
                            <div class="col-md-9">
                                <select class="form-control" name = "rekbank5" id = "rekbank5" onchange="valbank();">
                                    <option value="" selected>Pilih Bank</option>
                                    <?php if ($bankcoa) {
                                        foreach ($bankcoa as $key ) {
                                            echo "<option value='".$key->i_coa."+".$key->e_bank_name."'>".$key->e_bank_name."</option>";    
                                        } 
                                    } ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="saldobank5" name="saldobank5" onkeypress="return hanyaAngka(event);" onpaste="return false;" onkeyup="hitungtotalbank(document.getElementById('rekbank5').value, 'saldobank1');" value="0">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;"><b>TOTAL</b></td>
                        <td>
                            <label class="control-label col-md-1">Rp. </label>
                            <div class="col-md-11">
                                <input class="form-control" id="totalsaldobank" name="totalsaldobank" value="0" disabled>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: left;"><label class="control-label col-md-2">Keterangan Selisih</label></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="4"><textarea widht="828px" id="txtket" name="txtket" cols="50" rows="4"></textarea></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="5">
                            <button type="submit" id="simpan" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        showCalendar('.date');
    });

    var jmlpisik = 0;
    function hanyaAngka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function hitungkananauto(a,b,c,d){
        if ((a == null) || (a == '')){
            a = 0;
        }
        var hasil = parseInt(a) * parseInt(b);
        document.getElementById(c).value = formatcemua(hasil);
        document.getElementById(d).value = hasil;

        f = parseInt(document.getElementById('totalatm').value) + parseInt(document.getElementById('totallain').value); 

        g = parseInt(formatulang(document.getElementById('totalkertas100000').value))+      
        parseInt(formatulang(document.getElementById('totalkertas50000').value))+   
        parseInt(formatulang(document.getElementById('totalkertas20000').value))+       
        parseInt(formatulang(document.getElementById('totalkertas10000').value))+       
        parseInt(formatulang(document.getElementById('totalkertas5000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas2000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas1000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas500').value))+ 
        parseInt(formatulang(document.getElementById('totallogam1000').value))+ 
        parseInt(formatulang(document.getElementById('totallogam500').value))+      
        parseInt(formatulang(document.getElementById('totallogam200').value))+      
        parseInt(formatulang(document.getElementById('totallogam100').value))+      
        parseInt(formatulang(document.getElementById('totallogam50').value));       

        document.getElementById('totalphisik').value = formatcemua(f+g);
    }

    function hitungtotalfisik(){
        if((document.getElementById('totalatm').value == '') || (document.getElementById('totallain').value == '')){
            document.getElementById('totalatm').value = 0;
            document.getElementById('totallain').value = 0;
        }

        f = parseInt(document.getElementById('totalatm').value) + parseInt(document.getElementById('totallain').value); 

        g = parseInt(formatulang(document.getElementById('totalkertas100000').value))+      
        parseInt(formatulang(document.getElementById('totalkertas50000').value))+   
        parseInt(formatulang(document.getElementById('totalkertas20000').value))+       
        parseInt(formatulang(document.getElementById('totalkertas10000').value))+       
        parseInt(formatulang(document.getElementById('totalkertas5000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas2000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas1000').value))+        
        parseInt(formatulang(document.getElementById('totalkertas500').value))+ 
        parseInt(formatulang(document.getElementById('totallogam1000').value))+ 
        parseInt(formatulang(document.getElementById('totallogam500').value))+      
        parseInt(formatulang(document.getElementById('totallogam200').value))+      
        parseInt(formatulang(document.getElementById('totallogam100').value))+      
        parseInt(formatulang(document.getElementById('totallogam50').value));       

        formatcemua(parseInt(document.getElementById('totalatm').value)); 
        formatcemua(parseInt(document.getElementById('totallain').value)); 
        document.getElementById('totalphisik').value = formatcemua(f+g);
    }

    function hitungdana(){
        if((document.getElementById('totalnota').value == '') || (document.getElementById('totalkasbon').value == '')){
            document.getElementById('totalnota').value = 0;
            document.getElementById('totalkasbon').value = 0;
        }
        h = parseInt(document.getElementById('totalnota').value) + parseInt(document.getElementById('totalkasbon').value) + 
        parseInt(formatulang(document.getElementById('totalphisik').value)) ; 
        document.getElementById('totaldanaada').value = formatcemua(h)
    }

    function hitungselisih(){
        if(document.getElementById('totaldanaharus').value == '') {
            document.getElementById('totaldanaharus').value = 0;
        }

        i = parseInt(formatulang(document.getElementById('totaldanaada').value)) - parseInt(document.getElementById('totaldanaharus').value);
        document.getElementById('totalselisih').value = formatcemua(i);
    }

    function hitungtotalbank(a,b){
        if (a == '') {
            swal('Silahkan Pilih Bank Dahulu!');
            document.getElementById(b).value = 0;
        }
        console.log('coa' + a);
        console.log('isi' + b);


        banktotal = parseInt(document.getElementById('saldobank1').value) + 
        parseInt(document.getElementById('saldobank2').value) + 
        parseInt(document.getElementById('saldobank3').value) + 
        parseInt(document.getElementById('saldobank4').value) + 
        parseInt(document.getElementById('saldobank5').value);

        document.getElementById('totalsaldobank').value = formatcemua(banktotal);
    }

    function valbank(){
        var val = '';
        bank1 = document.getElementById('rekbank1').value;
        bank2 = document.getElementById('rekbank2').value;
        bank3 = document.getElementById('rekbank3').value;
        bank4 = document.getElementById('rekbank4').value;
        bank5 = document.getElementById('rekbank5').value;

        if (bank2 == bank1){
            swal('Anda telah memilih bank ini.');
            swal('hiji');
            document.getElementById('rekbank2').value = val;
        }
        else if ((bank3 == bank2) || (bank3 == bank1)){
            swal('Anda telah memilih bank ini.');
            swal('dua');
            document.getElementById('rekbank3').value = val;
        }
        else if ((bank4 == bank3) || (bank4 == bank2) || (bank4 == bank1) || (bank3 == bank2) || (bank3 == bank1)){
            swal('Anda telah memilih bank ini.');
            swal('tilu');
            document.getElementById('rekbank4').value = val;
        }
        else if ((bank5 == bank4) || (bank5 == bank3) || (bank5 == bank2) || (bank5 == bank1)){
            swal('Anda telah memilih bank ini.');
            swal('opat');
            document.getElementById('rekbank5').value = val;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#simpan").attr("disabled", true);
    });
</script>