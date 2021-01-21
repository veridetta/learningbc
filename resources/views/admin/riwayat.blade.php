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
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#" class="text-primary">Pengajar</a></li>
                <li class="breadcrumb-item active">Riwayat Absensi</li>
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
            <a href="#" class="btn btn-warning" disabled><i class="fa fa-tachometer"></i> Riwayat Absensi</a>
            <a href="/admin/pantaukelas" class="btn btn-outline-danger"><i class="fa fa-tachometer"></i> Pantauan Kelas</a>
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
                    <h4 class="text-dark"><i class="fa fa-history"></i> Riwayat Absensi</h4>
                </div>
                <div class="card-body" style="background:white;">
                    <table
                        id="table"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="false"
                        data-show-fullscreen="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="false"
                        data-show-export="true"
                        data-click-to-select="false"
                        data-minimum-count-columns="2"
                        data-show-pagination-switch="true"
                        data-pagination="true"
                        data-id-field="id"
                        data-page-list="[10, 25, 50, 100, all]"
                        data-show-footer="false"
                        data-side-pagination="server"
                        data-url="{{url('/admin/riwayat/data')}}"
                        data-response-handler="">
                    </table>
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
    var $table = $('#table');
  function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1;
    })
    return res
  };    
  
  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      height: 550,
      columns: [
        [{
          title: 'Id',
          field: 'id',
          visible:false,
          align: 'center',
          valign: 'middle',
          sortable: true
        }, {
          title: 'Nomor',
          field: 'no',
          align: 'center',
          valign: 'middle',
          sortable: false
          
        }, {
          field: 'name',
          title: 'Nama Pengajar',
          sortable: true,
          align: 'center'
        }, {
          field: 'kelas',
          title: 'Kelas',
          sortable: true,
          align: 'center'
        }, {
          field: 'mapel',
          title: 'Mapel',
          sortable: true,
          align: 'center'
        }, {
          field: 'materi',
          title: 'Materi',
          sortable: false,
          align: 'center'
        }, {
          field: 'metode',
          title: 'Metode',
          sortable: true,
          align: 'center'
        }, {
          field: 'masuk',
          title: 'Masuk',
          sortable: true,
          align: 'center'
        }, {
          field: 'keluar',
          title: 'Keluar',
          sortable: true,
          align: 'center'
        }, {
          field: 'status',
          title: 'Status',
          sortable: true,
          align: 'center'
        }]
      ]
    })
    $table.on('all.bs.table', function (e, nama, args) {
      console.log(nama, args)
    })
  };
  
    $(function() {
    initTable()
  })
 
})
</script>
@stop