package com.sm.blockchain;

import java.math.BigDecimal;
import java.math.BigInteger;

import org.apache.log4j.Logger;
import org.bouncycastle.util.encoders.Hex;
import org.web3j.crypto.Credentials;
import org.web3j.crypto.RawTransaction;
import org.web3j.crypto.TransactionEncoder;
import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.DefaultBlockParameterName;
import org.web3j.protocol.core.methods.response.EthGetTransactionCount;
import org.web3j.protocol.core.methods.response.EthSendTransaction;
import org.web3j.protocol.core.methods.response.TransactionReceipt;
import org.web3j.protocol.http.HttpService;
import org.web3j.tx.Transfer;
import org.web3j.utils.Convert.Unit;
import org.web3j.utils.Numeric;

import com.blockchain.masterConstractImp;
import com.entity.certificateDetails;

public class sendEthersToAccount {

	String senderPrivKey = "bff6ee37dd35f9adc1bb26c0dce1149468cf70f130393f2376c9ef41d0e6fa32";
	Credentials creds = Credentials.create(senderPrivKey);
	static Web3j web3 = Web3j.build(new HttpService("http://138.197.111.208:8545"));
	final static Logger logger = Logger.getLogger(masterConstractImp.class);
	public TransactionReceipt sendEther(Credentials credentials, String address) {
		TransactionReceipt transactionReceipt = new TransactionReceipt();
		try {
			transactionReceipt = Transfer.sendFunds(web3, credentials, address, BigDecimal.valueOf(1), Unit.ETHER)
					.send();
			return transactionReceipt;
		} catch (Exception ex) {
			logger.error("Expection :" + ex);
			return transactionReceipt;
		}
	}

	public String sendEther(certificateDetails certDetails) {
		try {
			System.out.println("ether transaction ...");
			EthGetTransactionCount ethGetTransactionCount = web3
					.ethGetTransactionCount(creds.getAddress(), DefaultBlockParameterName.LATEST).sendAsync().get();
			BigInteger nonce = ethGetTransactionCount.getTransactionCount();
			BigInteger _gasLimit = BigInteger.valueOf(4099756);
			BigInteger _garPrice = BigInteger.valueOf(4000000);
			byte[] byteArray = certDetails.toString().getBytes();
			String data = javax.xml.bind.DatatypeConverter.printHexBinary(byteArray);
			RawTransaction rawTransaction = RawTransaction.createTransaction(nonce, _garPrice, _gasLimit,
					"0x0B4E82f84CcC40Dd5920602ef01E75692032195f", BigInteger.valueOf(1), data);
			byte[] signedMessage = TransactionEncoder.signMessage(rawTransaction, creds);
			String hexValue = Numeric.toHexString(signedMessage);
			EthSendTransaction ethSendTransaction = web3.ethSendRawTransaction(hexValue).sendAsync().get();
			return ethSendTransaction.getTransactionHash();
		} catch (Exception ex) {
			logger.error("Expection :" + ex);
			return null;
		}

	}

}