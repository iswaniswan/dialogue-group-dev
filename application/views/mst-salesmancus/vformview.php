<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label>
                        <label class="col-md-6">Customer</label>
                        <!-- <label class="col-md-4">Salesman</label> -->
                        <div class="col-sm-6">
                            <input type="hidden" id="id" name="id" value="<?php echo $data->id;?>">
                            <select name="iarea" id="iarea" class="form-control select2" disabled="">
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2" disabled="">
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                        </div>
                        <!-- <div class="col-sm-4">
                            <select name="isalesman" id="isalesman" class="form-control select2" disabled="">
                                <option value="<?=$data->id_salesman;?>"><?=$data->e_sales;?></option>
                            </select>
                        </div> -->
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Salesman</label>
                        <label class="col-md-6">Periode</label>
                        <div class="col-sm-6">
                            <select name="isalesman" id="isalesman" class="form-control select2" disabled="">
                                <option value="<?=$data->id_salesman;?>"><?=$data->e_sales;?></option>
                            </select>
                            <!-- <select name="ibrand" id="ibrand" class="form-control select2" disabled="">
                                <option value="<?=$data->id_brand;?>"><?=$data->e_brand_name;?></option>
                            </select> -->
                        </div>
                         <div class="col-sm-4">
                                <input type="hidden" id="iperiode" name="iperiode" value="<?php echo $data->e_periode;?>">
                                <?php 
                                    $thn = substr($periode,0,4);
                                    $bln = substr($periode,4,2);
                                ?>
                                <select name="bulan" id="bulan" class="form-control select2" disabled="">
                                    <option value="01"<?php if($bln=='01') echo ' selected'; ?>>Januari</option>
                                    <option value="02"<?php if($bln=='02') echo ' selected'; ?>>Pebruari</option>
                                    <option value="03"<?php if($bln=='03') echo ' selected'; ?>>Maret</option>
                                    <option value="04"<?php if($bln=='04') echo ' selected'; ?>>April</option>
                                    <option value="05"<?php if($bln=='05') echo ' selected'; ?>>Mei</option>
                                    <option value="06"<?php if($bln=='06') echo ' selected'; ?>>Juni</option>
                                    <option value="07"<?php if($bln=='07') echo ' selected'; ?>>Juli</option>
                                    <option value="08"<?php if($bln=='08') echo ' selected'; ?>>Agustus</option>
                                    <option value="09"<?php if($bln=='09') echo ' selected'; ?>>September</option>
                                    <option value="10"<?php if($bln=='10') echo ' selected'; ?>>Oktober</option>
                                    <option value="11"<?php if($bln=='11') echo ' selected'; ?>>November</option>
                                    <option value="12"<?php if($bln=='12') echo ' selected'; ?>>Desember</option>
                                </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="tahun" id="tahun" class="form-control select2" disabled="">
                                    <?php 
                                        echo "<option value='$thn'>$thn</option>";
                                    ?>
                                    <?php 
                                        $tahun1 = date('Y')-3;
                                        $tahun2 = date('Y');
                                        for($i=$tahun1;$i<=$tahun2;$i++)
                                        {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>