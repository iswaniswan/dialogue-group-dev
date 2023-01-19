<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>assets/logo.png">-->
    <title>Dialogue Group Apps Portal</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css?v=2" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=2" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url(); ?>assets/css/style.css?v=2" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/global.css?v=100" rel="stylesheet">
    <!-- color CSS -->
    <!-- <link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet"> -->
    <link href="<?= base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/bower_components/datatables/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/fixedColumns.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/plugins/bower_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/tablesaw-master/dist/tablesaw.css" rel="stylesheet">
    <!-- File Upload -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/bower_components/dropify/dist/css/dropify.min.css?v=2">

    <!-- FIXED COLUMN CSS BOOSTRAP -->
    <link href="<?= base_url() . 'assets/css/bootstrap-table.min.css'; ?>" rel="stylesheet">
    <link href="<?= base_url() . 'assets/css/bootstrap-table-fixed-columns.min.css'; ?>" rel="stylesheet">
    <!-- FIXED COLUMN CSS BOOSTRAP -->
    <!--</head>-->
    <!-- <style>
        .dropdown-submenu {
        position: relative;
        }

        .dropdown-submenu a::after {
        transform: rotate(-90deg);
        position: absolute;
        right: 6px;
        top: .8em;
        }

        .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-left: .1rem;
        margin-right: .1rem;
        }
    </style> -->
</head>

<!-- <body oncontextmenu='return false;'> -->

<body>

