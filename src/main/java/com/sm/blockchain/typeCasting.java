package com.sm.blockchain;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import org.apache.commons.codec.binary.StringUtils;
import org.web3j.abi.datatypes.generated.Bytes32;
import org.web3j.utils.Numeric;

public class typeCasting {

	public List<byte[]> stringToBytes32Array(String s) {
		List<byte[]> arrayBytes = new ArrayList<byte[]>();
		int interval = 32;
		int arrayLength = (int) Math.ceil(((s.length() / (double) interval)));
		String[] result = new String[arrayLength];
		int j = 0;
		int lastIndex = result.length - 1;
		for (int i = 0; i < lastIndex; i++) {
			result[i] = s.substring(j, j + interval);
			j += interval;
		} // Add the last bit
		result[lastIndex] = s.substring(j);
		for (int p = 0; p < result.length; p++) {
			byte[] bytes = Numeric.hexStringToByteArray(asciiToHex(result[p]));
			arrayBytes.add(bytes);
		}
		return arrayBytes;
	}

	public static Bytes32 stringToBytes32Conversion(String string) {
		byte[] byteValue = string.getBytes();
		byte[] byteValueLen32 = new byte[32];
		System.arraycopy(byteValue, 0, byteValueLen32, 0, byteValue.length);
		return new Bytes32(byteValueLen32);
	}

	public static String bytes32ArrayToString(List<Bytes32> byteArray) throws UnsupportedEncodingException {
		String newString = "";
		System.out.println("String " + StringUtils.newStringUsAscii(byteArray.get(0).getValue()).trim());
		for (Bytes32 b : byteArray) {
			newString += StringUtils.newStringUsAscii(b.getValue());
			// newString.concat(StringUtils.newStringUsAscii( b.getValue()));
		}
		return newString.trim();
	}

	public static String hexToASCII(String hexValue) {
		StringBuilder output = new StringBuilder("");
		for (int i = 0; i < hexValue.length(); i += 2) {
			String str = hexValue.substring(i, i + 2);
			output.append((char) Integer.parseInt(str, 16));
		}
		return output.toString();
	}

	// public static Bytes32 stringToBytes32(String string) {
	// byte[] byteValue = string.getBytes();
	// byte[] byteValueLen32 = new byte[32];
	// System.arraycopy(byteValue, 0, byteValueLen32, 0, byteValue.length);
	// return new Bytes32(byteValueLen32);
	// }

	public static String asciiToHex(String asciiValue) {
		char[] chars = asciiValue.toCharArray();
		StringBuffer hex = new StringBuffer();
		for (int i = 0; i < chars.length; i++) {
			hex.append(Integer.toHexString((int) chars[i]));
		}
		return hex.toString() + "".join("", Collections.nCopies(32 - (hex.length() / 2), "00"));
	}

	// public static Bytes32 stringToBytes324(String string) {
	// byte[] byteValue = string.getBytes();
	// byte[] byteValueLen32 = new byte[32];
	// System.arraycopy(byteValue, 0, byteValueLen32, 0, byteValue.length);
	// return new Bytes32(byteValueLen32);
	// }

}
