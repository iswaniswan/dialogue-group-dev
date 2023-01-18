<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dialogue Group">
    <meta name="author" content="Management Information System">
    <!--<link rel="icon" href="optimum/images/logo.png" type="image/x-icon" />-->
    <title>Dialogue Group Apps Portal</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="<?= base_url(); ?>assets/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/global.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?= base_url(); ?>assets/css/colors/megna.css" id="theme" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <!--</head>-->

</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="login-register">
        <!-- <div class="login-box login-sidebar"> -->
        <div class="login-box shadow">
            <div class="white-box">
                <div align="center">
                    <img width="200" height="70" src="<?= base_url(); ?>assets/images/logo.png">
                    <br><br>
                    Welcome to<br> <strong style="color:green">Dialogue Group Apps Portal</strong>
                    <div align="center" class="mb-4">
                    </div>
                    <!-- <br><br> -->
                    <form class="form-horizontal form-material" id="login-form" action="<?= base_url(); ?>auth/login" method="post">

                        <div class="form-group">
                            <div class="col-xs-12">
                                <select name="apps" id="apps" class="form-control" required onchange="change_apps(this);" data-placeholder="Apps Type">
                                    <option value=""></option>
                                    <?php if ($apps->num_rows() > 0) {
                                        foreach ($apps->result() as $row) {
                                            echo "<option value='$row->i_apps'>$row->e_apps</option>";
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;" id="form-company">
                            <div class="col-xs-12">
                                <select name="company" id="company" class="form-control" data-placeholder="Select Company" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" autofocus type="text" name="username" required="" placeholder="Username" style="width:100%">
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" name="password" required="" placeholder="Password" style="width:100%">
                            </div>
                        </div>
                        <button id="main" class="btn btn-info style1 btn-lg btn-block text-uppercase waves-effect waves-light" type="submit" style="width:100%; color:white">
                            Login
                        </button>
                        <div align="center"><img id="install_progress" src="<?= base_url(); ?>assets/images/loading.gif" style="margin-left: 20px;  display: none" /></div>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    </form>
                </div>
            </div>
        </div>

    </section>


    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?= base_url(); ?>assets/bootstrap/dist/js/tether.min.js"></script>
    <script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js">
    </script>
    <script src="<?= base_url(); ?>assets/plugins/bower_components/blockUI/jquery.blockUI.js"></script>

    <!--Custom JavaScript -->
    <script src="<?= base_url(); ?>assets/js/custom.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/custom.js"></script>



    <!-- Menu Plugin JavaScript -->
    <script src="<?= base_url(); ?>assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <link href="<?= base_url(); ?>assets/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">

    <!-- auto hide message div-->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.hide_msg').delay(2000).slideUp();

            $('#company, #apps').select2({
                width: "100%",
            });
        });

        function get_csrf() {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });
        }
    </script>

    <!--slimscroll JavaScript -->
    <script src="<?= base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?= base_url(); ?>assets/js/waves.js"></script>
    <script>
        var base_url = '<?= base_url(); ?>';
    </script>
    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url(); ?>assets/js/custom.min.js"></script>
    <!--Style Switcher -->
    <script src="<?= base_url(); ?>assets/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
    <script>
        // $('form').submit(function (e) {
        //     $('#install_progress').show();
        //     $('#modal_1').show();
        //     $('.btn').val('Login...');
        //     $('form').submit();
        //     e.preventDefault();
        // });
    </script>
    <script>
        $('#company').on('change', function() {
            var company = $('#company').val();
            $.ajax({
                type: "POST",
                data: {
                    'company': company,

                },
                url: "<?= base_url(); ?>auth/set_company",
                dataType: "JSON",
            });
            setTimeout(function() { $('input[name="username"]').focus() }, 100);
        });
    </script>
    <!--</body></html>-->
</body>

</html>