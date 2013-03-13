<?php

/**
 * SpanSet Media-Neutral Database
 * 
 * Copyright (C) 2011-2013 WerbeCafé
 *
 * Class MndSoap
 * Provides methodes to communicate with SpanSet Media-Neutral Database through a soap interface
 * 
 * @author  Mario Ritzerfeld <m.ritzerfeld@werbecafe.de>
 * @link    http://www.werbecafe.de
 * @license GPL
 */

class MndSoap
{
	/**
	 * Soap client instance
	 */
	protected $objClient;
	
	/**
	 * Function data
	 */
	protected $varData;
	
	/**
	 * Initite soap connection
	 *
	 * @var $strToken  Authentication token for your MND user
	 * @return void
	 */
	public function __construct($strToken = null)
	{
		// check if token is set
		if($strToken == null)
			throw new Exception('SpanSet Media-Neutral Database: Authentication token is required for soap api.'); 
		
		// connect to soap server
		$this->objClient = new SoapClient('http://login.spanset-mnd.com/api/wsdl');
		
		// send authentication header
		$objHeader = new SoapHeader('http://login.spanset-mnd.com/api/', 'authToken', $strToken);
		$this->objClient->__setSoapHeaders($objHeader);
	}
	
	/**
	 * Execute soap function
	 */
	public function __call($strFunction, $arrArgs)
	{
		// convert arguments to the right format
		if(count($arrArgs) == 1)
		{
			$varArgs = $arrArgs[0];
		}
		else
		{
			switch($strFunction)
			{
				case 'getNotifications':
				case 'getLog':
					@$varArgs['arrParams'] = array('intStart'=>$arrArgs[0], 'intStop'=>$arrArgs[1]);
					break;
				case 'getTasks':
					@$varArgs['arrParams'] = array('strType'=>$arrArgs[0], 'intStart'=>$arrArgs[1], 'intStop'=>$arrArgs[2]);
					break;
				default:
					$varArgs['arrParams'] = $arrArgs;
			}
		}		
		
		// execute soap action
		return $this->objClient->__soapCall($strFunction, array($varArgs));
	}
	
}