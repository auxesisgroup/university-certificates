package com.entity;

import java.math.BigInteger;

public class certificateDetails {

	public String studentId;
	public String adharId;
	public BigInteger mobileNumber;
	public String pubKey;
	public String certHash;
	public String transactionId;
	public String contractAddress;

	@Override
	public String toString() {
		return "CertificateDetails: \n [{ \n StudentId:" + studentId + ", \n AadharId:" + adharId + ",\n MobileNumber:"
				+ mobileNumber + ",\n ContractAddress:" + contractAddress + ",\n PubKey:" + pubKey
				+ ",\n CertificateHash:" + certHash + " \n}]";
	}
}
