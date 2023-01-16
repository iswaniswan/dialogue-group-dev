<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('upload');
        $this->load->database('rest_database_group', TRUE);
    }

    public function index_get()
    {
        echo 'HAI API';
    }

    public function trace_post()
    {
        $id = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));

        $query = array();
        $key = 0;

        $head = $this->db->query("
            with cte as (
                select array[UPPER(e.name) , a.id , b.i_product_base, b.e_product_basename, c.e_color_name, d.e_brand_name] as datas from tr_proses a
                inner join tr_product_base b on (a.id_product_base = b.id) 
                inner join tr_color c on (b.i_color = c.i_color and b.id_company = c.id_company)
                inner join tr_brand d on (b.i_brand = d.i_brand and b.id_company = d.id_company)
                inner join public.company e on (b.id_company = e.id)
                where a.id = '$id'
            )
            SELECT un1.val::text as labels, un2.val::text as datas
            FROM unnest(ARRAY['Perusahaan', 'ID Barcode', 'Kode Produk', 'Nama Produk', 'Warna', 'Brand']) WITH ORDINALITY un1 (val, ord)
            INNER JOIN unnest((select datas from cte)) WITH ORDINALITY un2 (val, ord) ON un2.ord = un1.ord;
        ");
        if ($head->num_rows() > 0 ) {
            foreach ($head->result() as $row) {
               $query['head'][$key]['labels'] = $row->labels;
               $query['head'][$key]['datas'] = $row->datas;
               $key++;
            }
        }

        $key=0;
        $pengadaanA = $this->db->query("
            with cte as (
                select array[coalesce(b.d_update, b.d_entry)::text, b.i_keluar_pengadaan , to_char(b.d_keluar_pengadaan,'DD FMMonth YYYY') , 
                c.e_bagian_name, b.e_approve, to_char(b.d_approve,'DD FMMonth YYYY')] as datas from tr_proses a 
                inner join tm_keluar_pengadaan b on (a.id_keluar_pengadaan  = b.id)
                inner join tr_bagian c on (b.i_tujuan = c.i_bagian and b.id_company = c.id_company)
                where a.id = '$id'
            )
            SELECT un1.val::text as labels, un2.val::text as datas
            FROM unnest(ARRAY['Date Created', 'No. Dokumen', 'Tgl. Dokumen', 'Tujuan', 'Approve', 'Tgl. Approve']) WITH ORDINALITY un1 (val, ord)
            INNER JOIN unnest((select datas from cte)) WITH ORDINALITY un2 (val, ord) ON un2.ord = un1.ord;
        ");
        if ($pengadaanA->num_rows() > 0 ) {
            foreach ($pengadaanA->result() as $row) {
               $query['pgdA'][$key]['labels'] = $row->labels;
               $query['pgdA'][$key]['datas'] = $row->datas;
               $key++;
            }
        }







        if (sizeof($query) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Data Ditemukan',
                'data' => $query,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }



    public function imove_old_post()
    {
        $id = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));
        $i_nik = str_replace("'", "", htmlspecialchars(strtoupper($this->post('nik')), ENT_QUOTES));
        // $id = '13'; //BCL
        // $i_nik = '180709001';
        //$id = '227'; //Support

        $event = $this->load->database('event', TRUE);
        $query = $event->query("
            select id, e_participant_name, idcabang , f_support, e_participant_company from tr_participant where id = '$id'
        ");

        if ($query->num_rows() > 0) {

            $idevent = $event->query(" select id from tm_event where current_date between d_event_start and d_event_finish and f_event_cancel = false limit 1
            ")->row();

            if ($idevent) {
                $idcabang = $query->row()->idcabang;
                $f_support = $query->row()->f_support;
                $id_participant = $query->row()->id;
                $e_participant_company = $query->row()->e_participant_company;
                $idevent = $idevent->id;

                $cte = "
                    with karyawan as (
                         select 
                            case when a.support = true and cb.idcabang <> 40 then NULL
                            else cb.idcabang 
                            end as idcabang
                         ,cb.nama as pt, a.support, a.iduser, 
                         a.nama, a.i_nik from karyawan a
                         inner join public.user b on (a.iduser = b.iduser)
                         inner join public.level c on (b.idlevel = c.idlevel)
                         inner join cabang cb on (c.idcabang = cb.idcabang)
                         where resign = 'false' and cb.idcabang <> 1
                         order by 1,2,5
                    )
                ";

                $where = '';
                if ($idcabang != null) {
                    $where = " And idcabang = '$idcabang' ";
                } else {
                    $where = " And idcabang is null ";
                    $idcabang = 'NULL';
                }

                $datanik = $event->query(" 
                    $cte
                    select initcap(nama) as nama, support from karyawan where i_nik = '$i_nik' $where

                ")->row();

                if ($datanik) {

                    $nama = $datanik->nama;
                    $support = $datanik->support;

                    $cekbarcodenik = $event->query(" 
                        select * from tm_event_checkin where id_event = '$idevent' and id_participant = '$id_participant'
                    ")->row();

                    // $event->query("
                    //         INSERT INTO public.tm_event_checkin_log (id_event, id_participant, idcabang, f_support, i_nik, d_checkin)
                    //         VALUES($idevent, $id_participant, $idcabang, '$support', '$i_nik', now() at time zone 'Asia/Jakarta');
                    //     ");

                    if ($cekbarcodenik) {
                        $old_i_nik = $cekbarcodenik->i_nik;

                        if ($i_nik == $old_i_nik) {
                            $this->response([
                                'status' => false,
                                'message' => 'Anda Sudah Melakukan Scan, Silahkan Masuk ',
                            ], REST_Controller::HTTP_OK);
                        } else {

                            $cekbarcodenik = $event->query(" 
                                select * from tm_event_checkin_log where id_event = '$idevent' and i_nik = '$i_nik' $where limit 1
                            ")->row();

                            if ($cekbarcodenik) {
                                $this->response([
                                    'status' => false,
                                    'message' => 'Anda Sudah Melakukan Scan, Silahkan Masuk aja',
                                ], REST_Controller::HTTP_OK);
                            } else {
                                // echo $idcabang;
                                $where = '';
                                if ($idcabang == null || $idcabang == 'NULL' || $idcabang == NULL) {
                                    $where = " And a.idcabang is null ";
                                } else {
                                    $where = " And a.idcabang = '$idcabang' ";
                                }

                                $datakuota = $event->query("
                                    WITH cte as (
                                        select distinct idcabang, e_participant_company, n_kuota from tr_participant order by 1,2
                                    )
                                    select a.*, coalesce(b.total,0) + 1 as total from cte a
                                    left join (
                                        select idcabang, count(i_nik) as total from (
                                            select distinct idcabang, i_nik from tm_event_checkin_log where id_event = 1 
                                        ) as x
                                        group by 1
                                    ) as b on (a.idcabang = b.idcabang OR (a.idcabang IS NULL AND b.idcabang IS NULL))
                                    where e_participant_company = e_participant_company $where
                                ")->row();

                                if ($datakuota->total > $datakuota->n_kuota) {
                                    $this->response([
                                        'status' => false,
                                        'message' => 'Kuota '.$datakuota->e_participant_company.' Sudah Penuh ',
                                    ], REST_Controller::HTTP_OK);
                                } else {
                                    $event->query("
                                        INSERT INTO public.tm_event_checkin_log (id_event, id_participant, idcabang, f_support, i_nik, d_checkin)
                                        VALUES($idevent, $id_participant, $idcabang, '$support', '$i_nik', now() at time zone 'Asia/Jakarta');
                                    ");
                                    $this->response([
                                        'status' => true,
                                        //'message' => 'Anda Peserta Ke '. $datakuota->total . ', Silahkan Masuk',
                                        'message' => 'Akses Diterima, '.$nama ,
                                    ], REST_Controller::HTTP_OK);
                                }


                            }

                            // $sudahada = $event->query(" 
                            //     $cte
                            //     select nama from karyawan where i_nik = '$old_i_nik'
                            // ")->row();

                            // if ($sudahada) {
                            //     $sudahada = $sudahada->nama;
                            // } else {
                            //     $sudahada = '';
                            // }

                            // $this->response([
                            //     'status' => false,
                            //     'message' => 'Tiket Anda Sudah Tidak Berlaku , pernah di gunakan '.$sudahada,
                            // ], REST_Controller::HTTP_OK);
                        }

                    } else {
                        $event->query("
                            INSERT INTO public.tm_event_checkin (id_event, id_participant, idcabang, f_support, i_nik, d_checkin)
                            VALUES($idevent, $id_participant, $idcabang, '$support', '$i_nik', now() at time zone 'Asia/Jakarta') on conflict(id_event, id_participant) do nothing;
                        ");
                        $event->query("
                            INSERT INTO public.tm_event_checkin_log (id_event, id_participant, idcabang, f_support, i_nik, d_checkin)
                            VALUES($idevent, $id_participant, $idcabang, '$support', '$i_nik', now() at time zone 'Asia/Jakarta');
                        ");
                        $this->response([
                            'status' => true,
                            'message' => 'Akses Diterima, '.$nama ,
                        ], REST_Controller::HTTP_OK);
                    }

                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'NIK Tidak Ditemukan Di '.$e_participant_company ,
                    ], REST_Controller::HTTP_OK);
                }

                // $event->query("
                //     INSERT INTO public.tm_event_checkin (id_event, id_participant, d_checkin) VALUES($idevent, $id_participant, now() at time zone 'Asia/Jakarta') on conflict(id_event, id_participant) do nothing;
                // ");

                // $event->query("
                //     INSERT INTO public.tm_event_checkin_log (id_event, id_participant, d_checkin) VALUES($idevent, $id_participant, now() at time zone 'Asia/Jakarta');
                // ");
                // // echo "". $id_participant . $idevent;
                // $this->response([
                //     'status' => true,
                //     'message' => $query->row()->e_participant_name,
                // ], REST_Controller::HTTP_OK);

                var_dump($datanik);

            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Event Tidak Ditemukan',
                ], REST_Controller::HTTP_OK);
            }
            
        } else {
            $this->response([
                'status' => false,
                'message' => 'Barcode Tidak Ditemukan',
            ], REST_Controller::HTTP_OK);
        }
    }


    public function imove_post()
    {
        $id = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));

        //$id = '210802001165';
        $event = $this->load->database('event', TRUE);
        $query = $event->query("
            select id, e_company, e_name, e_ruangan from tr_participant_new where id = '$id'
        ");

        if ($query->num_rows() > 0) {

            $idevent = $event->query(" select id from tm_event where current_date between d_event_start and d_event_finish and f_event_cancel = false limit 1
            ")->row();

            if ($idevent) {
                $id_participant = $query->row()->id;
                $e_company = $query->row()->e_company;
                $e_name = $query->row()->e_name;
                $e_ruangan = $query->row()->e_ruangan;
                $idevent = $idevent->id;

                $event->query("
                    INSERT INTO public.tm_event_checkin_new (id_event, id_participant, d_checkin) VALUES($idevent, $id_participant, now()) on conflict(id_event, id_participant) do nothing;
                ");

                $this->response([
                    'status' => true,
                    'message' => 'Akses Diterima, '.$e_name .'. Silahkan Masuk Ke Ruangan '. $e_ruangan ,
                ], REST_Controller::HTTP_OK);

            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Event Tidak Ditemukan',
                ], REST_Controller::HTTP_OK);
            }
            
        } else {
            $this->response([
                'status' => false,
                'message' => 'QR Code Tidak Ditemukan, Silahkan tanyakan kepada panitia',
            ], REST_Controller::HTTP_OK);
        }
    }

}
