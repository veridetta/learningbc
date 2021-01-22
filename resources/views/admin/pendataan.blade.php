@extends('template')
@section('title', 'Pengaturan Database ')

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
    th {
    text-align: center;
    vertical-align: middle !important;
}
</style>
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#" class="text-primary">Admin</a></li>
                <li class="breadcrumb-item active">Pendataan</li>
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
            <a href="/admin" class="btn btn-outline-primary" disabled><i class="fa fa-tachometer"></i> Dashboard</a>
            <a href="/admin/riwayat" class="btn btn-outline-warning"><i class="fa fa-tachometer"></i> Riwayat Absensi</a>
            <a href="/admin/pantaukelas" class="btn btn-outline-danger"><i class="fa fa-tachometer"></i> Pantauan Kelas</a>
            <a href="/admin/taksiran/" class="btn btn-outline-info"><i class="fa fa-tachometer"></i> Taksiran Penghasilan</a>
            <a href="#" class="btn btn-primary"><i class="fa fa-list"></i> Pendataan</a>
            <a href="/admin/penilaian" class="btn btn-outline-success"><i class="fa fa-check"></i> Penilaian</a>
        </div>
    </div>
</div>
<div class="col-12 dashboard">
<div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    <div class="row">
        <div class="col-sm-12 col-xl-12 col-xs-12 col-md-12 col-lg-12" style="margin-bottom:12px;">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark"><i class="fa fa-check"></i> Data Siswa</h4>
                </div>
                <div class="card-body">
                    {{-- notifikasi form validasi --}}
                    @if ($errors->has('file'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('file') }}</strong>
                    </span>
                    @endif
            
                    {{-- notifikasi sukses --}}
                    @if ($sukses = Session::get('sukses'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $sukses }}</strong>
                    </div>
                    @endif
            
                    <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#importExcel">
                        IMPORT EXCEL
                    </button>
            
                    <!-- Import Excel -->
                    <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="post" action="/admin/import/siswa" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                                    </div>
                                    <div class="modal-body">
            
                                        {{ csrf_field() }}
            
                                        <label>Pilih file excel</label>
                                        <div class="form-group">
                                            <input type="file" name="file" required="required">
                                        </div>
            
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Import</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            
                    
                    <a href="/siswa/export_excel" class="btn btn-success my-3" target="_blank">EXPORT EXCEL</a>
                    <table
                        id="table"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="false"
                        data-show-fullscreen="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="true"
                        data-show-export="true"
                        data-filter-control="true"
                        data-show-search-clear-button="true"
                        data-click-to-select="false"
                        data-minimum-count-columns="2"
                        data-show-pagination-switch="true"
                        data-pagination="true"
                        data-id-field="id"
                        data-page-list="[10, 25, 50, 100, all]"
                        data-show-footer="false"
                        data-side-pagination="server"
                        data-url="{{url('/admin/siswa/data')}}"
                        data-response-handler="">
                        <thead>
                            <tr>
                            <th data-field="id">ID</th>
                            <th data-field="no">Nomor</th>
                            <th data-field="nis" data-filter-control="select">Nis</th>
                            <th data-field="nama" style="text-align: center; vertical-align: middle;">Nama</th>
                            <th data-field="kelas" data-filter-control="select">Kelas</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1;
    })
    return res
  };    
  var $table = $('#table');
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
          field: 'nis',
          title: 'NIS',
          sortable: true,
          align: 'center'
        },{
          field: 'nama',
          title: 'Nama Siswa',
          sortable: true
        }, {
          field: 'kelas',
          title: 'Kelas',
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
@endsection