
package com.blockchain;

import java.math.BigInteger;
import java.util.List;

import org.apache.log4j.Logger;
import org.json.simple.JSONObject;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Service;
import org.web3j.abi.datatypes.generated.Bytes32;
import org.web3j.crypto.Credentials;
import org.web3j.crypto.RawTransaction;
import org.web3j.crypto.TransactionEncoder;
import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.DefaultBlockParameterName;
import org.web3j.protocol.core.methods.response.EthGetTransactionCount;
import org.web3j.protocol.core.methods.response.EthSendTransaction;
import org.web3j.protocol.http.HttpService;
import org.web3j.tuples.generated.Tuple5;
import org.web3j.utils.Numeric;

import com.entity.certificateDetails;
import com.entity.response;
import com.sm.blockchain.getBalance;
import com.sm.blockchain.sendEthersToAccount;
import com.sm.blockchain.typeCasting;
import com.smartContract.Certificates;

@Service
@Configuration
@PropertySource(value = { "classpath:application.properties" })
public class masterConstractImp {

	@Value("${userBucket.path}")
	private String userBucketPath;

	@Autowired
	private Environment env;

	private String web3NodeIp = "http://138.197.111.208:8545";
	private String senderPrivKey = "bff6ee37dd35f9adc1bb26c0dce1149468cf70f130393f2376c9ef41d0e6fa32";
	private String gasPrice = "99999999999";
	private String toAddress = "0x0B4E82f84CcC40Dd5920602ef01E75692032195f";

	// String senderPrivKey =
	// "bff6ee37dd35f9adc1bb26c0dce1149468cf70f130393f2376c9ef41d0e6fa32";
	// Credentials creds = Credentials.create(senderPrivKey);
	// static Web3j web3 = Web3j.build(new
	// HttpService("http://138.197.111.208:8545"));

	sendEthersToAccount sendEther = new sendEthersToAccount();
	Web3j web3 = Web3j.build(new HttpService(web3NodeIp));
	getBalance _getBalance = new getBalance();
	public String contractAddress = "";
	// senderPrivKey="bff6ee37dd35f9adc1bb26c0dce1149468cf70f130393f2376c9ef41d0e6fa32";

	typeCasting typeCast = new typeCasting();
	final static Logger logger = Logger.getLogger(masterConstractImp.class);
	BigInteger _garPrice = new BigInteger(gasPrice);

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
			// web3NodeIp = certDetails.web3NodeIp;
			// senderPrivKey = certDetails.senderPrivKey;
			// gasPrice = certDetails.gasPrice;
			// toAddress = certDetails.toAddress;
			// System.out.println("sasa########################################
			// " + web3NodeIp);
			// System.out.println("sasa########################################"
			// + senderPrivKey);
			// System.out.println("sasa########################################"
			// + gasPrice);

			Credentials creds = Credentials.create(senderPrivKey);
			BigInteger _gasLimit = BigInteger.valueOf(2099756);

			List<byte[]> studentId = typeCast.stringToBytes32Array(certDetails.studentId);
			List<byte[]> adharId = typeCast.stringToBytes32Array(certDetails.adharId);
			List<byte[]> certHash = typeCast.stringToBytes32Array(certDetails.certHash);
			List<byte[]> collegeId = typeCast.stringToBytes32Array(certDetails.collegeId);
			Certificates contract = Certificates.deploy(web3, creds, _garPrice, _gasLimit, studentId, adharId,
					collegeId, certDetails.pubKey, certHash).sendAsync().get();
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
			String transacionHash = sendEther(certDetails);
			if (transacionHash == null) {
				res.message = " unable to transfer funds.";
				return res;
			}
			logger.error("contract deployed successfully .. :" + contractAddress);
			certDetails.transactionId = transacionHash;
			// res = setCertificateDetails(certDetails);
			// System.out.println("contractAddress" + contractAddress);
			// if (res.message != null) {
			// res.setMessage("Error" + res);
			// return res;
			// }

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

