package com.smartContract;

import java.math.BigInteger;
import java.util.Arrays;
import java.util.Collections;
import java.util.List;
import java.util.concurrent.Callable;
import org.web3j.abi.TypeReference;
import org.web3j.abi.datatypes.Address;
import org.web3j.abi.datatypes.DynamicArray;
import org.web3j.abi.datatypes.Function;
import org.web3j.abi.datatypes.Type;
import org.web3j.abi.datatypes.generated.Bytes32;
import org.web3j.abi.datatypes.generated.Uint256;
import org.web3j.crypto.Credentials;
import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.RemoteCall;
import org.web3j.protocol.core.methods.response.TransactionReceipt;
import org.web3j.tuples.generated.Tuple6;
import org.web3j.tx.Contract;
import org.web3j.tx.TransactionManager;

/**
 * <p>
 * Auto generated code.
 * <p>
 * <strong>Do not modify!</strong>
 * <p>
 * Please use the <a href="https://docs.web3j.io/command_line.html">web3j
 * command line tools</a>, or the
 * org.web3j.codegen.SolidityFunctionWrapperGenerator in the
 * <a href="https://github.com/web3j/web3j/tree/master/codegen">codegen
 * module</a> to update.
 *
 * <p>
 * Generated with web3j version 3.0.2.
 */
public final class Certificates extends Contract {
	private static final String BINARY = "6060604052341561000f57600080fd5b61067a8061001e6000396000f30060606040526004361061004b5763ffffffff7c0100000000000000000000000000000000000000000000000000000000600035041663353089198114610050578063a350b034146101c0575b600080fd5b341561005b57600080fd5b610063610302565b6040518080602001806020018781526020018673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff168152602001806020018060200185810385528b818151815260200191508051906020019060200280838360005b838110156100e75780820151838201526020016100cf565b5050505090500185810384528a818151815260200191508051906020019060200280838360005b8381101561012657808201518382015260200161010e565b50505050905001858103835287818151815260200191508051906020019060200280838360005b8381101561016557808201518382015260200161014d565b50505050905001858103825286818151815260200191508051906020019060200280838360005b838110156101a457808201518382015260200161018c565b505050509050019a505050505050505050505060405180910390f35b34156101cb57600080fd5b6102ee600460248135818101908301358060208181020160405190810160405280939291908181526020018383602002808284378201915050505050509190803590602001908201803590602001908080602002602001604051908101604052809392919081815260200183836020028082843750949686359660208082013573ffffffffffffffffffffffffffffffffffffffff1697509195506060810194506040908101358601808301945035925082918281020190519081016040528093929190818152602001838360200280828437820191505050505050919080359060200190820180359060200190808060200260200160405190810160405280939291908181526020018383602002808284375094965061053a95505050505050565b604051901515815260200160405180910390f35b61030a6105d2565b6103126105d2565b60008061031d6105d2565b6103256105d2565b600061032f6105d2565b60006103396105d2565b60006103436105d2565b600061034d6105d2565b600080549850886040518059106103615750595b90808252806020026020018201604052509750600096505b888710156103bc57600080548890811061038f57fe5b9060005260206000209001548888815181106103a757fe5b60209081029091010152600190960195610379565b6001549850886040518059106103cf5750595b90808252806020026020018201604052509550600094505b8885101561042a5760018054869081106103fd57fe5b90600052602060002090015486868151811061041557fe5b602090810290910101526001909401936103e7565b60045498508860405180591061043d5750595b90808252806020026020018201604052509350600092505b8883101561049857600480548490811061046b57fe5b90600052602060002090015484848151811061048357fe5b60209081029091010152600190920191610455565b6005549850886040518059106104ab5750595b90808252806020026020018201604052509150600090505b888110156105035760058054829081106104d957fe5b9060005260206000209001548282815181106104f157fe5b602090810290910101526001016104c3565b50600254600354979f959e509c5073ffffffffffffffffffffffffffffffffffffffff9096169a5090985093965090945050505050565b60008087805161054e9291602001906105e4565b5060018680516105629291602001906105e4565b5060028590556003805473ffffffffffffffffffffffffffffffffffffffff191673ffffffffffffffffffffffffffffffffffffffff861617905560048380516105b09291602001906105e4565b5060058280516105c49291602001906105e4565b506001979650505050505050565b60206040519081016040526000815290565b828054828255906000526020600020908101928215610621579160200282015b828111156106215782518255602090920191600190910190610604565b5061062d929150610631565b5090565b61064b91905b8082111561062d5760008155600101610637565b905600a165627a7a7230582021694c50f9053ad34a119a93a37f3d17dd80583521299e3c53588bb782595b0f0029";

