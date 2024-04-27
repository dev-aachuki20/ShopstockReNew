@extends('admin.exports.pdf.layout.pdf')
@section('title', 'Estimate #'.$order->invoice_number)
@section('styles')

    <style>
       .table {
             width: 100%;
             /* width: 70%;  */
             border-collapse: collapse;
             border-spacing: 0;
             margin-bottom: 20px;
             padding: 10px;
             color: #000 !important;
         }

         .table th {
             padding: 10px;
             margin-bottom: 10px;
             border-bottom: 1px solid #dee2e6;
             white-space: nowrap;
             color:#000 !important ;
             /* font-size: 15px; */
             font-size: 12px;
         }


         .table tfoot tr td {
             margin-top: 40px;
             padding: 3px 10px;
             white-space: nowrap;
             color:#000 !important;
         }

         .table td {
             /* padding: 10px;  */
             padding: 1px 1px 2px 14px;
             color:#000 !important ;
             font-size: 14px;
         }

         .table tbody tr:nth-child(2n+2) td {
             background: #F5F5F5;
         }

         .text-center {
             text-align: center;
         }

         .text-left {
             text-align: left;
         }

         .text-right {
             text-align: right;
         }

         .header {
             width: 100%;
         }

         .header tr td address {
             /* padding-top: 50px;  */
             color: #000 !important;
         }


         .invoice-info th {
             /* width: 150px; */
             text-align: right;
         }

         .invoice-info td {
             /* width: 200px; */
             text-align: right;

         }

         .invoice-info th,
         .invoice-info td {
             padding-bottom: 0px;

         }

        .table_head td{
            font-size:13px;
        }
        .table_head address{
            font-size:13px;
        }
        .table_head address p{
            margin-bottom:0 !important;
            padding-bottom:0;
            line-height: inherit;
        }
        .table_head address p strong{
            margin-bottom:0 !important;
            padding-bottom:0;
        }
        #ItemTable{
            padding-top:0;
        }

        #ItemTable,#ItemTable tbody  {
            border:1px solid #000 !important;
        }

        #ItemTable tbody td{
            font-size:11px;
            padding-bottom:4px;
            border:1px solid #000;
            /* border-top:none; */
            border-right: 1px solid #000;
        }
        #ItemTable thead th{
            padding-right:0;
            padding-left:13px;
            border:.5px solid #000 !important;
        }
        #ItemTable thead th:first-child{
            text-align:center !important;
        }
        .header{
            padding-bottom:0;
            margin-top: -80px;
        }
        .text-align-center{
            text-align:center !important;
        }
        .title_hd{
            margin-bottom:0 !important;
            padding-bottom:0;
            font-size:16px;
        }
        @page{
        margin-top: 100px; /* create space for header */
        margin-bottom: 70px; /* create space for footer */
        }

        header{
        position: fixed;
        left: 0px;
        right: 0px;
        height: 200px;
        margin-top: -60px;
        margin-bottom:100px !important;
        padding-bottom: 20px !important;
        z-index: 1000;
        }

        footer{
        position: fixed;
        bottom:0px;
        left: 0px;
        right: 0px;
        height: 50px;
        margin-bottom: -10px;
      }

      footer .pagenum:before {
      content: counter(page);
      }
      main{
        margin-top: 10px;
      }

      .cancelled-watermark {
        position: fixed;
        top: 19%;
        left: 25%;
        transform: translate(-50%, -50%);
        color: rgba(255, 0, 0, 0.2);
        transform: rotate(-20deg);
        font-size: 60px;
    }
    </style>
@stop
@section('content')
@dd($pdfData['orders'])



@stop


