<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/transfer'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <td align="center" style="font-weight:bold">NO</td>
                                <td align="center" style="font-weight:bold">NODOK</td>
                                <td align="center" style="font-weight:bold">TGLDOK</td>
                                <td align="center" style="font-weight:bold">KDLANG</td>
                                <td align="center" style="font-weight:bold">KDPROD</td>
                                <td align="center" style="font-weight:bold">JUMLAH</td>
                                <td align="center" style="font-weight:bold">HARGA</td>
                                <td align="center" style="font-weight:bold">NOOP</td>
                                <td align="center" style="font-weight:bold">WILA</td>
                                <td align="center" style="font-weight:bold">LANG</td>
                                <td align="center" style="font-weight:bold">Pilih</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $konek 	= "host=192.168.0.93 user=dedy dbname=distributor port=5432 password=g#>m[J2P^^";
                            $db    	= pg_connect($konek);
                            $sql	= " select d_do, '0'||substring(i_do_code,5,5) as i_do_code, i_branch as i_branch, 
                                        i_product, n_deliver, v_do_gross, i_op_code as i_op, d_op, i_do_code as no_do
                                        from duta_prod.tm_trans_do
                                        where d_do >= to_date('$dfrom','dd-mm-yyyy') AND d_do <= to_date('$dto','dd-mm-yyyy') and f_transfer='f'
                                        and i_do_code not like 'SAB%'
                                        order by d_do, i_do_code, i_product";
                            $rs		= pg_query($sql);
                            $no=0;
                            $i=0;
                            //$riw='';
                            while($row=pg_fetch_assoc($rs)){
                            $ido    = $row['i_do_code'];
                            $nodo   = $row['no_do'];
                            $xop    = $row['i_op'];
                                $ddo	 	= $row['d_do'];
                                if($ddo!=''){
                                    $tmp=explode("-",$ddo);
                                    $th=$tmp[0];
                                    $bl=$tmp[1];
                                    $hr=$tmp[2];
                                    $ddo=$hr."-".$bl."-".$th;
                                }
                            $dop	 	= $row['d_op'];
                            if($dop!=''){
                                    $tmp=explode("-",$dop);
                                    $th=$tmp[0];
                                    $bl=$tmp[1];
                                    $hr=$tmp[2];
                                    $dop=$hr."-".$bl."-".$th;
                                }
                                $lang	 	= 'SP030';
                                $prod	 	= $row['i_product'];
                                $juml	 	= $row['n_deliver'];
                                $vdo	 	= $row['v_do_gross'];
                            $vdo    = $vdo/$juml;
                                $iop	 	= $row['i_op'];
                                settype($iop,"string");
                                
                                $iop='0'.substr($iop,4,5);
                                $wila	 	= $row['i_branch'];
                                /*if($riw%2==1){
                                    $bgcolor='#F4D39E';
                                }else{
                                    $bgcolor='#80c0e0';
                                }*/
                                /*echo "<tr bgcolor=\"$bgcolor\"
                                onMouseOver=\"this.bgColor='#1BE1DB';\" onMouseOut=\"this.bgColor='$bgcolor';\">";
                                $riw++;*/
                                $i++;
                                $no		= $i;
                                $noarr	= $no-1;
                                echo '<td style="width:50px;"><input readonly style="text-align:center;" type="text" class="form-control" id="nodok'.$noarr.'" name="nodok'.$noarr.'" value="'.$no.'"></td>';
                                echo '<td><input style="text-align:center;" readonly type="text" class="form-control" id="nodok'.$noarr.'" name="nodok'.$noarr.'" value="'.$ido.'"></td>';
                                echo '<td><input style="text-align:center;" readonly type="text" class="form-control" id="tgldok'.$noarr.'" name="tgldok'.$noarr.'" value="'.$ddo.'"><input type="hidden" id="tglop'.$noarr.'" name="tglop'.$noarr.'" value="'.$dop.'"><input type="hidden" id="nodo'.$noarr.'" name="nodo'.$noarr.'" value="'.$nodo.'"><input type="hidden" id="xop'.$noarr.'" name="xop'.$noarr.'" value="'.$xop.'"></td>';
                                echo '<td><input style="text-align:center;" readonly type="text" class="form-control" id="kodelang'.$noarr.'" name="kodelang'.$noarr.'" value="'.$lang.'"></td>';
                                echo '<td><input style="text-align:center;" readonly type="text" class="form-control" id="kodeprod'.$noarr.'" name="kodeprod'.$noarr.'" value="'.$prod.'"></td>';
                                $juml=number_format($juml);
                                echo '<td style="width:50px;"><input style="text-align:center;" readonly type="text" class="form-control" id="jumlah'.$noarr.'" name="jumlah'.$noarr.'" value="'.$juml.'"></td>';
                                $vdo=number_format($vdo);
                                echo '<td><input style="text-align:center;" type="text" readonly class="form-control" id="hargasat'.$noarr.'" name="hargasat'.$noarr.'" value="'.$vdo.'"></td>';
                                echo '<td><input style="text-align:center;" class="form-control" readonly type="text" id="noop'.$noarr.'" name="noop'.$noarr.'" value="'.$iop.'"></td>';
                                echo '<td style="width:50px;"><input style="text-align:center;" readonly class="form-control" type="text" id="wila'.$noarr.'" name="wila'.$noarr.'" value="'.$wila.'"></td>';
                                echo '<td><input class="form-control" style="text-align:center;" readonly type="text" id="lang'.$noarr.'" name="lang'.$noarr.'" value="'.$lang.'"></td>';
                                echo '<td style="align:center;">
                                      <input id="jml" name="jml" value="'.$no.'" type="hidden">
                                      <label class="custom-control custom-checkbox">
                                      <input  style="checkbox-align:center;" type="checkbox" id="chk" name="chk'.$noarr.'" class="custom-control-input">
                                      <span class="custom-control-indicator"></span><span class="custom-control-description"></span></td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                    <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                        <br><br>
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Transfer</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                        <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                        <input type="hidden" id="jml"	name="jml" 	value="<?php echo $no; ?>">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Check All</span>
                        </label>
                        &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                 </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>