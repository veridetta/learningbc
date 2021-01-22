<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Imports\NilaiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SiswaModel;
use App\Models\NilaiModel;
use Session;

class ImportController extends Controller
{
    //
    public function export_siswa(){
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }
    public function import_siswa(Request $request){
        // validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('pendataan',$nama_file);

		// import data
		Excel::import(new SiswaImport, public_path('/pendataan/'.$nama_file));

		// notifikasi dengan session
		Session::flash('sukses','Data Siswa Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/admin/pendataan');
	}
	public function import_penilaian(Request $request){
        // validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('penilaian',$nama_file);

		// import data
		Excel::import(new NilaiImport, public_path('/penilaian/'.$nama_file));

		// notifikasi dengan session
		Session::flash('sukses','Data Nilai Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/admin/penilaian');
    }
}
