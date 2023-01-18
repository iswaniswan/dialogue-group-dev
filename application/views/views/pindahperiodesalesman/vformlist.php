<?php 
    $ab = array(
        "1Januari","2Februari","3Maret","4April","5Mei","6Juni","7Juli","8Agustus","9September","10Oktober","11November","12Desember",
    );
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/pindah'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-md-12">Periode Lama</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="blama">
                            <?php 
                                $angka=1;
                                for($i=0;$i<count($ab);$i++){
                                    if($angka > 9){
                                        $angka = substr($ab[$i],0,2);
                                        $bulan = substr($ab[$i],2);
                                    }else{
                                        $angka = substr($ab[$i],0,1);
                                        $bulan = substr($ab[$i],1);
                                    }
                                    echo "<option value='".$angka."'>".$bulan."</option>";
                                    $angka++;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-control" name="tlama">
                            <?php for($x='2016'; $x<=date('Y'); $x++){ ?>
                            <option value='<?= $x ?>'><?= $x ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Periode Baru</label>
                    <div class="col-sm-12">
                        <select class="form-control" name="bbaru">
                            <?php 
                                $angka=1;
                                for($i=0;$i<count($ab);$i++){
                                    if($angka > 9){
                                        $angka = substr($ab[$i],0,2);
                                        $bulan = substr($ab[$i],2);
                                    }else{
                                        $angka = substr($ab[$i],0,1);
                                        $bulan = substr($ab[$i],1);
                                    }
                                    echo "<option value='".$angka."'>".$bulan."</option>";
                                    $angka++;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-control" name="tbaru">
                            <?php for($x='2016'; $x<=date('Y'); $x++){ ?>
                            <option value='<?= $x ?>'><?= $x ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-plus"></i>&nbsp;&nbsp;Pindah</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>