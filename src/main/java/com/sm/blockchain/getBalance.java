package com.sm.blockchain;

import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.DefaultBlockParameterName;
import org.web3j.protocol.core.methods.response.EthGetBalance;
import org.web3j.protocol.http.HttpService;

public class getBalance {

	static Web3j web3 = Web3j.build(new HttpService());

	public EthGetBalance getAccountBalance(String accountAddress) throws Exception {

		EthGetBalance ethGetBalance = web3.ethGetBalance(accountAddress, DefaultBlockParameterName.LATEST).sendAsync()
				.get();
		return ethGetBalance;
	}

}
