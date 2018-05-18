package com.entity;

public class certificateDetails {

	public String studentId;
	public String adharId;
	public String collegeId;
	public String pubKey;
	public String certHash;
	public String transactionId;
	public String contractAddress;
	public String web3NodeIp;
	public String senderPrivKey;
	public String gasPrice;
	public String toAddress;

	@Override
	public String toString() {
		return "CertificateDetails: \n [{  \n CollegeId:" + collegeId
				+ ", \n StudentId:" + studentId
				+ ", \n AadhaarId:" + adharId 
				+ ",\n contractAddress:" + contractAddress 
				+ ",\n PubKey:" + pubKey
				+ ",\n CertificateHash:" + certHash + " \n}]";
	}
}
