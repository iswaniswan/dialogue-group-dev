<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No SPB</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Area</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(document).ready(function () {
            datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto."/".$iarea; ?>');
        });
    });

    function refreshview() {
        show('<?= $folder;?>/cform/view/<?= $iarea.'/'.$dfrom.'/'.$dto;?>','#main');
    }

  function yyy(b,c){
    lebar =450;
    tinggi=400;
    eval('window.open("<?php echo site_url(); ?>"+"/printsjkhusus/cform/cetak/"+b,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
  function xxx(b,c){
    lebar =450;
    tinggi=400;
    eval('window.open("<?php echo site_url(); ?>"+"/printsjkhusus/cform/cetakinclude/"+b,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }
</script>