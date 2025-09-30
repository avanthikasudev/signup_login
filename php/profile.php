<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

try{
  $uid=bearer_user_id(); if(!$uid) respond(['success'=>false,'message'=>'Unauthorized']);
  $col=mongo_collection();
  if($_SERVER['REQUEST_METHOD']==='GET'){
    $doc=$col->findOne(['user_id'=>$uid]);
    $p=$doc?[ 'age'=>intval($doc['age']??0),'dob'=>$doc['dob']??null,'contact'=>$doc['contact']??'','address'=>$doc['address']??'' ]:null;
    respond(['success'=>true,'profile'=>$p]);
  }
  if($_SERVER['REQUEST_METHOD']==='POST'){
    $b=read_json();
    $p=['user_id'=>$uid,'age'=>intval($b['age']??0),'dob'=>$b['dob']??null,'contact'=>trim($b['contact']??''),'address'=>trim($b['address']??'')];
    $col->updateOne(['user_id'=>$uid],['$set'=>$p],['upsert'=>true]);
    respond(['success'=>true]);
  }
  respond(['success'=>false,'message'=>'Method not allowed']);
}catch(Throwable $e){ respond(['success'=>false,'message'=>'Server error']); }


