<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

try{
  if($_SERVER['REQUEST_METHOD']!=='POST') respond(['success'=>false,'message'=>'Method not allowed']);
  $b=read_json(); $name=trim($b['name']??''); $email=trim($b['email']??''); $pwd=$b['password']??'';
  if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pwd)<6){ respond(['success'=>false,'message'=>'Invalid input']); }
  $pdo=mysql_pdo();
  $st=$pdo->prepare('SELECT id FROM users WHERE email=?'); $st->execute([$email]); if($st->fetch()){ respond(['success'=>false,'message'=>'Email already registered']); }
  $hash=password_hash($pwd,PASSWORD_DEFAULT); $ins=$pdo->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)'); $ins->execute([$name,$email,$hash]);
  respond(['success'=>true]);
}catch(Throwable $e){ respond(['success'=>false,'message'=>'Database connection failed']); }