	public String sendEther(certificateDetails certDetails) {
		try {
			Credentials creds = Credentials.create(senderPrivKey);
			System.out.println("ether transaction ...");
			EthGetTransactionCount ethGetTransactionCount = web3
					.ethGetTransactionCount(creds.getAddress(), DefaultBlockParameterName.LATEST).sendAsync().get();
			BigInteger nonce = ethGetTransactionCount.getTransactionCount();
			BigInteger _gasLimit = BigInteger.valueOf(4099756);
			byte[] byteArray = certDetails.toString().getBytes();
			String data = javax.xml.bind.DatatypeConverter.printHexBinary(byteArray);
			RawTransaction rawTransaction = RawTransaction.createTransaction(nonce, _garPrice, _gasLimit, toAddress,
					BigInteger.valueOf(1), data);
			byte[] signedMessage = TransactionEncoder.signMessage(rawTransaction, creds);
			String hexValue = Numeric.toHexString(signedMessage);
			EthSendTransaction ethSendTransaction = web3.ethSendRawTransaction(hexValue).sendAsync().get();
			return ethSendTransaction.getTransactionHash();
		} catch (Exception ex) {
			logger.error("Expection :" + ex);
			return null;
		}
	}

	// public response setCertificateDetails(certificateDetails certDetails) {
	// response res = new response();
	// try {
	// List<byte[]> studentId =
	// typeCast.stringToBytes32Array(certDetails.studentId);
	// List<byte[]> adharId =
	// typeCast.stringToBytes32Array(certDetails.adharId);
	// List<byte[]> certHash =
	// typeCast.stringToBytes32Array(certDetails.certHash);
	// List<byte[]> transactionId =
	// typeCast.stringToBytes32Array(certDetails.transactionId);
	// BigInteger _gasLimit = BigInteger.valueOf(4068756);
	//
	// Certificates contract = Certificates.load(contractAddress, web3, creds,
	// _garPrice, _gasLimit);
	// if (!contract.isValid()) {
	// res.setMessage("Invalid contract");
	// logger.error("invalide contract :" + contractAddress);
	// return res;
	// }
	// TransactionReceipt transact = contract.setCertificateDetails(studentId,
	// adharId, certDetails.collegeId,
	// certDetails.pubKey, certHash, transactionId).sendAsync().get();
	// res.setMethod("setSite");
	// res.setResult(transact.getTransactionHash());
	// return res;
	// } catch (Exception ex) {
	// // logger.error("Expection :" + ex);
	// logger.error("Expection :" + ex);
	// res.setMessage("Exception" + ex);
	// res.setMethod("setCertificateDetails");
	// return res;
	// }
	// }

	public response getCertificateDetails() {

		response res = new response();
		try {
			System.out.println("userBucketPath ::::  ");
			System.out.println("userBucketPath ::::  " + userBucketPath);
			System.out.println("userBucketPath ::::  " + env.getProperty("userBucket.path"));
			System.out.println("userBucketPath ::::  " + userBucketPath);
			BigInteger _gasLimit = BigInteger.valueOf(1068756);
			Credentials creds = Credentials.create(senderPrivKey);
			Certificates contract = Certificates.load(contractAddress, web3, creds, _garPrice, _gasLimit);
			if (!contract.isValid()) {
				res.setMessage("Invalid contract");
				logger.error("invalide contract :" + contractAddress);
				return res;
			}
			Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>> transact = contract
					.getCertificateDetails().sendAsync().get();
			certificateDetails certDetails = new certificateDetails();
			certDetails.studentId = typeCast.bytes32ArrayToString(transact.getValue1()).trim();
			certDetails.adharId = typeCast.bytes32ArrayToString(transact.getValue2()).trim();
			certDetails.collegeId = typeCast.bytes32ArrayToString(transact.getValue3()).trim();
			certDetails.pubKey = transact.getValue4();
			certDetails.certHash = typeCast.bytes32ArrayToString(transact.getValue5()).trim();
			res.setMethod("getCertificateDetails");
			res.setResult(certDetails);
			return res;
		} catch (Exception ex) {
			logger.error("Expection :" + ex);
			logger.error("Expection :" + ex);
			res.setMessage("Exception" + ex);
			res.setMethod("getCertificateDetails");
			return res;
		}
	}

}
