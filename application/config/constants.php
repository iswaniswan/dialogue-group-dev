<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code




define('NmPerusahaan','PT. DIALOGUE GARMINDO UTAMA');
define('AlmtPerusahaan','Jl. KARINDING NO.11');
define('KotaPerusahaan','BANDUNG');
define('TlpPerusahaan','(022)6002258');
define('FaxPerusahaan','(022)6030164');
define('NPWPPerusahaan','01.548.571.7.441.000');
define('TlpPajak','(022) 7307430');
#define('TtdNota','Yona Apriyanti');
define('TtdNota','Emmy Ernita');
#define('TtdNota','Mega Lestari');
#define('TtdNota','Noveni Karya Surya');
#define('TtdOP','Noveni K.S.');
define('TtdOP','');
// define('TahuKN','Susy Ferawaty');
define('TahuKN','Idvan Fitriansyah');
//define('BikinKN','Sahrir Syahrizal');
// define('BikinKN','Roby HM');
// define('BikinKN','Idvan Fitriansyah');
define('BikinKN','Rika Sugiarti');
define('TahuKN1','Kartika L.');
define('BikinKN1','Emmy Ernita');
define('TtdPajak','Lisnawati');
define('JabPajak','Admin Pajak');
define('PajakBBK','Tukiyo');
define('BCABandung','BCA - BANDUNG     Rek. No. 139.300.1236');
define('BCACimahi','BCA - Cimahi      Rek. No. 139.300.1236');
define('BRIBandung','BRI - BANDUNG     Rek. No. 028601000466303');
define('PermataBandung','PERMATA - BANDUNG Rek. No. 3801101800');
define('Kas','110-1');
define('Bank','110-2');
define('PiutKaryawan','110-4200');

define('110-20011','BCACMH');
define('110-20012','BCAJKT');
define('110-20013','BCADAGO');
define('110-20021','PERMATA');
define('110-2401','BRIBDG');

define('BCACMH','110-20011');
define('BCAJKT','110-20012');
define('BCADAGO','110-20013');
define('PERMATA','110-20021');
define('BRIBDG','110-2401');
define('BankBCA','110-20010');
define('BankBRI','110-20020');
define('BankBCA2019','110-2001');
define('BankBRI2019','110-2003');

define('KasBesarx','110-1100');
define('KasKecilx','110-12');
define('KasBesar','110-11000');
define('KasKecil','110-120');
define('KHP','111.3');####
define('PiutangDagang','110-41000');
define('PiutangDagangSementara','110-41000');
define('HutangDagang','210-10000');
define('HutangDagangSementara','210-10000');
define('HutangPPN','210-40071');
define('PotonganPenjualan','420-00000');
define('ReturPenjualan','420-10000');
define('HasilPenjualanKotor','410-00001');
define('Pembelian','110-50010');
define('PotonganPembelian','512.200');
define('Penyesuaian','900-00000');
define('BankPinjaman','110-20012');
define('ByPromosi','620-02100');
define('ByJasaPromosi','620-02101');
define('ByAlatPromosi','620-02102');
define('ByExpedisi','620-02200');
define('ByAdmBank','610-02801');#
define('ByAdmPenjualan','620-02701');#
define('ByPembulatan','610-2902');#
define('ByLainlain','800-50000');#
define('ByDiskonPromo','430-1200');#
define('RetPenjualanLokal','420-10100');#
define('HutangLain','210-50100');
define('ByPenjualan','620-');#
define('ByAdmUmum','610-');#
define('PendLain','700-');#
define('ByBungaBankdanlainnya','800-');#
define('HPP','110-8400');#