<div id="wrapper">
    <!-- Navigation -->
    <!-- <nav class="navbar navbar-default navbar-static-top m-b-0"> -->
    <nav class="navbar navbar-default m-b-0 navbar-expand-lg navbar-light bg-light" style="padding: 0;">
        <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="icon-grid"></i></a>
            <div class="top-left-part" style="width: 60px;"><a class="logo" href="<?= base_url(); ?>"><b><img src="<?= base_url(); ?>assets/small.png" alt="Codeig" /></b><span class="hidden-xs"></span></a></div>
            <!-- <ul class="nav navbar-top-links navbar-left hidden-xs">
                <li><a href="javascript:void(0)" class="open-close hidden-xs"><i class="icon-grid"></i></a></li>
                <li class="nav-item">
                <a class="nav-link" href="#">Link 1</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Link 2</a>
                </li>


                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Dropdown link
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Link 1</a>
                    <a class="dropdown-item" href="#">Link 2</a>
                    <a class="dropdown-item" href="#">Link 3</a>
                </div>
                </li>
            </ul> -->
            <ul class="nav navbar-top-links navbar-left hidden-xs">
                <!-- <li><a href="javascript:void(0)" class="open-close hidden-xs"><i class="icon-grid"></i></a></li> -->
                <!-- <li class="nav-item navbar-left">
                    <a class="nav-link" href="<?= base_url(); ?>" style="padding: 0 5px;">Dashboard</a>
                    </li> -->
                <?php if (get_menu()->num_rows() > 0) {
                    foreach (get_menu()->result() as $menuheader) {
                        if ($menuheader->i_parent == '0') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" style="padding: 0 5px;" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="<?= $menuheader->icon; ?> fa-fw text-white"></i>&nbsp;<?= $menuheader->e_menu; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <?php if (get_submenu($menuheader->i_menu)->num_rows() > 0) {
                                        foreach (get_submenu($menuheader->i_menu)->result() as $submenu) {
                                            if ($submenu->e_folder != "#") { ?>
                                                <li><a class="dropdown-item" href="#" onclick="show('<?= $submenu->e_folder; ?>/cform','#main'); return false;"><?= $submenu->e_menu; ?></a></li>
                                            <?php } else { ?>
                                                <li class="dropdown-submenu">
                                                    <a class="dropdown-item dropdown-toggle" href="#"><?= $submenu->e_menu; ?></a>
                                                    <ul class="dropdown-menu sub-menu">
                                                        <?php if (get_submenu($submenu->i_menu)->num_rows() > 0) {
                                                            foreach (get_submenu($submenu->i_menu)->result() as $subsubmenu) {
                                                                if ($subsubmenu->e_folder != '#') { ?>
                                                                    <li><a class="dropdown-item" href="#" onclick="show('<?= $subsubmenu->e_folder; ?>/cform','#main'); return false;" data-placement="bottom" title="<?= $subsubmenu->e_menu; ?>"><?= $subsubmenu->e_menu; ?></a></li>
                                                                <?php } else { ?>
                                                                    <li class="dropdown-submenu">
                                                                        <a class="dropdown-item dropdown-toggle" href="#"><?= $subsubmenu->e_menu; ?></a>
                                                                        <ul class="dropdown-menu menu-sub">
                                                                            <?php if (get_submenu($subsubmenu->i_menu)->num_rows() > 0) {
                                                                                foreach (get_submenu($subsubmenu->i_menu)->result() as $sss) { ?>
                                                                                    <li><a class="dropdown-item" href="#" onclick="show('<?= $sss->e_folder; ?>/cform','#main'); return false;" data-placement="bottom" title="<?= $sss->e_menu; ?>"><?= $sss->e_menu; ?></a></li>
                                                                                <?php }
                                                                            } ?>
                                                                        </ul>
                                                                    </li>
                                                                <?php }
                                                                ?>
                                                            <?php }
                                                        } ?>
                                                    </ul>
                                                </li>
                                                <?php
                                            }
                                        }
                                    } ?>

                                </ul>
                            </li>
                        <?php }
                    }
                } ?>
                <!--

                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Dropdown link
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Link 1</a>
                    <a class="dropdown-item" href="#">Link 2</a>
                    <a class="dropdown-item" href="#">Link 3</a>
                </div>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" style="padding: 0 5px;" href="<?= base_url() . 'auth/logout' ?>"><i class="icon-logout fa-fw"></i>&nbsp;Log Out</a>
                    </li> -->

            </ul>
            <ul class="nav navbar-top-links navbar-right hidden-xs">
                <!-- <li><a class="nav-link" href="<?= base_url() . 'auth/logout' ?>"><i class="icon-logout fa-fw"></i>&nbsp;Log Out</a></li> -->
                <li><a class="nav-link" href="#" onclick="logout()"><i class="icon-logout fa-fw"></i>&nbsp;Log Out</a></li>
            </ul>

        </div>
    </nav>
    <!-- Left navbar-header -->
    <!-- <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <?= $menu; ?>
            </div>
        </div> -->
    <!-- Left navbar-header end -->


    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row bg-title blog-shadow-dreamy">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title"><?= $nama_company; ?></h4>
                </div>
                <!-- /.col-lg-12 -->
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li> <label>Departement &nbsp;&nbsp;&nbsp;<select class="custom-select form-control-sm" id="departement" onchange="change_departement(this);">
                                    <option style="display: none;">Pilih</option>
                                    <?php
                                    $i_departement = $this->session->userdata('i_departement');
                                    foreach ($departement as $row) {
                                        if ($departement_user) {
                                            foreach ($departement_user->result() as $riw) {
                                                if ($row->e_departement_name == $riw->e_departement_name) { ?>
                                                    <option <?php if ($i_departement == $row->i_departement) {
                                                        echo "selected";
                                                    } ?> value='<?= $row->i_departement; ?>'><?= $row->e_departement_name ?></option>
                                                <?php }
                                            }
                                        }
                                        ?>

                                    <?php } ?>
                                </select></li>
                        <li> <label>Level &nbsp;&nbsp;&nbsp;<select class="custom-select form-control-sm" id="level" onchange="change_level(this);">
                                    <option style="display: none;">Pilih</option>
                                    <?php
                                    $i_level = $this->session->userdata('i_level');
                                    foreach ($level as $row) {
                                        if ($level_user) {
                                            foreach ($level_user->result() as $riw) {
                                                if ($row->e_level_name == $riw->e_level_name) { ?>
                                                    <option <?php if ($i_level == $row->i_level) {
                                                        echo "selected";
                                                    } ?> value='<?= $row->i_level; ?>'><?= $row->e_level_name ?></option>
                                                <?php }
                                            }
                                        }
                                    } ?>
                                </select></li>
                    </ol>
                </div>
            </div>
            <div id="loading">
                <div class="cssload-speeding-wheel"></div>
            </div>
            <div id="main">
                <!-- <div class="row"> -->

                <!-- <div class="col-md-3 col-sm-6">
                                    <div class="white-box">
                                        <div class="r-icon-stats">
                                            <i class="fa fa-list-alt"></i>
                                            <div class="bodystate">
                                                <h1 class="text-center text-danger font-weight-bold font-size-lg"></h1>
                                                <span class="text-info"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                <div class="row">

                    <?php if ($notif) {
                        foreach ($notif->result() as $row) { ?>
                            <div class="col-sm-6 col-xl-3 mb-4" onclick="show('<?= $row->e_folder; ?>/cform/index/<?= $row->dfrom; ?>/<?= $row->dto; ?>','#main'); return false;">
                                <div class="card card-body bg-info text-white has-bg-image">
                                    <div class="media">
                                        <div class="media-body text-center">
                                            <h2 class="mb-1 text-white font-size-lg font-weight-bold"><?= $row->total; ?></h2>
                                            <span class="text-uppercase font-size-md font-weight-bold"><?= $row->e_menu; ?></span>
                                        </div>

                                        <div class="ml-3 align-self-center">
                                            <i class="fa fa-flag fa-3x opacity-75"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>


                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list mr-2"></i> <?= "Stok WIP"; ?></div>
                            <div class="panel-body table-responsive">
                                <table id="tabledatawip" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="3%;">No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Warna</th>
                                        <th>Stok Jadi</th>
                                        <th>Stok WIP</th>
                                        <th>Stok Jahit</th>
                                        <th>Stok Pengadaan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading"> <i class="fa fa-list mr-2"></i> <?= "Stok Material"; ?></div>
                            <div class="panel-body table-responsive">
                                <table id="tabledatamaterial" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="3%;">No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Stok Gudang</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/row -->
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
        <footer class="footer text-center blog-shadow-dreamy" style="left: 0px;"><i class="fa fa-globe"></i> Generasi Hebat&nbsp; <i class="fa fa-globe"></i> </footer>
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js?v=2"></script>
<script src="<?= base_url(); ?>assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js?v=2"></script>
<!--Wave Effects -->
<script src="<?= base_url(); ?>assets/js/waves.js?v=2"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/tether.min.js?v=2"></script>
<script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js?v=2"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js?v=2"></script>
<!--slimscroll JavaScript -->
<script src="<?= base_url(); ?>assets/js/jquery.slimscroll.js?v=2"></script>


<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/jquery.dataTables.min.js?v=2"></script>
<!-- start - This is for export functionality only -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/dataTables.rowGroup.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/buttons.flash.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/jszip.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/pdfmake.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/vfs_fonts.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/datatables/buttons.print.min.js"></script>
<script src="<?= base_url(); ?>assets/js/dataTables.fixedColumns.min.js"></script>
<script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url(); ?>assets/js/cbpFWTabs.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/tablesaw-master/dist/tablesaw.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/blockUI/jquery.blockUI.js"></script>
<!-- File Upload -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/dropify/dist/js/dropify.min.js?v=2"></script>

<!--numberformat JavaScript -->
<script src="<?= base_url(); ?>assets/js/jquery.number.min.js?v=2"></script>
<script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js?v=2"></script>
<script src="<?= base_url(); ?>assets/js/freeze-table.js?v=2"></script>
<script src="<?= base_url(); ?>assets/bootstrap/js/tooltip.js?v=2"></script>

<!-- TABLE BOOSTRAP FIXED COLUMN -->
<script src="<?= base_url() . 'assets/js/bootstrap-table.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/js/bootstrap-table-fixed-columns.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/js/tableExport.min.js'; ?>"></script>
<script src="<?= base_url() . 'assets/js/bootstrap-table-export.min.js'; ?>"></script>
<!-- END TABLE BOOSTRAP FIXED COLUMN -->

<!-- JS BARCODE -->
<script src="<?= base_url() . 'assets/js/jsbarcode.all.min.js'; ?>"></script>
<!-- END OF JS BARCODE -->

<input type="hidden" id="closing" value="<?php echo $cls ?>">
<!-- End -->
<script>
    // navbar fixed top //

    // navbar fixed top end //

    var base_url = '<?= base_url(); ?>';
    var today = new Date("<?= $today; ?>");
    var holiday = [<?= "'" . implode("','", $holiday) . "'"; ?>];
    $(document).ready(function() {
        tooltip();
        let right = [4, 5, 6, 7];
        let kanan = [4];
        datatablemain('#tabledatawip', base_url + 'main/data_wip', right);
        datatablemain('#tabledatamaterial', base_url + 'main/data_material', kanan);
    });
    document.onkeydown = function(e) {
        // if (event.keyCode == 123) {
        //     return false;
        // }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'i'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'C'.charCodeAt(0) || e.keyCode == 'c'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'j'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && (e.keyCode == 'U'.charCodeAt(0) || e.keyCode == 'u'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && (e.keyCode == 'S'.charCodeAt(0) || e.keyCode == 's'.charCodeAt(0))) {
            return false;
        }
    }

    function logout() {
        let departement = $(`#departement`).val();
        let level = $(`#level`).val();
        location.href = `${base_url}auth/logout/${departement}/${level}`;
    }
</script>
<script src="<?= base_url(); ?>assets/js/sweetalert.js?v=2"></script>
<script src="<?= base_url(); ?>assets/js/custom.min.js?v=5"></script>
<!--</body>
    </html>-->
</body>

</html>