	private Certificates(String contractAddress, Web3j web3j, Credentials credentials, BigInteger gasPrice,
			BigInteger gasLimit) {
		super(BINARY, contractAddress, web3j, credentials, gasPrice, gasLimit);
	}

	private Certificates(String contractAddress, Web3j web3j, TransactionManager transactionManager,
			BigInteger gasPrice, BigInteger gasLimit) {
		super(BINARY, contractAddress, web3j, transactionManager, gasPrice, gasLimit);
	}

	public RemoteCall<Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>>> getCertificateDetails() {
		final Function function = new Function("getCertificateDetails", Arrays.<Type>asList(),
				Arrays.<TypeReference<?>>asList(new TypeReference<DynamicArray<Bytes32>>() {
				}, new TypeReference<DynamicArray<Bytes32>>() {
				}, new TypeReference<Uint256>() {
				}, new TypeReference<Address>() {
				}, new TypeReference<DynamicArray<Bytes32>>() {
				}, new TypeReference<DynamicArray<Bytes32>>() {
				}));
		return new RemoteCall<Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>>>(
				new Callable<Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>>>() {
					@Override
					public Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>> call()
							throws Exception {
						List<Type> results = executeCallMultipleValueReturn(function);
						;
						return new Tuple6<List<Bytes32>, List<Bytes32>, BigInteger, String, List<Bytes32>, List<Bytes32>>(
								(List<Bytes32>) results.get(0).getValue(), (List<Bytes32>) results.get(1).getValue(),
								(BigInteger) results.get(2).getValue(), (String) results.get(3).getValue(),
								(List<Bytes32>) results.get(4).getValue(), (List<Bytes32>) results.get(5).getValue());
					}
				});
	}

	public RemoteCall<TransactionReceipt> setCertificateDetails(List<byte[]> studentId, List<byte[]> adharId,
			BigInteger mobileNumber, String pubKey, List<byte[]> certHash, List<byte[]> transactionId) {
		Function function = new Function("setCertificateDetails", Arrays.<Type>asList(
				new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
						org.web3j.abi.Utils.typeMap(studentId, org.web3j.abi.datatypes.generated.Bytes32.class)),
				new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
						org.web3j.abi.Utils.typeMap(adharId, org.web3j.abi.datatypes.generated.Bytes32.class)),
				new org.web3j.abi.datatypes.generated.Uint256(mobileNumber),
				new org.web3j.abi.datatypes.Address(pubKey),
				new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
						org.web3j.abi.Utils.typeMap(certHash, org.web3j.abi.datatypes.generated.Bytes32.class)),
				new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
						org.web3j.abi.Utils.typeMap(transactionId, org.web3j.abi.datatypes.generated.Bytes32.class))),
				Collections.<TypeReference<?>>emptyList());
		return executeRemoteCallTransaction(function);
	}

	public static RemoteCall<Certificates> deploy(Web3j web3j, Credentials credentials, BigInteger gasPrice,
			BigInteger gasLimit) {
		return deployRemoteCall(Certificates.class, web3j, credentials, gasPrice, gasLimit, BINARY, "");
	}

	public static RemoteCall<Certificates> deploy(Web3j web3j, TransactionManager transactionManager,
			BigInteger gasPrice, BigInteger gasLimit) {
		return deployRemoteCall(Certificates.class, web3j, transactionManager, gasPrice, gasLimit, BINARY, "");
	}

	public static Certificates load(String contractAddress, Web3j web3j, Credentials credentials, BigInteger gasPrice,
			BigInteger gasLimit) {
		return new Certificates(contractAddress, web3j, credentials, gasPrice, gasLimit);
	}

	public static Certificates load(String contractAddress, Web3j web3j, TransactionManager transactionManager,
			BigInteger gasPrice, BigInteger gasLimit) {
		return new Certificates(contractAddress, web3j, transactionManager, gasPrice, gasLimit);
	}
}