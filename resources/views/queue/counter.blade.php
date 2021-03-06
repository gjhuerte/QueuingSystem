@extends('layouts.app')

@section('after_styles')
<style>
    body{
        background-color: #264348;
    }

</style>
@endsection

@section('content')
<div class="container-fluid" style="padding: 30px;">
  <div class="panel panel-white panel-smooth" style="margin-top: 10px; padding: 20px;">
      <div class="row">
        <div class="col-md-12 font-xxl" style="margin: 10px 0;">
          <div class="call-list"><span class="text-muted">On Call:</span> </div>
        </div>
      </div>
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Counter</li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table" id="voucherTable" >
              <thead>
                <th class="no-sort">Counter</th>
                <th class="no-sort">Voucher Currently Attended</th>
              </thead>
              <tbody> </tbody>
            </table>
        </div>
      </div>
  </div>
</div>
@endsection

@section('after_scripts')
 {{-- <script src="https://js.pusher.com/4.1/pusher.min.js"></script> --}}
 {{-- <script src="socket.io/socket.io.js"></script> --}}
 {{-- <script src="js/app.js"></script> --}}

<script src="{{ asset('js/socket.io.js') }}"></script>
<script>
  $(document).ready(function(){

    call_list = [];

    table = $('#voucherTable').DataTable({
        language: {
                searchPlaceholder: "Search..."
        },
        columnDefs:[
            { targets: 'no-sort', orderable: false },
        ],
        "dom": "<'row'<'col-sm-3'><'col-sm-6'<'toolbar'>><'col-sm-3'>>" +
                        "<'row'<'col-sm-12'>>" +
                        "<'row'<'col-sm-5'><'col-sm-7'>>",
        "processing": true, 
        ajax: "{{ url('queue/counter') }}",
        columns: [
            { data: "name" },
            { data: function(callback){
              ret_val = "<ol class='list-unstyled'>"
              for(var x = 0 ; x < callback.vouchers.length; x++)
              {
                ret_val += "<li>" + callback.vouchers[x].id + "</li>"
              }

              ret_val += "</ol>"
              return ret_val
            } },
        ],
    });

    var socket = io('{{ Request::getHttpHost() }}:{{ env('SOCKET_PORT') }}');
    // var socket = io('http://192.168.10.10:3000');
    socket.on("attended-queue:App\\Events\\AttendedQueue", function(message){
        table.ajax.reload()
    });

    socket.on("call-channel:App\\Events\\CallQueue",  function (data) {
      $( ".call-list" ).append( "<span class='queue' style='padding:10px; color:white; "+ setRandomColor() + "'>  " + data.id + "  </span>" ).ready(removeElement(5000));

    });

    function getRandomColor() {

      var color_list = [
        '1abc9c', '2ecc71', '3498db', '34495e', '2c3e50', 'c0392b', '1BA39C', '34495E',
        '22313F', '2C3E50', '663399', '96281B', 'CF000F', 'D24D57', '96281B'  
      ];

      var color = '#';
      do{
        color += color_list[Math.floor(Math.random() * color_list.length)];
      }while(color.indexOf("#undefined") !== -1 || color == "#")
      return color;
    }

    function setRandomColor() {
      return "background-color:" + getRandomColor() + ";"
    }

    function removeElement(milliseconds){
      setTimeout(function() {
        $(".queue").fadeOut(1500);
      },milliseconds);
    }

  })  
</script>
@endsection
