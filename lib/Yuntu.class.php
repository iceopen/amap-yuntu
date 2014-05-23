<?php
class Yuntu
{
    const KEY = 'cce6cf9950ffc71b797903cc7bb161f2'; //申请的高德地图的 key
    const TABLE_ID = '537efba3e4b04d1899875fac'; //云图的表ID
    //云图存储 API
    const DATA_URL = 'http://yuntuapi.amap.com/datamanage/data/';
    const DATA_CREATE = 'create';
    const DATA_UPDATA = 'update';
    const DATA_DELETE = 'delete';
    const DATA_LIST = 'list?';

    //云图检索 API
    const DATA_SEARCH_URL = 'http://yuntuapi.amap.com/datasearch/';
    const DATA_SEARCH_AROUND = 'around?'; //边检索
    const DATA_SEARCH_POLYGON = 'polygon?'; //多边形检索
    const DATA_SEARCH_ID = 'id?'; //id检索
    /**
     * GET 请求
     * @param string $url
     */
    private function httpGet($url, $param)
    {
        if (is_array($param)) {
            $url .= http_build_query($param);
        } else if (is_string($param)) {
            $url .= $param;
        }
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return json_decode($sContent, true);
        } else {
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @return string content
     */
    private function httpPost($url, $param)
    {
        $strPOST = '';
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return json_decode($sContent, true);
        } else {
            return false;
        }
    }

    /**
     * @param $param
     * @return array
     */
    protected static function setDateParam($param, $isReturn = false)
    {
        $data = array();
        $data['key'] = self::KEY;
        $data['tableid'] = self::TABLE_ID;
        if (empty($isReturn)) {
            $data['data'] = json_encode($param);
            return $data;
        } else {
            return $data;
        }
    }

    /**
     * 根据参数返回创建 _id 标示
     * @param $param
     * @return string
     */
    public static function dataCreate($param)
    {
        $rs = self::httpPost(self::DATA_URL . self::DATA_CREATE, self::setDateParam($param));
        if ($rs['status'] == 1) {
            return $rs['_id'];
        } else {
            return $rs;
        }
    }

    /**
     * 根据字段修改数据
     * @param $param
     * @return string
     */
    public static function dataUpdate($param)
    {
        $rs = self::httpPost(self::DATA_URL . self::DATA_UPDATA, self::setDateParam($param));
        return $rs['status'];
    }

    /**
     * 根据传递的ID删除
     * @param $ids
     * @return mixed
     */
    public static function dataDelete($ids)
    {
        $data = self::setDateParam(null, true);
        $data['ids'] = $ids;
        $rs = self::httpPost(self::DATA_URL . self::DATA_DELETE, $data);
        if ($rs['status'] == 1) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * 根据筛选条件查询指定tableid数据表中的数据。服务协议：HTTP/GET。
     * @param null $filter
     * @param null $sortrule
     * @param int $page
     * @param int $limit
     * @return bool
     */
    public static function dataList($filter = null, $sortrule = null, $page = 1, $limit = 100)
    {
        $data = self::setDateParam(null, true);
        if (!empty($filter)) {
            $data['filter'] = $filter;
        }
        if (!empty($sortrule)) {
            $data['sortrule'] = $sortrule;
        }
        $data['limit'] = $limit;
        $data['page'] = $page;
        $rs = self::httpGet(self::DATA_URL . self::DATA_LIST, $data);
        if ($rs['status'] == 1) {
            return $rs['datas'];
        } else {
            return false;
        }
    }

    /**
     * 周边检索
     * @param null $keywords
     * @param null $center
     * @param int $radius
     * @param null $filter
     * @param null $sortrule
     * @param int $page
     * @param int $limit
     */
    public static function dataSearchAround($center, $keywords = null, $radius = 3000, $filter = null, $sortrule = null, $page = 1, $limit = 100)
    {
        $data = self::setDateParam(null, true);
        if (!empty($keywords)) {
            $data['keywords'] = $keywords;
        }
        if (!empty($filter)) {
            $data['filter'] = $filter;
        }
        if (!empty($sortrule)) {
            $data['sortrule'] = $sortrule;
        }
        $data['center'] = $center;
        $data['radius'] = $radius;
        $data['limit'] = $limit;
        $data['page'] = $page;
        $rs = self::httpGet(self::DATA_URL . self::DATA_SEARCH_AROUND, $data);
        if ($rs['status'] == 1) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * id检索
     * @param $id
     * @return bool
     */
    public static function dataSearchId($id)
    {
        $data = self::setDateParam(null, true);
        $data['_id'] = $id;
        $rs = self::httpGet(self::DATA_URL . self::DATA_SEARCH_ID, $data);
        if ($rs['status'] == 1) {
            return $rs['datas'];
        } else {
            return false;
        }
    }


}