<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;                 
use App\Models\{Promocode,AppInfo,Version,Country,State,City,CancelReason,Users,Charges,Content};
use Validator,Redirect,Session,URL,Config,Response;


class ContentController extends Controller
{
  
  /*
  * Name: addPassenger
  * Create Date: 28 Mar 2018
  * Purpose:To show the contents
  */
  public function contents(Request $request){

      $data['title'] = 'Contents List';
      
      return view("Admin::detail.contents",$data);
    

  }/*Contents End */

  public function contentListData(Request $request){

    $query = Content::query();
    
    // Datatables Variables
    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];
        
        $query->where('name',"LIKE","%$value%");
    }
    
    //Order
    $orderByField = "content.id";
    $orderBy = 'desc';

    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {                        
        $orderBy = $request->get('order')[0]['dir'];
    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

      if ($request->get('order')[0]['column'] == 0) {
                    
          $orderByField = "content.id";
                
      }

    }

    $total = $query->count();
      
    $info = $query->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get();
                  

    $data = array();
    $sno = $start;

    foreach ($info as $content) {
    
      $editUrl = URL::to('admin/edit-content',[$content->id]);  
      //edit url       
    
      $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Content" title="edit-content" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Content</a>';    
      
          $data[] =array(
          $sno = $sno+1,
              ucfirst($content->name),
              ucfirst($content->value),
              
          '<div class="manage_drop_d">
            <div class="dropdown">
              <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
              </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                  <li>'.$edit_url.'</li>
                </ul>
           
            </div>
          </div>'
            ); 

      }/*foreach*/
      $output = array(
                      "draw"            => $draw,
                      "recordsTotal"    => $total,
                      "recordsFiltered"  => $total,
                      "data"             => $data
                    );
      echo json_encode($output);
      exit();       
    


  }/*Content List Data End*/

    public function editContent(Request $request,$id){

      if ($request->isMethod('post')) {
          
          $validation = Validator::make($request->all(), [
               'name'  => 'required',
               'value' => 'required',]);

            if ($validation->fails()) {
              
                return Redirect::to("admin/edit-content/$id")->withErrors($validation)->withInput();

            }else{

                $date = array('name'  => $request->name,
                                'value' => $request->value,);

                $update = Content::where(array('id' => $id))->update($date);

                  if($update){

                    return Redirect::to("admin/contents")->withSuccess('Great! info has been updated.');
                  
                  }else{
                    return Redirect::to("admin/edit-content/$id")->withFail('Something went to wrong.');
                  }
            }

      }else{

        $data['title'] ="Edit Content";
        $data['content_info'] = Content::where('id',$id)
                        ->select('id','name','value')
                        ->first();
        return view ("Admin::edit.edit_content",$data);

      }

    } /*Edit Content End*/
  

}//Content Controller End