<?php
/**
 * Created by PhpStorm.
 * User: femi
 * Date: 4/4/2017
 * Time: 8:25 PM
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function respond($org) {
        $org=\App\Organization::where('code', $org)->first();
        if($org==null) {

            abort(404);
            return;
        }
        return view('respond',['org'=>$org]);
    }
    public function responded(Request $request, $org) {
        $org=\App\Organization::where('code', $org)->first();
        if($org==null) {

            abort(404);
            return;
        }
        $validator= \Validator::make($request->all(), [
            //  'name' => 'required|max:50',
            'action' => 'required',
            'date'=> 'required',
            'pawprint'=> 'required'

        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $org->action=(int)$request->action;
        $org->action_date=$request->date;
        $org->responder=$request->pawprint;
        $org->comment=$request->comment;
        $org->responded_at=\Carbon\Carbon::now();
        switch($org->action) {
            case 1: $action='migrate'; break;
            case 2: $action='backup and delete'; break;
            case 3: $action='delete'; break;
            default: $action='delete'; break;
        }

        $message=getMessage(3)['body'];
        $action_date= \Carbon\Carbon::parse( $org->action_date);
        $cc=[];
        $responder='';
        foreach(json_decode($org->leaders) as $leader) {
            $cc[]=$leader->email;
            if($leader->pawprint==$request->pawprint) $responder=$leader->first_name.' '.$leader->last_name.' ('.$leader->pawprint.')';
        }
        if(isset($cc[0])) unset($cc[0]);
        if($org->save()) {
              replyTicket($org->ticket_id, 'blackboard@missouri.edu', $message, [
                  'org' => $org->name,
                  'org_id' => $org->code,
                  'email' => '',
                  'cc' => $cc,
                  'responder'=>$responder,
                  'action'=>$action,
                  'comment'=>empty($org->comment)?"None":$org->comment,
                  'date' =>$action_date->format('m/d/Y')
              ]);
            //Charlie =1002201053
            //Tanys = 1002426019
            //Gary = 1002290368
            //META = 1002426232
            if($action_date->lte(\Carbon\Carbon::now()->addDays(7))) {
                if($org->action==1 || $org->action==2) {

                    updateTicket($org->ticket_id, ['responder_id' => 1002426232]);//assign to Tanys
                }
                if($org->action==3) {
                    //updateTicket($org->ticket_id, ['responder_id' => 1002426232]);//assign to Garry
                }
            }

            return back()
                ->withSuccess('Your response has been saved!');
        }
        return back()
            ->withError('Unable to save response. Please try again.')
            ->withInput();

    }
    public function upcoming() {
        $date=\Carbon\Carbon::now()->addDays(7);
       // print_r($date);
        $upcoming=\App\Organization::where([['responder','!=',null],['action_date', '>', '2017-05-19'], ['action_date', '<', $date],['assigned','=',false]]);
       // print_r($upcoming->toSql());
        $upcoming=\App\Organization::where([['responder','!=',null],['action_date', '>', '2017-05-19'], ['action_date', '<', $date]]);
print_r($upcoming->get());
        die('femi');
        foreach($upcoming->get() as $org) {
            updateTicket($org->ticket_id, ['responder_id' => 1002426232]);//assign to Tanys
            $org->assigned=true;
            $org->save();

        }
        return $upcoming->count();
    }

}
