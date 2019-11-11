<?php

/**
 * qpayMch.config.php
 * Created by HelloWorld
 * vers: v1.0.0
 * User: Tencent.com
 */
class QpayMchConf
{
    /**
     * QQ钱包商户号
     */

    const MCH_ID = QQ_API_MCH_ID;

    /**
     * API密钥。
     * QQ钱包商户平台(http://qpay.qq.com/)获取
     */

    const MCH_KEY = QQ_API_MCH_KEY;
	
	/**
	 * API证书绝对路径
	 * QQ钱包接口中，涉及资金回滚的接口会使用到商户证书，包括退款、撤销接口。商家在申请QQ钱包支付成功后，收到的相应邮件后，可以按照指引下载API证书，也可以按照以下路径下载：QQ钱包商户平台(https://qpay.qq.com/)-->账户管理-->API安全 。
	 */
	const SSLCERT_PATH = '/home/cert/apiclient_cert.pem';
	const SSLKEY_PATH = '/home/cert/apiclient_key.pem';

	/**
	 * 企业付款-操作员ID
	 */
	const OP_USERID = '100000@1405050601';

	/**
	 * 企业付款-操作员密码
	 */
	const OP_USERPWD = '123456';

}