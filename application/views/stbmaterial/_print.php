<div class="container-fluid pt-4">
    <!-- cetak kop hanya di halaman pertama -->
    <?php if ($index == 1) { ?>

        <section id="kop" style="border: 1px solid #333; background: #c9c9c9!important">
            <div class="row">
                <div class="col-3 p-2 text-center" style="border-right: 1px solid #333;">
                    <img src="<?= base_url() . 'assets/images/logo/' .$company->logo ?>" class="img-fluid" alt="logo-perusahaan"/>
                </div>
                <div class="col-9 p-4 text-center">
                    <b>FORMULIR <br/>SERAH TERIMA BARANG (STB)</b>
                </div>
            </div>
        </section>          
        <section id="header" class="border-side border-bottom">
            <div class="row">
                <div class="col-3 text-center border-right">
                    <div>Nomor Dokumen</div>
                    <div>Form/QAS/001/01</div>
                </div>
                <div class="col-3 text-center border-right">
                    <div>REVISI</div>
                    <div>00</div>
                </div>
                <div class="col-3 text-center border-right">
                    <div>Tanggal Efektif</div>
                    <div>1 Januari 2023</div>
                </div>
                <div class="col-3 text-center">
                    <div>Halaman </div>
                    <div>1 dari 1</div>
                </div>
            </div>
        </section>

    <?php } ?>

    <?php $border_bottom = ($index != $total_pages) ? 'border-bottom' : ''; ?>
    <section id="body" class="border-side border-top <?= $border_bottom ?>">        
        <div class="row ml-4 mr-4 pt-4 mb-4">
            <div class="col-6">
                <div class="row">
                    <div class="col-2"><b>Dari</b></div>
                    <div class="col">: <?= $data->e_bagian_name ?></div>
                </div>
                <div class="row">
                    <div class="col-2"><b>Untuk</b></div>
                    <?php $e_bagian_receive = $data->e_bagian_receive_name ?>
                    <?php if (strpos($data->i_document, "SJ") !== false) { 
                        $e_bagian_receive .= " - $data->e_company_receive_name";
                    } ?>
                    <div class="col">: <?= $e_bagian_receive ?></div>
                </div>
                <div class="row">
                    <div class="col-2"><b>Tanggal</b></div>
                    <div class="col">: <?= $data->date_document ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-3" style="margin-left: 80px"><b style="margin-left: 2px">No. Urut</b></div>
                    <div class="col">: <?= $no_urut ?></div>
                </div>
                <div class="row">
                    <div class="col-3" style="margin-left: 80px"><b style="margin-left: 2px">Hal</b></div>
                    <div class="col">: <?= "$index dari $total_pages" ?></div>                    
                </div>
                <div class="row">
                    <?php $jenis = "STB"; if (strpos($data->i_document, $jenis) === false) { $jenis = 'SJ'; } ?>
                    <div class="col-3" style="margin-left: 80px"><b style="margin-left: 2px">Jenis</b></div>
                    <div class="col">: <?= $jenis ?></div>
                </div>
            </div>
        </div>
        <div class="row ml-4 mr-4">
            <div class="col-12">
                <p>Bersama ini dikirimkan barang dengan spesifikasi sebagai berikut:</p>
            </div>
        </div>
        <div class="row ml-4 mr-4">
            <div class="container-fluid table-responsive">
                <table class="table table-hover table-bordered">
                    <thead style="background: #f8f9fa!important">
                        <tr>
                            <th style="width: 35px;">No</th>
                            <th style="width: 55px;">Kode</th>
                            <th style="width: auto;">Nama Barang</th>
                            <th style="width: 55px;">Jumlah</th>
                            <th style="width: 55px;">Satuan</th>
                            <th style="width: 200px;">Keterangan</th>
                        </tr>    
                    </thead>
                    <tbody>
                    <?php $no=1; 
                        if ($index > 1) { 
                            $no = ($index-1) * $page_break + 1;
                        }

                        $last_row_id = null;

                        foreach($datadetail as $item) { ?>

                        <?php if ($item['show_as_product'])  { ?>
                            <tr>
                                <td colspan="2"><b><?= $item['i_product_wip'] ?></b></td>
                                <td colspan="4"><b><?= $item['e_product_wipname']. ' - ' .$item['e_color_name'] ?></b></td>
                            </tr>                            
                        <?php } else { ?>
                            <tr>
                                <td><?= @$item['seq'] ?></td>
                                <td><?= $item['i_material'] ?></td>
                                <td><?= $item['e_material_name'] ?></td>
                                <td><?= number_format($item['n_quantity'], 2, ".", ",") ?></td> 
                                <td><?= $item['i_satuan_code']; ?></td>
                                <td><?= $item['e_remark']; ?></td>
                            </tr>
                        <?php } ?>

                    <?php $no++ ; } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row ml-4">
            <div class="col-12 mb-5">
                <p>Mohon diperiksa kembali sebelum dilakukan penerimaan barang</p>
            </div>
        </div>
    </section>
    <?php if ($index == $total_pages) { ?>
        <section id="footer" class="border-side border-bottom pb-5">
            <div class="row justify-content-center">
                <div class="col-3 text-center p-4 mx-2" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Dibuat Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4 mx-2" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diketahui Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4 mx-2" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diterima Oleh,</h5>
                    <p>(_________________)</p>
                </div>
            </div>
        </section>
    <?php } ?>    
</div>
<?php if ($index != $total_pages) { ?>
    <div class="page-break"></div>
<?php } ?>
