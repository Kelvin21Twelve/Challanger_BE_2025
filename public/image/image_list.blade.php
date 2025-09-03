<style>
   .dataTables_filter{
     display:none
   }
   .icon-xxl {
        font-size: 1.35rem !important;
   }
   .company-listing-gridview {
    border: solid 1px #e8ecef;
    margin-bottom: 30px;
    height: 243px;
    }
    img {
    vertical-align: middle;
    border-style: none;
    }
    .company-listing-gridview .company-logins-card {
        height: 157px;
        width: 217px;
        margin: 0px auto;
    }
    
    .company-logins-card img {
    max-width: 100%;
    max-height: 100%;
    }
    .vp-company-buttonsview {
      display: none;
    }

    .vp-company-buttonsview {
        bottom: 10px;
        width: 100%;
        text-align: center;
    }

    .company-listing-gridview:hover .vp-company-buttonsview{
        display: block;
        left: 0px;
        bottom: 0px;
        padding: 7px 0px;
        background: #ddd;
    }

    
    
    i.la.la-trash-alt.cust-box-icon {
    color: white;
    }
</style>
@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
@section('content')
@php
$lang =  \Session::get('lang');
if(empty($lang)){
$lang = "en";
}
app()->setLocale($lang);
@endphp
<section class="price-container p-0 about-us-container-details">
   <div class="container">
      <div class="row front-home-pricing-plan-all-one">
         <div class="container">
            <div class="row">
               <div class="col-sm-12 py-2">
                  <div class="section-title text-center">
                     <h2>{{ __('lang.gallery_image') }}</h2>
                     <a href="{{ url('/image-create' ) }}">
                       <button class="btn btn-primary btn-sm" data-repeater-create type="submit" style=" float: right;">
                            <i class="las la-plus cust-box-icon" aria-hidden="true"></i>
                            <span class="invoice-repeat-btn" style="font-size:20px">{{ __('lang.add') }}</span>
                        </button>
                         </a>
                  </div>
                 
               </div>
               
               
               <div class="grid-views-sections">
               
    <div class="row">
      @if(!$datas->isEmpty())
      @foreach($datas as $data)

      <div class="col-md-3" id="{{en_de_crypt($data->id,'e')}}" style="max-width:none;">
      <div class="card-body text-center company-listing-gridview">
          <div class="company-logins-card">
            <img src="{{asset('/image')}}/{{$data->image}}"  alt="profile image">
          </div>
          <!-- <h4 style="margin:0px;"></h4> -->
          <span class="vp-email-address-st">  </span>
          <p class="card-text">
           
          </p>
          <div class="vp-company-buttonsview">
            <a href="{{ url('/image/edit/' .en_de_crypt($data->id,'e')) }}">
                <button class="btn btn-primary btn-xs"><span class="cust-box-icon-add"> 
                <i class="la la-pencil-alt cust-box-icon"></i> </span> </button></a>
            <a data-url="{{ url('image/delete')}}" onclick="deleteimage()" data-route="{{ url('image/delete')}}" data-id="{{ en_de_crypt($data->id,'e') }}" data-model="Gallery_img" class="s_delete">
                <button class="btn btn-danger btn-xs" style="font-size: 1.2rem;"><span class="cust-box-icon-add"> <i class="la la-trash-alt cust-box-icon"></i> </span> </button>
            </a>
            <!-- <button class="btn btn-danger btn-xs delete" data-id="{{en_de_crypt($data->id ,'e')}}" data-model="Company"><span class="cust-box-icon-add"> <i class="la la-trash-alt cust-box-icon"></i> </span></button> -->
          </div>
        </div>


      </div>
      @endforeach
      @else
      <div class="card card-inverse-info col-md-12" id="context-menu-simple">
        <div class="card-body">
          <p class="card-text"> {{ __('lang.No_Data_Found') }}  </p>
        </div>
      </div>
      @endif
    </div>
    <div>
      {{ $datas->links('pagination::bootstrap-4') }}
      </div>
  </div>
            </div>
         </div>
      </div>
   </div>
</section>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
   var table = $('#gallery_img').DataTable({ 
         select: false,
         "columnDefs": [{
             className: "Name", 
             "targets":[0],
             "visible": false,
             "searchable":false,
             "bFilter": false
         }]
     });//End of create main table
   
</script> 
@endsection