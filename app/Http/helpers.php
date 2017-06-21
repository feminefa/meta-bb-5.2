<?php
/**
 * Created by PhpStorm.
 * User: femi
 * Date: 4/3/2017
 * Time: 12:25 PM
 */


function createTicket($subject, $message, $data) {
    $subject=str_replace('[org]', @$data['org'], $subject);
    // echo $org;
    //echo '<br>';
    //echo $email1;
    //echo '<br>';
    // echo $other_emails;
    // $subject=str_replace("[org]", $org, $_POST['subject']);
    $message=str_replace("[org]", @$data['org'], $message);
    $message=str_replace("[org_id]", @$data['org_id'], $message);

    $message=str_replace("'", "\u0027", $message);
    $subject=str_replace("'", "\u0027", $subject);
    $message=addslashes($message);
    //$subject=addslashes($subject);
    $message=str_replace("\n", " ",$message );
    $message=str_replace("\r", "", $message);
    $message=str_replace("//", "\/\/", $message);
    $message=str_replace('\\\\u0027s','\\u0027s', $message);
    // $message=str_replace("<", "\<", $message);
    // $message=str_replace("(", "\(", $message);
    // $message=str_replace(")", "\)", $message);
    // $message=str_replace(">", "\>", $message);
    $other_emails="";
    foreach(@$data['cc'] as $cc) {
        if ($other_emails != "") $other_emails .= ", ";
        $other_emails .= '"' . $cc . '"';
    }
    $status=10;
    if(isset($data['status'])) $status=(int) $data['status'];
    // $cmd="curl -v -u meta@missouri.edu:M3TAr0ck$ -H \"Content-Type: application/json\" -X GET https://mizzou.freshdesk.com/helpdesk/tickets.json";
    // $cmd="curl -v -u fgodm2@mail.missouri.edu:femigb1234 -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 4, \"group_id\":1000115046, \"source\":1,  \"custom_fields\":{ \"mode_114356\":\"Email\", \"lms_114356\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }' -X POST https://feminefa.freshdesk.com/api/v2/tickets";
    // $cmd="curl -v -u fgodm2@mail.missouri.edu:femigb1234 -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 4, \"source\":1, \"cc_emails\": [".$other_emails."] }' -X POST https://feminefa.freshdesk.com/api/v2/tickets";
    // $cmd="curl -v -u meta@missouri.edu:M3TAr0ck$ -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 10, \"source\":1,  \"group_id\":1000115046, \"custom_fields\":{ \"mode\":\"Email\", \"lms\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }' -X POST https://mizzou.freshdesk.com/api/v2/tickets";
//echo $cmd;
    //  echo $message; die;
  //  $cmd="curl --insecure -u ".env('FRESHDESK_ACCOUNT')." -H \"Content-Type:application/json\"  -H \"Accept:application/json\"  -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$data['email']."\", \"priority\": 1, \"status\": ".$status.",   \"email_config_id\":1000040700, \"custom_fields\":{ \"mode\":\"Email\", \"lms\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }' -X POST https://mizzou.freshdesk.com/api/v2/tickets/outbound_email";

   // $resp =exec($cmd, $output, $return);
    $resp=getRequest('outbound_email', "{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$data['email']."\", \"priority\": 1, \"status\": ".$status.",   \"email_config_id\":1000040700, \"custom_fields\":{ \"mode\":\"Email\", \"lms\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }", 'POST');
    // print_r($resp); die;


    $obj=json_decode($resp);
    if($obj && isset($obj->subject))
        return $obj;
    // echo '<div>'.($return==0?"Sent successfully":"Failed sending!!!").': '.$data['org'].'</div>';
    else {
        //echo '<div style="color:red">Failed sending!!!: '.$data['org'].'</div>';
        // die;
        // print_r($obj);
        return false;
    }

}

