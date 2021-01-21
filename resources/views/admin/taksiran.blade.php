@extends('template')
@section('title', 'Taksiran Penghasilan')

@section('intro-header')
<style>
    #my_camera{
    height: 240px;
    border: 1px solid black;
    }
    .sembunyi{
        display:none;
    }
    .borderless td, .borderless th {
        border: none;
    }
    .table-responsive {
        display: table;
    }
</style>
<?php
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
function fee($jam,$menit,$fee){
    $permenit=$fee/60;
    if($menit < 45){
        $pe['fee']=$fee + ($permenit * $menit);
    }else{
        $pe['fee']=2*$fee;
    }
    if($menit==60){
        $pe['waktu']=($jam+1)." jam ";
    }else{
        $pe['waktu']=$jam." jam ".$menit." menit";
    }
    return $pe;
}
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#" class="text-primary">Admin</a></li>
                <li class="breadcrumb-item active">Taksiran Penghasilan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection
@section('main')
<div class="col-12" style="margin-bottom:12px;">
    <div class="card" style="background:#f4f6f9;border:none  !important;">
        <div class="card-body">
            <a href="/admin" class="btn btn-outline-primary" ><i class="fa fa-tachometer"></i> Dashboard</a>
            <a href="/admin/riwayat" class="btn btn-outline-warning" disabled><i class="fa fa-tachometer"></i> Riwayat Absensi</a>
            <a href="/admin/pantaukelas" class="btn btn-outline-danger"><i class="fa fa-tachometer"></i> Pantauan Kelas</a>
            <a href="#" class="btn btn-info"><i class="fa fa-tachometer"></i> Taksiran Gaji</a>
        </div>
    </div>
</div>
<div class="col-12 dashboard">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    <div class="row">
        <div class="col-sm-12 col-xl-12 col-xs-12 col-md-12 col-lg-12" style="margin-bottom:12px;">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-money"></i> Total Gaji Bulan Ini</h4>
                </div>
                <div class="card-body" style="background:white;">
                    <table class="table table-responsive col-12 table-hovered table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td class="font-weight-bold text-center">No</td>
                                    <td class="font-weight-bold text-center">Pengajar</td>
                                    <td class="font-weight-bold text-center">Pertemuan</td>
                                    <td class="font-weight-bold text-center">Jam</td>
                                    <td class="font-weight-bold text-center">Fee</td>
                                    <td class="font-weight-bold text-center">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $noo=1;
                                $month = date('m');
                                $gaji=0;
                                $durasi=0;
                                foreach($peng->get() as $guru){
                                    $per=DB::table('absen')->where('users_id',$guru->id)->whereMonth('masuk', '=', $month)->count();
                                    $total = 2*$per*$guru->fee;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $noo;?></td>
                                    <td><?php echo $guru->name;?></td>
                                    <td class="text-center"><?php echo $per;?>  Pertemuan</td>
                                    <td class="text-center">2  jam</td>
                                    <td class="text-center"><?php echo rupiah($guru->fee);?></td>
                                    <td class="text-center"><?php echo rupiah($total);?></td>
                                </tr>
                            <?php
                                $noo++;
                                $gaji+=$total;
                            }
                            ?>
                            <tr>
                                <td colspan="5" class="font-weight-bold text-center">Total Gaji Bulan Ini</td>
                                <td class="font-weight-bold text-center"><?php echo rupiah($gaji);?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-history"></i> Taksiran Gaji</h4>
                </div>
                <div class="card-body" style="background:white;">
                    <form method="post" id="pantauan" name="pantauan">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select name="guru" id="guru" class="form-control">
                                            @foreach($peng->get() as $pengajar)
                                            <option value="{{$pengajar->id}}">{{$pengajar->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <button class="btn btn-info btn-block" id="btn_kelas" name="btn_kelas">Ganti</button>
                                </div> 
                            </div>
                        </div>
                    </form>
                    <div class="row" id="xcontent">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function mAlert(judul,pesan,clas){
        $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fa fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
    };
    $("#btn_kelas").click(function(e){
        e.preventDefault();
        $.ajax({
            data: $('#pantauan').serialize(),
            url: "{{url('/admin/taksiran/data')}}",
            type: "POST",
            dataType : 'html',
        }).done(function(data){ 
            $("#xcontent").html(data);
        })
    })
    $("#btn_kelas").trigger('click');
})
</script>
@stop