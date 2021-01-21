<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AjaxPController extends Controller
{
    function absen(Request $request){
        $id=$request->id;
        $respon=array(
            "kode"=>"",
            "success"=>false,
            "pesan"=>"",
            "judul"=>"",
            "class"=>""
        );
        //$status=$request->status;
        $status=1;
        if(!empty($request->kelas_lain)){
            $kelas_lain = $request->kelas_lain;
        }else{
            $kelas_lain = "";
        }
        if(!empty($request->mapel_lain)){
            $mapel_lain = $request->mapel_lain;
        }else{
            $mapel_lain = "";
        }
        if($status>0){
            $ins=DB::table('absen')->insert(['users_id'=>$id,'kelas_id'=>$request->kelas,'mapel'=>$request->mapel,'materi'=>$request->materi,'metode'=>$request->metode,'kelas_lain'=>$kelas_lain,'mapel_lain'=>$mapel_lain]);
            if($ins){
                $respon['pesan']="Absen Berhasil, akan dialihkan dalam beberapa saat";
                $respon['judul']="Sukses";
                $respon['class']="success";
                $respon['success']=true;
            }else{
                $respon['pesan']="Input Error, Silahkan coba beberapa saat lagi";
                $respon['class']="danger";
                $respon['judul']="Gagal";
            }
        }else{
            $respon['pesan']="Input Error, Diluar radius jangkauan";
            $respon['class']="danger";
            $respon['judul']="Gagal";
        }
       return response()->json($respon);
    }
    public function absen_keluar(Request $request){
        $id=Auth::user()->id;
        $idMasuk=$request->id_masuk;
        //$waktu = Carbon::now()->timestamp;
        $waktu =date('Y-m-d H:i:s');
        $respon=array(
            "kode"=>"",
            "success"=>false,
            "pesan"=>"",
            "judul"=>"",
            "class"=>""
        );
        $ins=DB::table('absen')->where('id',$idMasuk)->update(['keluar'=>$waktu]);
        if($ins){
            $respon['pesan']="Absen Berhasil, akan dialihkan dalam beberapa saat";
            $respon['judul']="Sukses";
            $respon['class']="success";
            $respon['success']=true;
        }else{
            $respon['pesan']="Input Error, Silahkan coba beberapa saat lagi";
            $respon['class']="danger";
            $respon['judul']="Gagal";
        }
       return response()->json($respon);
    }
    function materi(Request $request){
        $id=Auth::user()->id;
        $mapel = $request->materi;
        $kelas = $request->kelas;
        $kelass=DB::table('kelas')->where('id',$kelas)->first();
        $mat = DB::table('mapel')->where('kelas',$kelass->induk_kelas)->where('mapel',$mapel);
        $materi=$mat->get();
       return response()->json($materi);
    }
    function absen_konfirm(Request $request){
        $id=$request->id_pengajar;
        $idMasuk=$request->id_masuk;
        //$waktu = Carbon::now()->timestamp;
        $waktu =date('Y-m-d H:i:s');
        $respon=array(
            "kode"=>"",
            "success"=>false,
            "pesan"=>"",
            "judul"=>"",
            "class"=>""
        );
        if($id>0){
            $ins=DB::table('absen')->where('id',$idMasuk)->update(['status'=>1]);   
        }else{
            $ins=DB::table('absen')->where('id',$idMasuk)->update(['keluar'=>$waktu,'status'=>1]);
        }
        if($ins){
            $respon['pesan']="Absen Berhasil, akan dialihkan dalam beberapa saat";
            $respon['judul']="Sukses";
            $respon['class']="success";
            $respon['success']=true;
        }else{
            $respon['pesan']="Input Error, Silahkan coba beberapa saat lagi";
            $respon['class']="danger";
            $respon['judul']="Gagal";
        }
       return response()->json($respon);
    }
}
