<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function riwayat(){
        
        return view('admin/riwayat');
    }

    public function riwayatData(Request $request){
         // The columns variable is used for sorting
        $columns = array (
            // datatable column index => database column name
            
            0 =>'name',
            1 =>'mapel',
            2 =>'materi',
            3 =>'metode',
            4 =>'masuk',
            5 =>'keluar',
            6 =>'status',
            7 =>'id',
            8 =>'kelas',
        );
        //init tanggal
        $year=Date('Y');
        $month=Date('m');
        //Getting the data
        $users = DB::table ( 'absen' )->join('users','users.id','absen.users_id')->join('kelas','kelas.id','absen.kelas_id')
        ->select ('absen.id',
            'users.name',
            'absen.mapel',
            'absen.materi',
            'absen.metode',
            'absen.masuk',
            'absen.keluar',
            'absen.status',
            'kelas.nama'
        )->whereYear('masuk', '=', $year)
        ->whereMonth('masuk', '=', $month);
        $totalData = $users->count ();            //Total record
        $totalFiltered = $totalData;      // No filter at first so we can assign like this
        // Here are the parameters sent from client for paging 
        
        if ($request->has ( 'offset' )) {
            $start = $request->input ( 'offset' );           // Skip first start records
        }else{
            $start = 0;

        }
        $length = 10;   //  Get length record from start
        /*
        * Where Clause
        */
        if ($request->has ( 'search' )) {
            if ($request->input ( 'search' ) != '') {
                $searchTerm = $request->input ( 'search' );
                /*
                * Seach clause : we only allow to search on user_name field
                */
                $users->where ( 'users.name', 'Like', '%' . $searchTerm . '%' );
            }
        }
        /*
        * Order By
        */
        // Data to client
        $jobs = $users->skip ( $start )->take ( $length );
        if ($request->has ( 'sort' )) {
            if ($request->input ( 'sort' ) != '') {
                $orderColumn = $request->input ( 'sort' );
                $orderDirection = $request->input ( 'order' );
                $jobs->orderBy ( $columns [intval ( $orderColumn )], $orderDirection );
            }
        }
        // Get the real count after being filtered by Where Clause
        $totalFiltered = $users->count ();
        

        /*
        * Execute the query
        */
        $users = $users->get ();
        /*
        * We built the structure required by BootStrap datatables
        */
        $data = array ();
        $no=1;
        foreach ( $users as $user ) {
            $nestedData = array ();
            $nestedData ['no'] = $no;
            $nestedData ['id'] = $user->id;
            $nestedData ['name'] = $user->name;
            $nestedData ['mapel'] = $user->mapel;
            $nestedData ['materi'] = $user->materi;
            $nestedData ['metode'] = $user->metode;
            $nestedData ['masuk'] = $user->masuk;
            $nestedData ['keluar'] = $user->keluar;
            $nestedData ['status'] = $user->status;
            $nestedData ['kelas'] = $user->nama;
            $data [] = $nestedData;
            $no++;
        }
        /*
        * This below structure is required by Datatables
        */ 
        $tableContent = array (
                "draw" => intval ( $request->input ( 'draw' ) ), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "total" => intval ( $totalData ), // total number of records
                "totalNotFiltered" => intval ( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "rows" => $data
        );
        return $tableContent;
    }
    public function pantau(){
        $ke=DB::table('kelas')->orderBy('kelas');   
        $mat=DB::table('mapel as m')->where('m.kelas','XII')->groupBy('m.mapel');
        return view('admin/pantau',['kelas'=>$ke,'mat'=>$mat]);
    }
    public function pantauData(Request $request){
        $id_kelas = $request->kelas;
        $kelas=DB::table('kelas')->where('id',$id_kelas)->first();
        $mat=DB::table('mapel as m')->where('m.kelas', $kelas->induk_kelas)->groupBy('m.mapel');
        $isi='';
        ob_start();
        foreach($mat->get() as $materi){
            ?>
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-6 col-xl-6">
                <div class="card bg-info mt-5">
                    <div class="card-header">
                        <h4 class="text-dark"><i class="fa fa-book"></i> <?php echo $materi->mapel;?></h4>
                    </div>
                    <div class="card-body" style="background:white;">
                        <table class="table table-responsive col-12 table-hovered table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td class="font-weight-bold text-center">No</td>
                                    <td class="font-weight-bold text-center">Materi</td>
                                    <td class="font-weight-bold text-center">Pertemuan</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $map=DB::table('mapel')->where('kelas',$materi->kelas)->where('mapel',$materi->mapel);
                                $noo=1;
                                foreach($map->get() as $mapel){
                                    $per=DB::table('absen')->where('kelas_id',$id_kelas)->where('mapel',$mapel->mapel)->where('materi',$mapel->bab)->count();
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $noo;?></td>
                                    <td><?php echo $mapel->bab;?></td>
                                    <td class="text-center"><?php echo $per;?></td>
                                </tr>
                                <?php
                                    $noo++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
        }
        $isi=ob_get_clean();
        ob_flush();
        return($isi);
    }
}