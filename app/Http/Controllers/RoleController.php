<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Paket_soal;
use App\Models\Peserta_paket;
use Carbon\Carbon;
class RoleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
      }
      public function siswa() {
        $id_users= Auth::user()->id;
        return view('siswa.siswa');
      }
      public function admin() {
        $ke=DB::table('kelas')->orderBy('kelas');
        $aktif = DB::table('absen')->join('kelas as k','k.id','absen.kelas_id')->select('absen.*','k.nama')->whereDate('masuk',Carbon::today());
        $peng=DB::table('users')->where('role','pengajar')->orderBy('name');
        return view('admin.admin',['kelas'=>$ke,'aktif'=>$aktif,'peng'=>$peng]);
      }
      public function pengajar(Request $request) {
        $ke=DB::table('kelas')->orderBy('kelas');
        $id=Auth::user()->id;
        //$ipAddress = self::get_client_ip();
        //$ip = '103.239.147.187'; //For static IP address get
        //$ipAddress=$request->ip();
        $ipAddress =  trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com")); //Dynamic IP address get
        $data = \Location::get($ipAddress);    
        $aktif = DB::table('absen')->join('kelas as k','k.id','absen.kelas_id')->select('absen.*','k.nama')->whereDate('masuk',Carbon::today())->where('users_id',$id);
        //return view('details',compact('data'));
        return view('pengajar.pengajar',['kelas'=>$ke,'data'=>$data,'aktif'=>$aktif]);
      }
      public function index()
      {
          $role = Auth::user()->role;
          switch ($role) {
            case 'admin':
              return redirect('/admin');
              break;
            case 'user':
              return redirect('/siswa');
              break; 
              case 'pengajar':
                return redirect('/pengajar');
                break; 
            default:
              return redirect('/'); 
            break;
          }
    
      }
      public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
      }
      function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'IP tidak dikenali';
        return $ipaddress;
    }
    
}