function replyTicket($ticket_id, $from_email, $message, $data) {
    // $subject=str_replace('[org]', @$data['org'], $subject);
    // echo $org;
    //echo '<br>';
    //echo $email1;
    //echo '<br>';
    // echo $other_emails;
    // $subject=str_replace("[org]", $org, $_POST['subject']);
    $message=str_replace("[org]", @$data['org'], $message);
    $message=str_replace("[org_id]", @$data['org_id'], $message);
    $message=str_replace("[comment]", @$data['comment'], $message);

    $message=str_replace("'", "\u0027", $message);
    // $subject=str_replace("'", "\u0027", $subject);
    $message=addslashes($message);
    //$subject=addslashes($subject);
    $message=str_replace("\n", " ",$message );
    $message=str_replace("\r", "", $message);
    $message=str_replace("//", "\/\/", $message);
    $message=str_replace('\\\\u0027s','\\u0027s', $message);
    // $message=str_replace("<", "\<", $message);
    // $message=str_replace("(", "\(", $message);
    // $message=str_replace(")", "\)", $message);
    // $message=str_replace(">", "\>", $message);

    $other_emails="";
    foreach(@$data['cc'] as $cc) {
        if ($other_emails != "") $other_emails .= ", ";
        $other_emails .= '"' . $cc . '"';
    }
    foreach($data as $k=>$v) {
        if(!is_array($v))
            $message=str_replace("[".$k."]", $v, $message);
    }

    // $cmd="curl -v -u meta@missouri.edu:M3TAr0ck$ -H \"Content-Type: application/json\" -X GET https://mizzou.freshdesk.com/helpdesk/tickets.json";
    // $cmd="curl -v -u fgodm2@mail.missouri.edu:femigb1234 -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 4, \"group_id\":1000115046, \"source\":1,  \"custom_fields\":{ \"mode_114356\":\"Email\", \"lms_114356\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }' -X POST https://feminefa.freshdesk.com/api/v2/tickets";
    // $cmd="curl -v -u fgodm2@mail.missouri.edu:femigb1234 -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 4, \"source\":1, \"cc_emails\": [".$other_emails."] }' -X POST https://feminefa.freshdesk.com/api/v2/tickets";
    // $cmd="curl -v -u meta@missouri.edu:M3TAr0ck$ -H \"Content-Type: application/json\" -d '{ \"description\": \"".$message."\", \"subject\": \"".$subject."\", \"email\": \"".$email1."\", \"priority\": 1, \"status\": 10, \"source\":1,  \"group_id\":1000115046, \"custom_fields\":{ \"mode\":\"Email\", \"lms\":\"Blackboard\"} , \"cc_emails\": [".$other_emails."] }' -X POST https://mizzou.freshdesk.com/api/v2/tickets";
//echo $cmd;
    //  echo $message; die;
    //  echo password();


    // $cmd="curl -v -u ".env('FRESHDESK_ACCOUNT')." -H \"Content-Type: application/json\" -d '{ \"body\": \"".$message."\",  \"user_id\":1002426232, \"cc_emails\": [".$other_emails."] }' -X POST https://mizzou.freshdesk.com/api/v2/tickets/".$ticket_id."/reply";
//echo $cmd; die;
    // $resp=exec($cmd, $output, $return);
    $resp=getRequest($ticket_id.'/reply', "{ \"body\": \"".$message."\",  \"user_id\":1002426232, \"cc_emails\": [".$other_emails."] }", 'POST');
    // print_r($resp); die;
    $obj=json_decode($resp);
    // print_r($obj);
    if($obj && isset($obj->id))
        return $obj;
    // echo '<div>'.($return==0?"Sent successfully":"Failed sending!!!").': '.$data['org'].'</div>';
    else {
        //echo '<div style="color:red">Failed sending!!!: '.$data['org'].'</div>';
        // die;
        return false;
    }
}

function updateTicket($ticket_id, $params=[]) {
    //$cmd="curl  -u ".env('FRESHDESK_ACCOUNT')." -H \"Content-Type: application/json\"  -X PUT -d '".json_encode($params)."' 'https://mizzou.freshdesk.com/api/v2/tickets/".$ticket_id."'";
//echo $cmd; die;
    //$resp=exec($cmd, $output, $return);
//
    $resp=getRequest($ticket_id, $params, 'PUT');

    $obj=json_decode($resp);
    //print_r($obj); die;
    if($obj && isset($obj->id))
        return $obj;
    // echo '<div>'.($return==0?"Sent successfully":"Failed sending!!!").': '.$data['org'].'</div>';
    else {
        //echo '<div style="color:red">Failed sending!!!: '.$data['org'].'</div>';
        // die;
        return false;
    }
}


