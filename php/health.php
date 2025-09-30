<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';
$out=[];
try{ $pdo=mysql_pdo(); $pdo->query('SELECT 1'); $out['mysql']=['ok'=>true]; }catch(Throwable $e){ $out['mysql']=['ok'=>false,'error'=>$e->getMessage()]; }
try{ $r=redis_client(); $r->setex('health:test',5,'1'); $out['redis']=['ok'=>$r->get('health:test')==='1']; }catch(Throwable $e){ $out['redis']=['ok'=>false,'error'=>$e->getMessage()]; }
try{ $c=mongo_collection(); $c->countDocuments(); $out['mongodb']=['ok'=>true]; }catch(Throwable $e){ $out['mongodb']=['ok'=>false,'error'=>$e->getMessage()]; }
respond(['success'=>true,'health'=>$out]);


