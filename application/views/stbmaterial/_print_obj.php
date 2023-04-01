<div class="page-break"></div>
<div class="container pt-4">
    <section id="kop" style="border: 1px solid #333; background: #c9c9c9!important">
        <div class="row">
            <div class="col-3 p-4 text-center" style="border-right: 1px solid #333;">
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
                <div><?= $data->i_document ?></div>
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
                <div><?= $index ?> dari <?= $total_pages ?></div>
            </div>
        </div>
    </section>
    <?php $border_bottom = ($index != $total_pages) ? 'border-bottom' : ''; ?>
    <section id="body" class="border-side <?= $border_bottom ?>">
        <div class="row justify-content-end">
            <div class="col-3 mb-4">
                <?php /** no urut diambil dari ekor nomor dokumen */ 
                $suffix = substr($data->i_document, -4);                
                ?>
                <div>No. Urut <?= $suffix ?></div>
            </div>
        </div>
        <div class="row ml-4 mr-4 mr-4 mb-4">
            <div class="col-12">
                <div class="row">
                    <div class="col-1"><b>Dari</b></div>
                    <div class="col">: <?= $data->e_bagian_name ?></div>
                </div>
                <div class="row">
                    <div class="col-1"><b>Untuk</b></div>
                    <div class="col">: <?= $data->e_bagian_receive_name ?></div>
                </div>
                <div class="row">
                    <div class="col-1"><b>Tanggal</b></div>
                    <div class="col">: <?= $data->date_document ?></div>
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
                        foreach($datadetail as $item) { ?>

                        <?php if ($item->show_as_product)  { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><b><?= $item->i_product_wip ?></b></td>
                                <td colspan="4"><b><?= "$item->e_product_wipname - $item->e_color_name" ?></b></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $item->i_material ?></td>
                                <td><?= $item->e_material_name ?></td>
                                <td><?= number_format($item->n_quantity, 2, ".", ",") ?></td> 
                                <td><?= $item->i_satuan_code; ?></td>
                                <td><?= $item->e_remark; ?></td>
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
                <div class="col-3 text-center p-4" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Dibuat Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diketahui Oleh,</h5>
                    <p>(_________________)</p>
                </div>
                <div class="col-3 text-center p-4" style="border: 1px solid #333; min-height: 100px">
                    <h5 style="margin-bottom: 80px;">Diterima Oleh,</h5>
                    <p>(_________________)</p>
                </div>
            </div>
        </section>
    <?php } ?>    
</div>
