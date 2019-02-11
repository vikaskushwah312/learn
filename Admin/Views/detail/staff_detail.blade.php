@extends('Admin::layout.master')
@section('css')
 
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{@$heading}}
        
         <a href="{{ url('admin/staff-list')}}" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>   
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">

          <!-- Profile Image -->
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title text-right">{{@$heading}}</h3>
            </div>
            <div class="box-body">
             
             <div class="col-md-3">

          <!-- Profile Image -->
          <div class="">
            <div class="box-body box-profile">
              @if($staffInfo->profile_image != '')

                {!! Html::image(Config::get('constants.PROFILE_IMAGE').$staffInfo->profile_image,'',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-cicrle'])!!}
              @else
                {!! Html::image(Config::get('constants.NO_IMAGE').'user-no-image.png','',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-cicrle'])!!}

              @endif  

              <h3 class="profile-username text-center">{{ucfirst($staffInfo->first_name.' '.$staffInfo->last_name)}}</h3>

              <p class="text-muted text-center"><b>Status</b>: <span class="badge bg-green">
                @if($staffInfo->status == 1) Active @else Inactive @endif</span></p>
            </div>
          </div>

        </div>
            <div class="col-md-8 table-responsive">
              <div class="">
            <div class="box-body box-profile">
              <table id="customer-detail-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <td><b>Name</b></td>
                  <td>{{ucfirst($staffInfo->first_name.' '.$staffInfo->last_name)}}</td>
                </tr>
                <tr>
                  <td><b>Email</b></td>
                  <td>{{$staffInfo->email}}</td>
                </tr>
                <tr>
                  <td><b>Mobile Number</b></td>
                  <td>{{'+'.$staffInfo->country_code.'-'.$staffInfo->mobile_number}}</td>
                </tr>
                <tr>
                  <td><b>Gender</b></td>
                  <td>{{ucfirst($staffInfo->gender)}}</td>
                </tr>
                <tr>
                  <td><b>Country</b></td>
                  @if($staffInfo->country != "")
                    <td>{{ucfirst($staffInfo->country)}}</td>
                  @else
                    <td>N/A</td>
                  @endif
                </tr>
                <tr>
                  <td><b>State</b></td>
                  @if($staffInfo->state != "")
                    <td>{{ucfirst($staffInfo->state)}}</td>
                  @else
                    <td>N/A</td>
                  @endif
                </tr>
                <tr>
                  <td><b>City</b></td>
                  @if($staffInfo->city != "")
                    <td>{{ucfirst($staffInfo->city)}}</td>
                  @else
                    <td>N/A</td>
                  @endif
                </tr>
                 <tr>
                  <td><b>Address</b></td>
                  @if($staffInfo->address != "")
                    <td>{{ucfirst($staffInfo->address)}}</td>
                  @else
                    <td>N/A</td>
                  @endif
                </tr>
                <tr>
                  <td><b>Created At</b></td>
                    @if($staffInfo->created_at != "")
                    
                    <td><?php echo date(Config::get('constants.DATE_TIME_FORMATE'), strtotime($staffInfo->created_at)); ?></td>
                  @else
                    <td>N/A</td>
                  @endif
                </tr>
                
                </thead>
                <tbody>
                </tbody>
               
              </table>
            </div>
          </div> 
            </div>
         
          
            </div>
            <!-- /.box-body -->
          </div>
          

          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>

@stop
