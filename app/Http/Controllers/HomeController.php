<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /* createTicket('this is a [org]', 'test ticket for [org]', [
            'org'=>'Test Org',
            'email'=>'feminefa@gmail.com',
            'cc'=>[
                'fgodm2@mail.missouri.edu'
            ]
        ]);*/
        return view('home');
    }
    public function send()
    {
        /* createTicket('this is a [org]', 'test ticket for [org]', [
             'org'=>'Test Org',
             'email'=>'feminefa@gmail.com',
             'cc'=>[
                 'fgodm2@mail.missouri.edu'
             ]
         ]);*/

        $message=getMessage();

        return view('send', [
            'message'=>$message[1]['body'],
            'subject'=>$message[1]['subject'],

        ]);
    }
    public function doSend(Request $request) {
       // echo $request->input('message'); die;
        $validator= \Validator::make($request->all(), [
            //  'name' => 'required|max:50',
            'data' => 'required',
            'subject'=> 'required',
           // 'message'=> 'required'

        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $data=$request->get('data');
        $data=explode("\n", $data);
        // print_r($data);
        $orgs=[];
        $i=0;
        foreach($data as $row) {
            $parts = explode("\t", $row);
            if(strpos(trim(@$parts[3]), '@')=== FALSE) {
                return back()
                    ->withError("One or more item in the list is invalid")
                    ->withInput();
            }
            if(!isset($orgs[$parts[0]])) {
                $orgs[$parts[0]]=[];
                $orgs[$parts[0]]['org_id']='';
                $orgs[$parts[0]]['org']='';
               // $orgs[$parts[0]]['cc']=[];
                $orgs[$parts[0]]['email']='';
                $orgs[$parts[0]]['members']=[];
                $i=0;
            }
            if($orgs[$parts[0]]['org']=="") {
                $orgs[$parts[0]]['org']=$parts[1];

            }

            if($orgs[$parts[0]]['org_id']=="") $orgs[$parts[0]]['org_id']=$parts[0];
            if($orgs[$parts[0]]['email']=="")  $orgs[$parts[0]]['email']=trim($parts[3]);
           // $pawprint=trim($parts[4]);
          //  if(!isset($orgs[$parts[0]]['members']))  $orgs[$parts[0]]['members']=[];
            $orgs[$parts[0]]['members'][$i]['email'] =  trim($parts[3]);
            $orgs[$parts[0]]['members'][$i]['pawprint'] =  trim($parts[4]);
            $orgs[$parts[0]]['members'][$i]['first_name'] =  trim($parts[5]);
            $orgs[$parts[0]]['members'][$i]['last_name'] =  trim($parts[6]);

            $i++;


            //print("<br><br>");
        }
        $failed=[];
        foreach($orgs as $org) {
           // print_r($org); continue;

            $cc=array();
            foreach($org['members'] as $member) {
                if($member['email']==$org['email']) continue;
                $cc[]=$member['email'];
            }
            $msg=getMessage($request->get('subject'));

            $ticket=createTicket($msg['subject'], $msg['body'], [
                'org' => $org['org'],
                'org_id' => $org['org_id'],
                'email' => $org['email'],
                'cc' => $cc,
                'status'=>10
            ]);
            if(!$ticket) {
                $failed[]=$org;
            }

           //7*999999999999999999999999999999999..99 print_r($ticket); die;
           $action=(int)@$msg['id'];

            $o=\App\Organization::where(['code'=>$org['org_id']])->get();
            if($o->count()==0) {
                \App\Organization::create([
                    'name' => $org['org'],
                    'code' => $org['org_id'],
                    'leaders' => json_encode($org['members']),
                    'ticket_id' => $ticket->id,
                    'action'=>$action,
                    'action_date'=>\Carbon\Carbon::now()->addDays(7)->toDateString()
                ]);
            }
        }

       if(empty($failed)) {
           $request->session()->flash('oldSubject', $request->get('subject'));
           return back()->withSuccess('Successfully created ticket(s)');
       }else{
           $fs="";
           foreach($failed as $org) {
               $fs=''.$org['org'].', ';
           }
           return back()-> withError("Failed sending for organization ".$fs)
               ->withInput();
       }
    }

    public function organizations()
    {
        if (isset($_GET['q'])) {
            $q=$_GET['q'];
            $orgs=\App\Organization::where([['name', 'like', '%'.$q.'%']])->orWhere([['code', 'like', '%'.$q.'%']]);
        }else {
            if (isset($_GET['filters'])) {

                session(['filters' => $_GET['filters']]);

                $orgs = $orgs = \App\Organization::where([['id', '=', 0]]);
            } else {
                $arr = filters();
                unset($arr['this_week']);
                session(['filters' => []]);
                $orgs = $orgs = \App\Organization::where([['id', '!=', 0]]);
            }

            $filters = session('filters');



            // echo \Carbon\Carbon::now()->addDays(7)->toDateString();
            $fields=[];
            foreach ($filters as $k => $filter) {
                $fields[]=$k;
                if(!array_key_exists('not_responded', $filters)) {
                    if ($k == 'migrate') $orgs->orWhere(function ($q) use ($filters) {
                        $q->where(['action' => 1]);
                        if (isset($filters['this_week'])) {
                            // $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                        }
                        if (!array_key_exists('processed', $filters)) {

                            //$q->where('status', '!=', 'processed');
                        }
                    });
                    if ($k == 'backup') $orgs->orWhere(function ($q) use ($filters) {
                        $q->where(['action' => 2]);
                        if (isset($filters['this_week'])) {
                            $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                        }
                        if (!array_key_exists('processed', $filters)) {

                            //$q->where('status', '!=', 'processed');
                        }
                    });

                    if ($k == 'delete') $orgs->orWhere(function ($q) use ($filters) {
                        $q->where(['action' => 3]);
                        if (isset($filters['this_week'])) {
                            $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                        }
                        if (!array_key_exists('processed', $filters)) {

                            $q->where('status', '!=', 'processed');
                        }

                    });
                }
                if ($k == 'responded') $orgs->where(function ($q) use ($filters) {
                    $q->where([['action', '!=', null]]);
                    if (isset($filters['this_week'])) {
                        $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                    }
                });
               /* if ($k == 'not_responded') $orgs->where(function ($q) use ($filters) {
                    $q->where([['action', '=', null]]);
                    if (isset($filters['this_week'])) {
                        $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                    }
                });*/
                if ($k == 'processed' || ($k == 'not_processed'))  {
                    $cb=function ($q) use ($filters, $k) {
                        if($k == 'processed') $q->where([['status', '=', 'processed']]);
                        else   $q->where([['status', '<>', 'processed']]);
                        if (isset($filters['this_week'])) {
                            $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                        }
                    };
                    if(!array_key_exists('migrate', $filters) && !array_key_exists('delete', $filters) && !array_key_exists('backup', $filters)) {
                        $orgs->orWhere($cb);
                    }else  $orgs->where($cb);

                }
/*
                if  $orgs->where(function ($q) use ($filters) {
                    $q->where([['status', '<>', 'processed']]);
                    if (isset($filters['this_week'])) {
                        $q->where([['action_date', '>', \Carbon\Carbon::now()->toDateString()], ['action_date', '<', \Carbon\Carbon::now()->addDays(7)->toDateString()]]);
                    }
                });*/

                //   if($k=='this_week') $orgs->orWhere([['action_date','>',\Carbon\Carbon::now()->toDateString()], ['action_date','>',\Carbon\Carbon::now()->addDays(7)->toDateString()]]);
            }



        }
        //print_r($orgs->toSql());
        $count=$orgs->count();
        $result=$orgs->paginate(40);
        return view('organizations', ['orgs'=>$result, 'count'=>$count]);
    }

    public function process(Request $request, $id) {
        $obj=\App\Organization::find($id);
        $obj->status='processed';
        $obj->save();
        updateTicket($obj->ticket_id, ['status' => 5]);//close the ticket

        return back()->withSuccess('Organization status updated');
    }
    public function delete(Request $request, $id) {
        $obj=\App\Organization::find($id);
        $obj->delete();


        return back()->withSuccess('Organization '.$obj->name.' deleted');
    }
}
