<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

try{
  if($_SERVER['REQUEST_METHOD']!=='POST') respond(['success'=>false,'message'=>'Method not allowed']);
  $b=read_json(); $email=trim($b['email']??''); $pwd=$b['password']??'';
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$pwd===''){ respond(['success'=>false,'message'=>'Invalid input']); }
  $pdo=mysql_pdo(); $st=$pdo->prepare('SELECT id,name,email,password_hash FROM users WHERE email=?'); $st->execute([$email]); $u=$st->fetch();
  if(!$u||!password_verify($pwd,$u['password_hash'])){ respond(['success'=>false,'message'=>'Invalid email or password']); }
  $token=bin2hex(random_bytes(24)); $r=redis_client(); $r->setex('session:'.$token,21600,(string)$u['id']);
  respond(['success'=>true,'token'=>$token,'user'=>['id'=>intval($u['id']),'name'=>$u['name'],'email'=>$u['email']]]);
}catch(Throwable $e){ respond(['success'=>false,'message'=>'Database connection failed']); }


