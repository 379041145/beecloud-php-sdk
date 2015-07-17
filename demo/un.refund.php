<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>BeeCloud银联退款查询示例</title>
</head>
<body>
<table border="1" align="center" cellspacing=0>
    <?php
    require_once("../beecloud.php");
    date_default_timezone_set("Asia/Shanghai");

    $data = array();
    $appSecret = "39a7a518-9ac8-4a9e-87bc-7885f33cf18c";
    $data["app_id"] = "c5d1cba1-5e3f-4ba0-941d-9b0a371fe719";
    $data["timestamp"] = time() * 1000;
    $data["app_sign"] = md5($data["app_id"] . $data["timestamp"] . $appSecret);
    //选择渠道类型(WX、WX_APP、WX_NATIVE、WX_JSAPI、ALI、ALI_APP、ALI_WEB、ALI_QRCODE、UN、UN_APP、UN_WEB)
    $data["channel"] = "UN";
    $data["limit"] = 10;


    try {
        $result = BCRESTApi::refunds($data);
        if ($result->result_code != 0 || $result->result_msg != "OK") {
            echo json_encode($result->err_detail);
            exit();
        }
        $refunds = $result->refunds;
        foreach($refunds as $list) {
            echo "<tr>";
            foreach($list as $k=>$v) {
                switch ($k) {
                    case "bill_no":
                        echo "<td>订单号</td>";
                        break;
                    case "refund_no":
                        echo "<td>退款号</td>";
                        break;
                    case "total_fee":
                        echo "<td>订单金额(分)</td>";
                        break;
                    case "refund_fee":
                        echo "<td>退款金额(分)</td>";
                        break;
                    case "channel":
                        echo "<td>渠道类型</td>";
                        break;
                    case "title":
                        echo "<td>订单标题</td>";
                        break;
                    case "result":
                        echo "<td>退款是否成功</td>";
                        break;
                    case "finish":
                        echo "<td>退款是否完成</td>";
                        break;
                    case "created_time":
                        echo "<td>退款创建时间</td>";
                        break;
                }
                echo "<td>".($k=="result"?($v?"成功":"失败"):($k=="created_time"?date('Y-m-d H:i:s',$v/1000):($k=="finish"?($v?"完成":"未完成"):$v)))."</td>";
                if($k=="finish" && !$v) {
                    echo "<td><a href='ali.agree.refund.php?refund_no=".$list->refund_no."&bill_no=".$list->bill_no."&refund_fee=".$list->refund_fee."'>同意退款</a></td>";
                }
            }
            echo "</tr>";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
</table>
</body>
</html>