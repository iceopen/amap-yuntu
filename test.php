<?php
require 'lib/Yuntu.class.php';
$data = array();
$data['_name'] = '铁心桥店';
$data['_location'] = '118.757057,31.958134';
$data['_address'] = '江东中路359号国睿大厦1号楼B区4楼A504';
//云图存储 API
//var_dump(Yuntu::dataCreate($data));//演示添加
$data['_id'] = 1;
$data['_name'] = '铁心桥店' . time();
//var_dump(Yuntu::dataUpdate($data));//演示修改
//var_dump(Yuntu::dataDelete(1));//演示删除
//var_dump(Yuntu::dataList());//查询
//图检索 API
//var_dump(Yuntu::dataSearchAround('118.757057,31.958134','铁'));//演示修改
//var_dump(Yuntu::dataSearchId(2));//演示修改