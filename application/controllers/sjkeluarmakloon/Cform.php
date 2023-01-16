<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050207';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->doc_qe = $data[0]['doc_qe'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

    public function index()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'type'          => $this->mmaster->type($this->i_menu),
            'bagian'        => $this->mmaster->bagian(),
            'bagian_receive' => $this->mmaster->bagian_receive(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "SJ-" . date('ym') . "-1234",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  DATA PARTNER SESUAI TYPE MAKLOON  ----------*/

    public function partner()
    {
        $filter = [];
        if ($this->input->get('idtype') != '') {
            $data = $this->mmaster->partner($this->input->get('idtype'), str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => $key->e_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data."
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tipe Makloon Tidak Boleh Kosong!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL PARTNER  ----------*/

    public function detailsupplier()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->detailpartner($this->input->post('idsupplier', TRUE))->row());
    }

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function referensi()
    {
        $filter = [];
        if ($this->input->get('idpartner') != '') {
            $data = $this->mmaster->datareferensi(str_replace("'", "", $this->input->get('q')), $this->input->get('idpartner'));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => $key->i_document
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data."
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Partner Tidak Boleh Kosong!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL ITEM REFERENSI  ----------*/

    public function detailreferensi()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'data'   => $this->mmaster->ref($this->input->post('id', TRUE))->row(),
            'detail' => $this->mmaster->detailreferensi($this->input->post('id', TRUE), $this->input->post('tgl', TRUE))->result_array(),
        );
        echo json_encode($query);
    }


    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function product_wip()
    {
        $filter = [];
        $data = $this->mmaster->product_wip(str_replace("'", "", $this->input->get('q')), $this->input->get('dfrom'), $this->input->get('dto'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => '[' . $key->i_product_wip . '] - ' . $key->e_product_wipname . ' ' . $key->e_color_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }


    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function product_material()
    {
        $filter = [];
        $data = $this->mmaster->product_material(str_replace("'", "", $this->input->get('q')), $this->input->get('dfrom'), $this->input->get('dto'), $this->input->get('id_wip'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => '[' . $key->i_material . '] - ' . $key->e_material_name . ' - ' . $key->e_satuan_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }


    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function product()
    {
        $filter = [];
        $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')), $this->input->get('dfrom'), $this->input->get('dto'), $this->input->get('idtype'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => '[' . $key->i_material . '] - ' . $key->e_material_name . ' - ' . $key->e_satuan_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL ITEM REFERENSI  ----------*/

    public function detail_product()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detail_product($this->input->post('id_material', TRUE), $this->input->post('dfrom', TRUE), $this->input->post('dto', TRUE))->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = formatYmd($ddocument);
        }
        $destimate    = $this->input->post('destimate', TRUE);
        if ($destimate != '') {
            $destimate = formatYmd($destimate);
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ibagianreceive = $this->input->post('ibagianreceive', TRUE);
        $idtype         = $this->input->post('idtype', TRUE);
        $idpartner      = $this->input->post('idpartner', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $id = $this->mmaster->runningid();
        if ($ibagian != '' && $idtype != '' && $ibagianreceive != ''  && $idpartner != '' /* && $jml > 0 */) {
            $this->db->trans_begin();
            $this->mmaster->simpan($id, $idocument, $ddocument, $destimate, $ibagian, $ibagianreceive, $idtype, $idpartner, $eremark);
            /* for($i=0;$i<$jml;$i++){
                $idmaterial      = $this->input->post('idmaterial'.$i, TRUE);
                $nqty            = $this->input->post('nqty'.$i, TRUE);
                $idmateriallist  = $this->input->post('idmateriallist'.$i, TRUE);
                $nqtylist        = $this->input->post('nqtylist'.$i, TRUE);
                $eremark         = $this->input->post('eremark'.$i, TRUE);
                $vunitprice      = str_replace(",", "", $this->input->post('vunitprice'.$i, TRUE));
                $vunitpricelist  = str_replace(",", "", $this->input->post('vunitpricelist'.$i, TRUE));
                if (($idmaterial!='' || $idmaterial!=null) && $nqty > 0 && ($idmateriallist!='' || $idmateriallist!=null) && $nqtylist > 0) {
                    $this->mmaster->simpandetail($id,$idreff,$idmaterial,$nqty,$idmateriallist,$nqtylist,$eremark,$vunitprice,$vunitpricelist);
                }
            }  */
            // for ($x = 1; $x <= $jml; $x++) {
            //     $idmaterial = $this->input->post('idmaterial' . $x, TRUE);
            //     $id_wip = $this->input->post('id_wip' . $x, TRUE);
            //     $nquantity  = str_replace(",", "", $this->input->post('nquantity' . $x, TRUE));
            //     if ($idmaterial != "" || $idmaterial != NULL) {
            //         $i = 0;
            //         foreach ($this->input->post("idmaterialhead[]", TRUE) as $idmaterialhead) {
            //             if ($idmaterial == $idmaterialhead) {
            //                 $idmateriallist = $this->input->post("idmateriallist[]", TRUE)[$i];
            //                 $nquantityhead  = str_replace(",", "", $this->input->post("nquantityhead[]", TRUE)[$i]);
            //                 $nquantitylist  = str_replace(",", "", $this->input->post("nquantitylist[]", TRUE)[$i]);
            //                 $eremark        = $this->input->post("eremarklist[]", TRUE)[$i];
            //                 if (($idmaterialhead != null || $idmaterialhead != '') && $nquantityhead > 0) {
            //                     $this->mmaster->simpandetail($id, $idmaterialhead, $nquantity, $idmateriallist, $nquantitylist, $eremark, $id_wip);
            //                 }
            //             }
            //             $i++;
            //         }
            //     }
            // }
            // $idmateriallist = $this->input->post("idmateriallist[]", TRUE);
            // $idmateriallist2 = $this->input->post("idmateriallist2[]", TRUE);
            // var_dump($idmateriallist, $idmateriallist2);
            // die;

            // $idkeluarhead = $this->input->post("idkeluarhead[]", TRUE);
            // $i = 0;
            // foreach($idkeluarhead as $id_keluar) {
            //     $idmateriallist = (!empty($this->input->post("idmateriallist[]", TRUE)[$i])) ? $this->input->post("idmateriallist[]", TRUE)[$i] : null ;
            //     $id_wip = (!empty($this->input->post("idwip[]", TRUE)[$i])) ? $this->input->post("idwip[]", TRUE)[$i] : null ;
            //     $nquantitylist  = str_replace(",", "", $this->input->post("nquantitylist[]", TRUE)[$i]);
            //     $eremark        = $this->input->post("eremarklist[]", TRUE)[$i];
            //     // if (($idmateriallist != null || $idmateriallist != '') && $nquantitylist > 0) {
            //         // var_dump('vardump1');
            //         // var_dump([$id_wip, $id_keluar, $idmateriallist, $nquantitylist, $eremark]);
            //         //     // $this->mmaster->simpandetailkeluar($id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark);
            //     // }
            //     // if (($idmateriallist2 != null || $idmateriallist2 != '') && $nquantitylist2 > 0) {
                    
            //         //     var_dump('masuk ke masuk makloon');
            //         //     // $this->mmaster->simpandetailmasuk($id, $id_keluar, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa);
            //     // }
            //     // var_dump($id);
            //     var_dump($idmateriallist, $nquantitylist);
            //     $i++;
            // }
            // $a = 0;
            // foreach($idkeluarhead as $id_keluar) {
            //     $idmateriallist2 = (!empty($this->input->post("idmateriallist2[]", TRUE)[$a])) ? $this->input->post("idmateriallist2[]", TRUE)[$a] : null;
            //     $nquantitylist2  = str_replace(",", "", $this->input->post("nquantitylist2[]", TRUE)[$a]);
            //     $nquantitylist2sisa  = str_replace(",", "", $this->input->post("nquantitylist2sisa[]", TRUE)[$a]);
            //     // var_dump('vardump2');
            //     // var_dump([$id_keluar, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa]);
            //     var_dump($idmateriallist2, $nquantitylist2);
            //     $a++;
            // }
            $jmlitem = $this->input->post('jmlitem', true);
            for ($i = 1; $i <= $jmlitem; $i++) {
                $id_keluar = $this->input->post("idkeluarhead$i", TRUE);
                $idmateriallist = (!empty($this->input->post("idmateriallist$i", TRUE))) ? $this->input->post("idmateriallist$i", TRUE) : null ;
                $id_wip = (!empty($this->input->post("idwip$i", TRUE))) ? $this->input->post("idwip$i", TRUE) : null ;
                $nquantitylist  = ($this->input->post("nquantitylist$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist$i", TRUE)) : 0;
                $eremark        = $this->input->post("eremarklist$i", TRUE);
                $idmateriallist2 = (!empty($this->input->post("idmateriallist2$i", TRUE))) ? $this->input->post("idmateriallist2$i", TRUE) : null;
                $nquantitylist2  = ($this->input->post("nquantitylist2$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist2$i", TRUE)) : 0;
                $nquantitylist2sisa  = ($this->input->post("nquantitylist2sisa$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist2sisa$i", TRUE)) : 0;
                // if (($idmateriallist != null || $idmateriallist != '') && $nquantitylist > 0) {
                $this->mmaster->simpandetailkeluar($id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark);
                // }
                // if (($idmateriallist2 != null || $idmateriallist2 != '') && $nquantitylist2 > 0) {
                $this->mmaster->simpandetailmasuk($id, $id_keluar, $id_wip, $idmateriallist, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa);
                // }
            }


            // var_dump($jml);

            // for ($x = 1; $x <= $jml; $x++) {
            //     $id_wip = $this->input->post('id_wip' . $x, TRUE);
            //     $id_keluar = $this->input->post('id_keluar' . $x, TRUE);
            //     if ($id_wip != "" || $id_wip != NULL) {
            //         $i = 0;
            //         foreach ($this->input->post("idkeluarhead[]", TRUE) as $idkeluarhead) {
            //             if ($id_keluar == $idkeluarhead) {
            //                 $idmateriallist = (isset($this->input->post("idmateriallist[]", TRUE)[$i])) ? $this->input->post("idmateriallist[]", TRUE)[$i] : null ;
            //                 $idmateriallist2 = (isset($this->input->post("idmateriallist2[]", TRUE)[$i])) ? $this->input->post("idmateriallist2[]", TRUE)[$i] : null;
            //                 $nquantitylist  = str_replace(",", "", $this->input->post("nquantitylist[]", TRUE)[$i]);
            //                 $nquantitylist2  = str_replace(",", "", $this->input->post("nquantitylist2[]", TRUE)[$i]);
            //                 $nquantitylist2sisa  = str_replace(",", "", $this->input->post("nquantitylist2sisa[]", TRUE)[$i]);
            //                 $eremark        = $this->input->post("eremarklist[]", TRUE)[$i];
            //                 if (($idmateriallist != null || $idmateriallist != '') && $nquantitylist > 0) {
            //                     var_dump('masuk ke keluar makloon');
            //                     var_dump([$id_wip, $id_keluar, $idmateriallist, $nquantitylist, $eremark]);
            //                     // $this->mmaster->simpandetailkeluar($id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark);
            //                 }
            //                 if (($idmateriallist2 != null || $idmateriallist2 != '') && $nquantitylist2 > 0) {
            //                     var_dump('masuk ke masuk makloon');
            //                     var_dump([$id_keluar, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa]);
            //                     // $this->mmaster->simpandetailmasuk($id, $id_keluar, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa);
            //                 }
            //             }
            //             $i++;
            //         }
            //     }
            // }
            // die;

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $idocument);
            }
        } else {
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "SJ-" . date('ym') . "-1234",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'type'       => $this->mmaster->type($this->i_menu),
            'bagian'     => $this->mmaster->bagian(),
            'bagian_receive' => $this->mmaster->bagian_receive(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetailprod' => $this->mmaster->datadetailprod($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->dataeditdetailkeluarmasuk($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $destimate    = $this->input->post('destimate', TRUE);
        if ($destimate != '') {
            $destimate = date('Y-m-d', strtotime($destimate));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ibagianreceive = $this->input->post('ibagianreceive', TRUE);
        $idtype       = $this->input->post('idtype', TRUE);
        $idpartner    = $this->input->post('idpartner', TRUE);
        $itypepajak   = $this->input->post('itypepajak', TRUE);
        $ndiskon      = $this->input->post('ndiskon', TRUE);
        $idreff       = $this->input->post('idreff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id != '' && $ibagian != '' && $idtype != '' && $idpartner != '' && $jml > 0) {
            $this->db->trans_begin();
            /* $this->mmaster->update($id, $idocument, $ddocument, $destimate, $ibagian, $idtype, $idpartner, $idreff, $eremark, $itypepajak, $ndiskon);
            for ($i = 0; $i < $jml; $i++) {
                $idmaterial      = $this->input->post('idmaterial' . $i, TRUE);
                $nqty            = $this->input->post('nqty' . $i, TRUE);
                $idmateriallist  = $this->input->post('idmateriallist' . $i, TRUE);
                $nqtylist        = $this->input->post('nqtylist' . $i, TRUE);
                $eremark         = $this->input->post('eremark' . $i, TRUE);
                $vunitprice      = str_replace(",", "", $this->input->post('vunitprice' . $i, TRUE));
                $vunitpricelist  = str_replace(",", "", $this->input->post('vunitpricelist' . $i, TRUE));
                if (($idmaterial != '' || $idmaterial != null) && $nqty > 0 && ($idmateriallist != '' || $idmateriallist != null) && $nqtylist > 0) {
                    $this->mmaster->simpandetail($id, $idreff, $idmaterial, $nqty, $idmateriallist, $nqtylist, $eremark, $vunitprice, $vunitpricelist);
                }
            } */
            $this->mmaster->update($id, $idocument, $ddocument, $destimate, $ibagian, $ibagianreceive, $idtype, $idpartner, $eremark);
            $this->mmaster->deletekeluar($id);
            $this->mmaster->deletemasuk($id);

            $jmlitem = $this->input->post('jmlitem', true);
            for ($i = 1; $i <= $jmlitem; $i++) {
                $id_keluar = $this->input->post("idkeluarhead$i", TRUE);
                $idmateriallist = (!empty($this->input->post("idmateriallist$i", TRUE))) ? $this->input->post("idmateriallist$i", TRUE) : null ;
                $id_wip = (!empty($this->input->post("idwip$i", TRUE))) ? $this->input->post("idwip$i", TRUE) : null ;
                $nquantitylist  = ($this->input->post("nquantitylist$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist$i", TRUE)) : 0;
                $eremark        = $this->input->post("eremarklist$i", TRUE);
                $idmateriallist2 = (!empty($this->input->post("idmateriallist2$i", TRUE))) ? $this->input->post("idmateriallist2$i", TRUE) : null;
                $nquantitylist2  = ($this->input->post("nquantitylist2$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist2$i", TRUE)) : 0;
                $nquantitylist2sisa  = ($this->input->post("nquantitylist2sisa$i", TRUE) != '') ? str_replace(",", "", $this->input->post("nquantitylist2sisa$i", TRUE)) : 0;
                // if (($idmateriallist != null || $idmateriallist != '') && $nquantitylist > 0) {
                $this->mmaster->simpandetailkeluar($id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark);
                // var_dump([$id, $id_keluar, $id_wip, $idmateriallist, $nquantitylist, $eremark]);
                // }
                // if (($idmateriallist2 != null || $idmateriallist2 != '') && $nquantitylist2 > 0) {
                // var_dump([$id, $id_keluar, $idmateriallist, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa]);
                $this->mmaster->simpandetailmasuk($id, $id_keluar, $id_wip, $idmateriallist, $idmateriallist2, $nquantitylist2, $nquantitylist2sisa);
                // }
            }
            // die;

            // for ($x = 1; $x <= $jml; $x++) {
            //     $idmaterial = $this->input->post('idmaterial' . $x, TRUE);
            //     $id_wip = $this->input->post('id_wip' . $x, TRUE);
            //     $nquantity  = str_replace(",", "", $this->input->post('nquantity' . $x, TRUE));
            //     if ($idmaterial != "" || $idmaterial != NULL) {
            //         $i = 0;
            //         foreach ($this->input->post("idmaterialhead[]", TRUE) as $idmaterialhead) {
            //             if ($idmaterial == $idmaterialhead) {
            //                 $idmateriallist = $this->input->post("idmateriallist[]", TRUE)[$i];
            //                 $nquantityhead  = str_replace(",", "", $this->input->post("nquantityhead[]", TRUE)[$i]);
            //                 $nquantitylist  = str_replace(",", "", $this->input->post("nquantitylist[]", TRUE)[$i]);
            //                 $eremark        = $this->input->post("eremarklist[]", TRUE)[$i];
            //                 // var_dump($idmaterialhead, $nquantityhead);
            //                 if (($idmaterialhead != null || $idmaterialhead != '') && $nquantityhead > 0) {
            //                     $this->mmaster->simpandetail($id, $idmaterialhead, $nquantity, $idmateriallist, $nquantitylist, $eremark, $id_wip);
            //                 }
            //             }
            //             $i++;
            //         }
            //     }
            // }
            // die;

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $idocument);
            }
        } else {
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }

        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU FORM VIEW  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetailprod' => $this->mmaster->datadetailprod($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->dataeditdetailkeluarmasuk($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetailkeluarmasuk($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        /* if ($istatus == '6') {
            $this->mmaster->updatesisa($id);
            $this->mmaster->simpanjurnal($id, $this->global['title']);
        } */
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }
}

/* End of file Cform.php */