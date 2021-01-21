@extends('template')
@section('title', 'Member BaseCampTO ')

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
</style>
<?php
function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
    $earth_radius = 6371;
  
    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  
  
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  
  
    return $d;  
  }
  
$uLat = $data->latitude;
$uLong = $data->longitude;
$distance = getDistance($uLat, $uLong, -6.724055647601357, 108.54759335517885);
if ($distance < 3) {
  $lokasi = "Dalam Radius";
  $ok=1;
} else {
    $lokasi = "Diluar Radius";
    $ok=0;
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
                <li class="breadcrumb-item"><a href="#" class="text-primary">Pengajar</a></li>
                <li class="breadcrumb-item active">Absensi Mengajar</li>
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
            <a href="#" class="btn btn-primary" disabled><i class="fa fa-tachometer"></i> Dashboard</a>
            <a href="#" class="btn btn-outline-warning"><i class="fa fa-tachometer"></i> Riwayat Absensi</a>
            <a href="#" class="btn btn-outline-danger"><i class="fa fa-tachometer"></i> Pantauan Kelas</a>
            <a href="#" class="btn btn-outline-info"><i class="fa fa-tachometer"></i> Taksiran Penghasilan</a>
        </div>
    </div>
</div>
<div class="col-12 dashboard">
<div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    <div class="row">
        <div class="col-sm-12 col-xl-12 col-xs-12 col-md-12 col-lg-12" style="margin-bottom:12px;">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-history"></i> Sedang Berlangsung</h4>
                </div>
                <div class="card-body">
                    <table class="table borderless col-12 table-responsive-sm">
                    <?php $noo=1;?>
                    @foreach($aktif->get() as $aktiff)
                        <tr style="margin-bottom:0px;border:none;">
                            <td><span class="badge badge-primary">{{$noo}}</span></td>
                            <td>{{$aktiff->nama}}</td>
                            <td>{{$aktiff->mapel}}</td>
                            <td>{{$aktiff->materi}}</td>
                            <td><span class="badge badge-success">{{$aktiff->masuk}}</span></td>
                            @if($aktiff->status< 1)
                                @if(!is_null($aktiff->keluar))
                                <td><span class="badge badge-info">{{$aktiff->keluar}}</span></td>
                                <td><button class="btn btn-warning" disabled href="#">Menunggu Konfirmasi</button></td>
                                @else
                                <td><button class="selesai btn btn-info" data="{{$aktiff->id}}" >Selesai</button></td>
                                @endif
                            @else
                                <td><span class="badge badge-info">{{$aktiff->keluar}}</span></td>
                                <td><button class="btn btn-warning" disabled href="#">Telah Selesai</button></td>
                            @endif
                        </tr>
                        <?php $noo++;?>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12 col-xs-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-calendar"></i> Absensi Mengajar</h4>
                </div>
                <div class="card-body">
                    <form method="post" name="absen" id="absen">
                    @csrf
                        <input type="hidden" name="status" value="{{$ok}}">
                        <input type="hidden" name="id" value="{{Auth::user()->id}}">
                        <div class="form-group">
                            <label for="">Nama Pengajar</label>
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control" placeholder="" disabled value="{{Auth::user()->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Kelas</label>
                            <div class="input-group">
                                <select name="kelas" id="kelas" class="form-control">
                                    @foreach($kelas->get() as $kelass)
                                    <option value="{{$kelass->id}}">{{$kelass->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group sembunyi" id="kelas_lain">
                            <label for="">Masukan Nama Kelas</label>
                            <div class="input-group">
                                <input type="text" name="kelas_lain" id="" class="form-control" placeholder="Kelas Lainnya">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mapel">Mapel</label>
                            <div class="input-group">
                                <select name="mapel" id="mapel" class="form-control">
                                    <option value="Mat Wajib">Mat Wajib</option>
                                    <option value="Mat Minat">Mat Minat</option>
                                    <option value="Fisika">Fisika</option>
                                    <option value="Kimia">Kimia</option>
                                    <option value="Skolastik">Skolastik</option>
                                    <option value="Biologi">Biologi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group sembunyi" id="mapel_lain">
                            <label for="">Masukan Nama Kelas</label>
                            <div class="input-group">
                                <input type="text" name="mapel_lain" id="" class="form-control" placeholder="Mapel Lainnya">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Metode Pembelajaran</label>
                            <div class="input-group">
                                <textarea type="text" name="metode" id="" class="form-control" placeholder=""></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Materi <small>ganti kelas untuk memuat materi.</label>
                            <div class="input-group">
                                <select type="text" name="materi" id="materi" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Lokasi</label>
                            <div class="input-group">
                                <input type="text" name="lokasi" id="" class="form-control" placeholder="Lokasi" value="{{$lokasi}}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button name="btn-absen" id="btn-absen" class="btn btn-success">Isi Kehadiran</button>
                            </div>
                        </div>
                    </form>
                <div>
            </div>
        </div>
    </div>
</div>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function mAlert(judul,pesan,clas){
    $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fa fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
};
$("#btn-absen").click(function(e){
    e.preventDefault();
        $.ajax({
            data: $('#absen').serialize(),
            url: "{{url('/pengajar/action/absen')}}",
            type: "POST",
            dataType : 'json',
        }).done(function(data){
            if(data.success) {
                $('#modal').modal('hide');
                mAlert(data.judul,data.pesan,'success');
                setTimeout(function(){window.location.replace("/pengajar"); }, 1000);
            } else {
                mAlert(data.judul,data.pesan,'danger');
            }     
            
        })
})
$('#kelas').on('change', function() {
  var s= this.value;
  if(s=='13'){
      $("#kelas_lain").removeClass('sembunyi');
  }else{
    $("#kelas_lain").addClass('sembunyi');
  }
  
});
$('.selesai').on('click',function(e){
    e.preventDefault();
    var idd = $(this).attr('data');
    var url = "/pengajar/action/absen/absenkeluar/"+idd;
    $.get( url, function( data ) {
        if(data.success) {
                $('#modal').modal('hide');
                mAlert(data.judul,data.pesan,'success');
                setTimeout(function(){window.location.replace("/pengajar"); }, 1000);
        } else {
                mAlert(data.judul,data.pesan,'danger');
        } 
    });
})
$('#mapel').on('change', function(){
    var mapel = $('#mapel').val();
    var kelas = $('#kelas').val();
    if(mapel=='Lainnya'){
        $("#mapel_lain").removeClass('sembunyi');
    }else{
        $("#mapel_lain").addClass('sembunyi');
        var url = "/pengajar/action/absen/materi/"+mapel+"/"+kelas;
        $.get( url, function( data ) {
            $('#materi').empty();
            if(data.length>0){
                $.each(data, function(i, item) {
                    $('#materi').append('<option value="'+data[i].bab+'">'+data[i].bab+'</option>');
                });
                $('#materi').append('<option value="Lainnya">Lainnya</option>');
            }else{
                $('#materi').append('<option value="Lainnya">Lainnya</option>');
            }
            //$('#materi').append('<option selected="selected" value="whatever">'+data+'</option>');
        });
    }
})
</script>
<!--
<script type="text/javascript">
    var video = document.querySelector("#video-webcam");

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

    if (navigator.getUserMedia) {
        navigator.getUserMedia({ video: true }, handleVideo, videoError);
    }

    function handleVideo(stream) {
        video.src = window.URL.createObjectURL(stream);
        console.log(stream);
    }

    function videoError(e) {
        // do something
        alert("Izinkan menggunakan webcam untuk demo!")
    }

    function takeSnapshot() {
        var img = document.createElement('img');
        var context;
        var width = video.offsetWidth
                , height = video.offsetHeight;

        canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;

        context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, width, height);

        img.src = canvas.toDataURL('image/png');
        document.body.appendChild(img);
    }

</script>
-->
@endsection