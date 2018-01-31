
package com.blockchain;

import java.math.BigInteger;
import java.util.List;

import org.apache.log4j.Logger;
import org.json.simple.JSONObject;
import org.springframework.stereotype.Service;
import org.web3j.abi.datatypes.generated.Bytes32;
import org.web3j.crypto.Credentials;
import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.methods.response.TransactionReceipt;
import org.web3j.protocol.http.HttpService;
import org.web3j.tuples.generated.Tuple6;

import com.entity.certificateDetails;
import com.entity.response;
import com.sm.blockchain.getBalance;
import com.sm.blockchain.sendEthersToAccount;
import com.sm.blockchain.typeCasting;
import com.smartContract.Certificates;

@Service
public class masterConstractImp {

	sendEthersToAccount sendEther = new sendEthersToAccount();
	static Web3j web3 = Web3j.build(new HttpService("http://138.197.111.208:8545"));
	getBalance _getBalance = new getBalance();
	public String contractAddress = "0xf9cd4ed81ff0336a0581e7be2abbb199e79b7f45";

	String senderPrivKey = "bff6ee37dd35f9adc1bb26c0dce1149468cf70f130393f2376c9ef41d0e6fa32";
	Credentials creds = Credentials.create(senderPrivKey);
	typeCasting typeCast = new typeCasting();
	final static Logger logger = Logger.getLogger(masterConstractImp.class);
	//
	// public TransactionReceipt sendEtherWithMetadata(Credentials credentials,
	// String address) {
	// TransactionReceipt transactionReceipt = new TransactionReceipt();
	// try {
	//
	// BigInteger _gasLimit = BigInteger.valueOf(2099756);
	// BigInteger _garPrice = BigInteger.valueOf(2000000);
	// Certificates contract = Certificates.deploy(web3, creds, _garPrice,
	// _gasLimit).sendAsync().get();
	//
	// Transaction transaction = Transaction.createFunctionCallTransaction(
	// "0xf9cd4ed81ff0336a0581e7be2abbb199e79b7f45", _garPrice,_gasLimit,
	// contractAddress,0.0001, "sas");
	//
	// org.web3j.protocol.core.methods.response.EthSendTransaction
	// transactionResponse =
	// web3j.ethSendTransaction(transaction).sendAsync().get();
	//
	// String transactionHash = transactionResponse.getTransactionHash();
	//
	//
	//
	// transactionReceipt = Transfer.sendFunds(web3, credentials, address,
	// BigDecimal.valueOf(1), Unit.ETHER)
	// .send();
	// return transactionReceipt;
	// } catch (Exception e) {
	// return transactionReceipt;
	// }
	// }

	public response smartContractDeploy(certificateDetails certDetails) {
		response res = new response();
		res.setMethod("masterSmartContractDeply");
		try {
			BigInteger _gasLimit = BigInteger.valueOf(1099756);
			BigInteger _garPrice = BigInteger.valueOf(1000000);
			Certificates contract = Certificates.deploy(web3, creds, _garPrice, _gasLimit).sendAsync().get();
			if (!contract.isValid()) {
				logger.error("invalide contract :" + contractAddress);
				res.setMessage("Invalid contract");
				return res;
			}
			// now contract is deployed.
			// doing ether transaction tothe contract address with all the
			// details
			contractAddress = contract.getContractAddress();
			certDetails.contractAddress = contract.getContractAddress();
			String transacionHash = sendEther.sendEther(certDetails);
			if (transacionHash == null) {
				res.message = " unable to transfer funds.";
				return res;
			}
			logger.error("contract  deployed successfully .. :" + contractAddress);
			certDetails.transactionId = transacionHash;
			res = setCertificateDetails(certDetails);
			System.out.println("contractAddress" + contractAddress);
			if (res.message != null) {

				res.setMessage("Error" + res);
				return res;
			}
			JSONObject result = new JSONObject();
			result.put("contractAddress", contractAddress);
			result.put("transactionHash", transacionHash);
			res.result = result;
			return res;
		} catch (Exception ex) {
			// logger.error("Expection :" + ex);
			logger.error("Expection :" + ex);
			res.setMessage("Exception" + ex);
			res.setMethod("smartContractDeploy");
			return res;
		}
	}

	public response setCertificateDetails(certificateDetails certDetails) {
		response res = new response();
		try {
			List<byte[]> studentId = typeCast.stringToBytes32Array(certDetails.studentId);
			List<byte[]> adharId = typeCast.stringToBytes32Array(certDetails.adharId);
			List<byte[]> certHash = typeCast.stringToBytes32Array(certDetails.certHash);
			List<byte[]> transactionId = typeCast.stringToBytes32Array(certDetails.transactionId);
			BigInteger _gasLimit = BigInteger.valueOf(4068756);
			BigInteger _garPrice = BigInteger.valueOf(4068756);
			Certificates contract = Certificates.load(contractAddress, web3, creds, _garPrice, _gasLimit);
			if (!contract.isValid()) {
				res.setMessage("Invalid contract");
				logger.error("invalide contract :" + contractAddress);
				return res;
			}
			TransactionReceipt transact = contract.setCertificateDetails(studentId, adharId, certDetails.mobileNumber,
					certDetails.pubKey, certHash, transactionId).sendAsync().get();
			res.setMethod("setSite");
			res.setResult(transact.getTransactionHash());
			return res;
		} catch (Exception ex) {
			// logger.error("Expection :" + ex);
			logger.error("Expection :" + ex);
			res.setMessage("Exception" + ex);
			res.setMethod("setCertificateDetails");
			return res;
		}
	}

	public response getCertificateDetails() {
		response res = new response();
		try {
			BigInteger _gasLimit = BigInteger.valueOf(1068756);
			BigInteger _garPrice = BigInteger.valueOf(1068756);
			Certificates contract = Certificates.load(contractAddress, web3, creds, _garPrice, _gasLimit);
			if (!contract.isValid()) {
				res.setMessage("Invalid contract");
				logger.error("invalide contract :" + contractAddress);
				return res;
			}
			Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>> transact = contract
					.getCertificateDetails().sendAsync().get();
			certificateDetails certDetails = new certificateDetails();
			certDetails.studentId = typeCast.bytes32ArrayToString(transact.getValue1()).trim();
			certDetails.adharId = typeCast.bytes32ArrayToString(transact.getValue2()).trim();
			certDetails.mobileNumber = (transact.getValue3());
			certDetails.pubKey = transact.getValue4();
			certDetails.certHash = typeCast.bytes32ArrayToString(transact.getValue5()).trim();
			certDetails.transactionId = typeCast.bytes32ArrayToString(transact.getValue6()).trim();
			res.setMethod("setSite");
			res.setResult(certDetails);
			return res;
		} catch (Exception ex) {
			// logger.error("Expection :" + ex);
			logger.error("Expection :" + ex);
			res.setMessage("Exception" + ex);
			res.setMethod("getCertificateDetails");
			return res;
		}
	}

}