function filters() {
    return [
      //  'responded' => 'Responded',
        'migrate' => 'Migrate',
        'backup' => 'Backup+Delete',
        'delete' => 'Delete entire site',
        'this_week' => 'Act within a week',

        'not_responded' => 'Not responded',

        'processed'=>'Processed',
        //'not_processed'=>'Not Processed',
    ];
}
function getSubject($index="") {
    $arr=[
        1=>"Your blackboard organization site is scheduled to be deleted",
        2=>"Action required for [org] Blackboard organization"
    ];
   if(empty($index)) return $arr;
    else return $arr[$index];
}
function getMessage($index="") {
    $arr=[
        1=>[
          'id'=>3,
           'label'=>'Deletion notice',
            'subject'=>'The [org] blackboard organization is to be deleted',
            'body'=>'<div dir="ltr">
<p>We sent your organization leaders an email last year regarding your blackboard organization site, [org]. We noticed this site has not been accessed this year.
</p><br><p>
Blackboard will be retired at the end of this year and all inactive organization sites are being deleted.
 </p><br><p>
This organization site is scheduled to be deleted shortly unless we hear back from you.
</p><br><p>
Feel free to contact us as soon as possible if you have any questions.
</p><br><p>
Thank you.</p>
</div>
<br>
<div>&nbsp;</div>
<div dir="ltr">
<p>META (Mizzou Educational Technologies Assistance) Team</p>
<p>Educational Technologies at Missouri</p>
<p>130 Heinkel Building</p>
<p>(573) 882-3303</p>
<p>http://etatmo.missouri.edu</p>
<p>@MIZZOUelearning</p>
</div>'
        ],
        2=>[
          'id'=>0,
           'label'=>'Action required',
           'subject'=>'Action required for [org] Blackboard organization',
            'body'=> '<div dir="ltr">
<p>You are receiving this message because you are enrolled as a Leader in the Blackboard Organization site,[org].</p>
<br>
<p>Mizzou is moving away from Blackboard and has adopted Canvas as its new learning management system. Blackboard will no longer be available after December 31, 2017. We are encouraging users of organization sites to move to Canvas early.
Support and resources are available for Mizzou\'s migration to Canvas at <a href="http://canvas-migration.missouri.edu/" target="">http://canvas-migration.missouri.edu</a>.</p>
<div>&nbsp;</div>
<p>Please inform us of your required action by completing the form in the link below:</p>
<div>&nbsp;</div>
<p><a href="https://learntech.missouri.edu/response/[org_id]">https://learntech.missouri.edu/response/[org_id]</a> </p>

</div>
<br><br>
<div>&nbsp;</div>
<div dir="ltr">
<p>META (Mizzou Educational Technologies Assistance) Team</p>
<p>Educational Technologies at Missouri</p>
<p>130 Heinkel Building</p>
<p>(573) 882-3303</p>
<p>http://etatmo.missouri.edu</p>
<p>@MIZZOUelearning</p>
</div>'
        ],
        3=>[
            'id'=>0,
            'subject'=>'Thank you for submitting your response',
            'body'=> '<div dir="ltr">
<p>We have received a response to [action] Blackboard Organization site, [org].</p>
<br>
<p>This is scheduled for [date].</p>
<div>Responder: [responder]</div>
<div>Comment: [comment]</div>
<div>&nbsp;</div>
<p>Do not hesitate contacting us for further help.</p>
<div>&nbsp;</div>

</div>
<div>&nbsp;</div>
<div dir="ltr">
<p>META (Mizzou Educational Technologies Assistance) Team</p>
<p>Educational Technologies at Missouri</p>
<p>130 Heinkel Building</p>
<p>(573) 882-3303</p>
<p>http://etatmo.missouri.edu</p>
<p>@MIZZOUelearning</p>
</div>'
        ]
    ];

    if(empty($index)) return $arr;
    else return $arr[$index];
}

function getRequest($ticket_id="", $post_json="", $method='POST') {
    $url="https://mizzou.freshdesk.com/api/v2/tickets/".$ticket_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERPWD, env('FRESHDESK_ACCOUNT'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    if(!empty($post_json)) {
        if(is_array($post_json)) $post_json=json_encode($post_json, true);
        if($method=='POST') curl_setopt($ch,CURLOPT_POST, 1);
        if($method!='POST' && $method!='GET')  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_json);

    }


    $result=curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
    curl_close ($ch);
  //  echo $result; die;
    return $result;
}
