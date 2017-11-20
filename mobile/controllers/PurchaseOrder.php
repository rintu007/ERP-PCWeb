<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: wenja
 * Date: 2017/11/19
 * Time: 23:55
 */
require 'ResponseJSON.php';
class purchaseOrder extends CI_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function getAllList() {
        // post format: user: {userName, userpwd}
        $post_user = json_decode(file_get_contents("php://input"));
        $result = new ResponseJSON();
        if ($post_user->userName == null || $post_user->userpwd == null || $this->mysql_model->checkUserPwd($post_user) == null) {
            $result->info = 'user error';
            echo json_encode($result);
            return;
        }

        $selectAllSQL = 'select
		            a.*,
					b.name as contactName,
					b.number as contactNo,
	    			b.linkMans as linkMans,
					c.number as staffNo ,c.name as staffName,c.mobile as staffMobile,c.email as staffEmail,
					d.number as accountNumber ,d.name as accountName,
				d.account as account,d.bank as bank,d.swiftCode as swiftCode,d.taxNumber as taxNumber,
				d.address as address,d.tel as tel,d.fax as fax,d.zipcode as zipcode,
					a.currency as currency,f.currencyCode as currencyCode,f.currencyText as currencyText,
					g.id as locationId,g.name as locationName ,g.number as locationNo
				from '.ORDER.' as a
					left join
						(select
							id,number,name,linkMans
						from '.CONTACT.'
						where (isDelete=0)
						order by id desc) as b
					on a.buId=b.id
					left join
						(select
							id,name,number,mobile,email
						from '.STAFF.'
						where (isDelete=0)
						order by id desc) as c
					on a.uid=c.id
					left join
					(select
						id,name,number,account,bank,swiftCode,taxNumber,address,tel,fax,zipcode
					from '.ACCOUNT.'
						where (isDelete=0)) as d
					on a.accId=d.id
					left join
						(select
							id,code as currencyCode,name as currencyText
						from '.CURRENCY.') as f
					on a.currency=f.id
					left join
						(select
							id,name,number
						from '.STORAGE.'
						where (isDelete=0)
						order by id desc) as g
					on a.locationId=g.id
				where
					(a.isDelete=0) ';
        $purchaseOrders = $this->mysql_model->query(ORDER, $selectAllSQL, 2);
        if (count($purchaseOrders) == 0) {
            $result->info = 'data error';
        } else {
            // purify data
            $tempArray = array();
            foreach ($purchaseOrders as $index => $row) {
                $tempArray[$index]['id']           = intval($row['id']);
                $tempArray[$index]['checkName']    = $row['checkName'];
                $tempArray[$index]['checked']      = intval($row['checked']);
                $tempArray[$index]['billDate']     = $row['billDate'];
                $tempArray[$index]['hxStateCode']  = intval($row['hxStateCode']);
                $tempArray[$index]['amount']       = (float)abs($row['amount']);
                $tempArray[$index]['transType']    = intval($row['transType']);
                $tempArray[$index]['rpAmount']     = (float)abs($row['rpAmount']);
                $tempArray[$index]['currency']     = $row['currency'];
                $tempArray[$index]['contactName']  = $row['contactName'];//$row['contactNo'].' '.$row['contactName'];
                $tempArray[$index]['description']  = $row['description'];
                $tempArray[$index]['billNo']       = $row['billNo'];
                $tempArray[$index]['totalAmount']  = (float)abs($row['totalAmount']);
                $tempArray[$index]['userName']     = $row['userName'];
                $tempArray[$index]['transTypeName']= $row['transTypeName'];
                $tempArray[$index]['disEditable']  = 0;
                $tempArray[$index]['totalQty']       = $row['totalQty'];
                $tempArray[$index]['deliveryDate']       = $row['deliveryDate'];
                $tempArray[$index]['checkName']       = $row['checkName'];
                if($row['billStatus']==2)
                {
                    $tempArray[$index]['billStatusName'] = "全部入库";
                }
                elseif ($row['billStatus']==1)
                {
                    $tempArray[$index]['billStatusName'] = "部分入库";
                }
                else
                {
                    $tempArray[$index]['billStatusName'] = "未入库";
                }
            }

            $result->status = true;
            $result->info = $tempArray;
        }
        echo json_encode($result);
    }
}