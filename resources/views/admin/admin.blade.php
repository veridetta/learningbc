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
            <a href="/admin/riwayat" class="btn btn-outline-warning"><i class="fa fa-tachometer"></i> Riwayat Absensi</a>
            <a href="/admin/pantaukelas" class="btn btn-outline-danger"><i class="fa fa-tachometer"></i> Pantauan Kelas</a>
            <a href="/admin/taksiran/" class="btn btn-outline-info"><i class="fa fa-tachometer"></i> Taksiran Penghasilan</a>
        </div>
    </div>
</div>
<div class="col-12 dashboard">
<div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    <div class="row">
        <div class="col-sm-12 col-xl-12 col-xs-12 col-md-12 col-lg-12" style="margin-bottom:12px;">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-check"></i> Konfirmasi Absen</h4>
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
                                    <td><button class="selesai btn btn-warning" data="{{$aktiff->id}}" data-pengajar="{{$aktiff->users_id}}">Konfirmasi</button></td>
                                    @else
                                    <td><button class="selesai btn btn-info" data="{{$aktiff->id}}" data-pengajar="0">Belum Selesai</button></td>
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
            <div class="card" style="margin-top:12px;">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-plus"></i> Buat Absen</h4>
                </div>
                <div class="card-body">
                <form method="post" name="absen" id="absen">
                    @csrf
                        <input type="hidden" name="status" value="1">
                        <div class="form-group">
                            <label for="">Nama Pengajar</label>
                            <div class="input-group">
                                <select name="id" id="id" class="form-control">
                                    @foreach($peng->get() as $pengajar)
                                    <option value="{{$pengajar->id}}">{{$pengajar->name}}</option>
                                    @endforeach
                                </select>
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
                            <div class="input-group">
                                <button name="btn-absen" id="btn-absen" class="btn btn-success">Isi Kehadiran</button>
                            </div>
                        </div>
                    </form>
                </div>
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
$('.selesai').on('click',function(e){
    e.preventDefault();
    var idd = $(this).attr('data');
    var idPengajar = $(this).attr('data-pengajar');
    var url = "/pengajar/action/absen/absenadmin/"+idd+"/"+idPengajar;
    $.get( url, function( data ) {
        if(data.success) {
                $('#modal').modal('hide');
                mAlert(data.judul,data.pesan,'success');
                setTimeout(function(){window.location.replace("/admin"); }, 1000);
        } else {
                mAlert(data.judul,data.pesan,'danger');
        } 
    });
})
$('#kelas').on('change', function() {
  var s= this.value;
  if(s=='13'){
      $("#kelas_lain").removeClass('sembunyi');
  }else{
    $("#kelas_lain").addClass('sembunyi');
  }
  
});
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
});
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
                setTimeout(function(){window.location.replace("/admin"); }, 1000);
            } else {
                mAlert(data.judul,data.pesan,'danger');
            }     
            
        })
})
</script>
@endsection