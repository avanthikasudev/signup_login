<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

function envv($k,$d){ $v=getenv($k); return $v!==false?$v:$d; }
function respond($a){ echo json_encode($a); exit; }

function mysql_pdo(){
  static $pdo=null; if($pdo) return $pdo;
  $host=envv('MYSQL_HOST','127.0.0.1');
  $db=envv('MYSQL_DATABASE','signup_login');
  $user=envv('MYSQL_USER','root');
  $pass=envv('MYSQL_PASSWORD','');
  $dsn="mysql:host=$host;dbname=$db;charset=utf8mb4";
  $pdo=new PDO($dsn,$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
  $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(191) UNIQUE NOT NULL, password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
  return $pdo;
}

function redis_client(){ static $r=null; if($r) return $r; $r=new Redis(); $r->connect(envv('REDIS_HOST','127.0.0.1'),intval(envv('REDIS_PORT','6379'))); return $r; }

function mongo_collection(){ static $c=null; if($c) return $c; $client=new MongoDB\Client(envv('MONGODB_URI','mongodb://127.0.0.1:27017')); $c=$client->{envv('MONGODB_DB','signup_login')}->profiles; $c->createIndex(['user_id'=>1],['unique'=>true]); return $c; }

function read_json(){ $d=json_decode(file_get_contents('php://input'),true); return is_array($d)?$d:[]; }

function bearer_user_id(){ $h=getallheaders(); $a=$h['Authorization']??($h['authorization']??''); $t=null; if(strpos($a,'Bearer ')===0){ $t=substr($a,7); } elseif(isset($_GET['token'])){ $t=$_GET['token']; } if(!$t) return null; $r=redis_client(); $uid=$r->get('session:'.$t); return $uid?intval($uid):null; }


