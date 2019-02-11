@extends('Web::layout.master_ride')

@section('content')

          <div class="row">
                  <div class="container">
                   <div class="col mt-4">
                <h5><strong>Request Ride</strong></h5>
                </div>
                <div class="col-md-auto text-center mt-4 fancy-box">
                                            <div class="media p-2">
                                                <img class="align-self-center mr-3" src="images/location.png" alt="Generic placeholder image">
                                                <div class="media-body ">
                                                    <input class="form-control model-input mb-4" id="inputPassword2" placeholder="Pick-up Location" type="text">
                                                    <input class="form-control model-input" id="inputPassword2" placeholder="Drop-up Location" type="text">

                                                </div>
                                            </div>
                                        </div>
                <div class="fancy-box mt-4 col-sm-12">
                    <div class="row brdr-b">
                        <div class="col py-2">
                            <div class="media mt-4">
                                <img class="align-self-center mr-3" src="images/cash.png" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h6 class="">
                                     <a  class="" data-toggle="modal" data-target="#exampleModal">
                        Cash</a></h6>
                     					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header ctm-header">
                                    <h2 class="modal-title color-white text-center" id="exampleModalLabel">$10 - $12</h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <h6><strong>Approx,Travel Time : 15 Mins</strong></h6>

                                    <div class="row justify-content-md-center">
                                        <div class="col-md-auto text-center mt-3 fancy-box">
                                            <div class="media p-2">
                                                <img class="align-self-center mr-3" src="images/location.png" alt="Generic placeholder image">
                                                <div class="media-body ">
                                                    <input class="form-control model-input mb-4" id="inputPassword2" placeholder="Pick-up Location" type="text">
                                                    <input class="form-control model-input" id="inputPassword2" placeholder="Drop-up Location" type="text">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer  justify-content-md-center mt-2 ctm-border">
                                        <button type="submit" class="btn theme-btn mb-2">done</button>

                                    </div>
                                    <p><strong>Note:</strong> This is an Approximate estmate.Actual
                                        <br> fares may very slightly based on traffic on discounts.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col brdr-l py-2">
                            <div class="media">
                                <img class="align-self-center mr-3 ml-2 mt-3" src="images/passanger.png" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h6 class="passanger-align">Passanger</h6>
                                    <select class="form-control ctm-dropdown" id="exampleFormControlSelect1">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row ">
                        <div class="col py-2">
                            <div class="media">
                                <img class="align-self-center mr-3" src="images/fair-estmate.png" alt="Generic placeholder image">
                                <div class="media-body">
                               <h6 class=""> <a  class="" data-toggle="modal" data-target="#exampleModal1">
                      Fair-Estimate  </a><br>10.00
                                    </h6>
                        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header ctm-header">
                                    <h2 class="modal-title color-white text-center" id="exampleModalLabel">$10 - $12</h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h6 class="text-center"><strong>Estimated Fare</strong></h6>

                                    <div class="row ">
                                        <div class="col mx-4 mt-3 fancy-box">
                                            <div class="media p-2">
                                                <img class="align-self-center mr-3" src="images/paypal.png" alt="Generic placeholder image">
                                                <div class="media-body ml-3 ">
                                                   Paypal
                                                    

                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row ">
                                        <div class="col mx-4 mt-3 fancy-box">
                                            <div class="media p-2">
                                                <img class="align-self-center mr-3" src="images/credit.png" alt="Generic placeholder image">
                                                <div class="media-body ">
                                                   Credit/debit
                                                    

                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row ">
                                        <div class="col mx-4 mt-3 fancy-box">
                                            <div class="media p-2">
                                                <img class="align-self-center mr-3" src="images/coin.png" alt="Generic placeholder image">
                                                <div class="media-body ml-3">
                                                   Cash
                                                    

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer  justify-content-md-center mt-2 ctm-border">
                                        <button type="submit" class="btn theme-btn mb-2">done</button>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                        
                                </div>
                            </div>
                        </div>
                        <div class="col brdr-l py-2">
                            <div class="media">
                                <img class="align-self-center mr-3" src="images/promo-code.png" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h6 class="mt-2">Promocode</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                   
                   
         </div>
                 
                  
                  
                  
                     
                  </div>
               </div>
              
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-8 col-xs-12">
               <div class="row">
                  <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France" width="1500" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
               </div>
            </div>
         </div>
      </div>
      
      
@endsection