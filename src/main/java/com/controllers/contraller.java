package com.controllers;

import org.apache.log4j.Logger;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;
import com.blockchain.masterConstractImp;
import com.entity.certificateDetails;
import com.entity.response;
import com.mysql.cj.core.util.StringUtils;
import com.sm.blockchain.sendEthersToAccount;

@RestController
public class contraller {

	final static Logger logger = Logger.getLogger(contraller.class);
	sendEthersToAccount sendEthe = new sendEthersToAccount();
	masterConstractImp contract = new masterConstractImp();
	masterConstractImp smart = new masterConstractImp();

	@RequestMapping(value = "/", method = RequestMethod.GET)
	public ResponseEntity<Object> init() {
		response res = new response();
		try {
			// certificateDetails re = new certificateDetails();
			// re.studentId = "sas";
			// re.adharId = "adharId";
			// re.mobileNumber = BigInteger.valueOf(1233);
			// re.pubKey = "001212";
			// re.transactionId = "1222";
			// res.result = sendEthe.sendEther(re);
			res.setResult("Started...........");
			return new ResponseEntity<Object>(res, HttpStatus.OK);
		} catch (Exception Ex) {
			logger.error("Expection :" + Ex);
			res.message = "Expection " + Ex;
			res.method = "init";
			res.result = null;
			return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);
		}
	}

	@RequestMapping(value = "/createCertificate", method = RequestMethod.POST)
	public ResponseEntity<Object> createCertificate(@RequestBody certificateDetails certDetails) {
		response res = new response();
		try {
			if (StringUtils.isNullOrEmpty(certDetails.studentId) || StringUtils.isNullOrEmpty(certDetails.adharId)
					|| StringUtils.isNullOrEmpty(certDetails.certHash)
					|| StringUtils.isNullOrEmpty(certDetails.mobileNumber.toString())
					|| StringUtils.isNullOrEmpty(certDetails.pubKey)) {
				res.message = "invalide input";
				res.method = "createCertificate";
				return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);

			}
			res = contract.smartContractDeploy(certDetails);
			if (res.message != null) {
				res.method = "createCertificate";
				return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);
			}
			res.method = "createCertificate";
			return new ResponseEntity<Object>(res, HttpStatus.OK);
		} catch (Exception Ex) {
			logger.error("Expection :" + Ex);
			res.message = "Expection " + Ex;
			res.method = "createCertificate";
			res.result = null;
			return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);
		}
	}

	@RequestMapping(value = "/getCertificateDetails/{Address}", method = RequestMethod.GET)
	public ResponseEntity<Object> getCertificateDetails(@PathVariable String Address) {
		response res = new response();
		try {
			contract.contractAddress = Address;
			res = contract.getCertificateDetails();
			if (res.message != null) {
				res.method = "getCertificateDetails";
				return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);
			}
			res.method = "getCertificateDetails";
			return new ResponseEntity<Object>(res, HttpStatus.OK);
		} catch (Exception Ex) {
			logger.error("Expection :" + Ex);
			res.message = "Expection " + Ex;
			res.method = "getCertificateDetails";
			res.result = null;
			return new ResponseEntity<Object>(res, HttpStatus.BAD_REQUEST);
		}
	}

}
