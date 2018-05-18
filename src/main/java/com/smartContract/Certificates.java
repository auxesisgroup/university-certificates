package com.smartContract;

import java.math.BigInteger;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Callable;

import org.web3j.abi.FunctionEncoder;
import org.web3j.abi.TypeReference;
import org.web3j.abi.datatypes.Address;
import org.web3j.abi.datatypes.DynamicArray;
import org.web3j.abi.datatypes.Function;
import org.web3j.abi.datatypes.Type;
import org.web3j.abi.datatypes.generated.Bytes32;
import org.web3j.crypto.Credentials;
import org.web3j.protocol.Web3j;
import org.web3j.protocol.core.RemoteCall;
import org.web3j.tuples.generated.Tuple5;
import org.web3j.tx.Contract;
import org.web3j.tx.TransactionManager;

/**
 * <p>Auto generated code.
 * <p><strong>Do not modify!</strong>
 * <p>Please use the <a href="https://docs.web3j.io/command_line.html">web3j command line tools</a>,
 * or the org.web3j.codegen.SolidityFunctionWrapperGenerator in the 
 * <a href="https://github.com/web3j/web3j/tree/master/codegen">codegen module</a> to update.
 *
 * <p>Generated with web3j version 3.0.2.
 */
public final class Certificates extends Contract {
    private static final String BINARY = "6060604052341561000f57600080fd5b60405161055638038061055683398101604052808051820191906020018051820191906020018051820191906020018051919060200180519091019050600085805161005f9291602001906100c1565b5060018480516100739291602001906100c1565b5060028380516100879291602001906100c1565b5060038054600160a060020a031916600160a060020a03841617905560048180516100b69291602001906100c1565b50505050505061012b565b8280548282559060005260206000209081019282156100fe579160200282015b828111156100fe57825182556020909201916001909101906100e1565b5061010a92915061010e565b5090565b61012891905b8082111561010a5760008155600101610114565b90565b61041c8061013a6000396000f3006060604052600436106100405763ffffffff7c0100000000000000000000000000000000000000000000000000000000600035041663353089198114610045575b600080fd5b341561005057600080fd5b6100586101ae565b604051808060200180602001806020018673ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff1681526020018060200185810385528a818151815260200191508051906020019060200280838360005b838110156100d65780820151838201526020016100be565b50505050905001858103845289818151815260200191508051906020019060200280838360005b838110156101155780820151838201526020016100fd565b50505050905001858103835288818151815260200191508051906020019060200280838360005b8381101561015457808201518382015260200161013c565b50505050905001858103825286818151815260200191508051906020019060200280838360005b8381101561019357808201518382015260200161017b565b50505050905001995050505050505050505060405180910390f35b6101b66103de565b6101be6103de565b6101c66103de565b60006101d06103de565b60006101da6103de565b60006101e46103de565b60006101ee6103de565b60006101f86103de565b6000805498508860405180591061020c5750595b90808252806020026020018201604052509750600096505b8887101561026757600080548890811061023a57fe5b90600052602060002090015488888151811061025257fe5b60209081029091010152600190960195610224565b60015498508860405180591061027a5750595b90808252806020026020018201604052509550600094505b888510156102d55760018054869081106102a857fe5b9060005260206000209001548686815181106102c057fe5b60209081029091010152600190940193610292565b6004549850886040518059106102e85750595b90808252806020026020018201604052509350600092505b8883101561034357600480548490811061031657fe5b90600052602060002090015484848151811061032e57fe5b60209081029091010152600190920191610300565b6002549850886040518059106103565750595b90808252806020026020018201604052509150600090505b888110156103ae57600280548290811061038457fe5b90600052602060002090015482828151811061039c57fe5b6020908102909101015260010161036e565b50600354969d949c509a505073ffffffffffffffffffffffffffffffffffffffff90941697509295509350505050565b602060405190810160405260008152905600a165627a7a72305820a53da520f8d2ca0f6d9a606e8fc0b0d3a2b42560eba2709406d105ac423d62350029";

    private Certificates(String contractAddress, Web3j web3j, Credentials credentials, BigInteger gasPrice, BigInteger gasLimit) {
        super(BINARY, contractAddress, web3j, credentials, gasPrice, gasLimit);
    }

    private Certificates(String contractAddress, Web3j web3j, TransactionManager transactionManager, BigInteger gasPrice, BigInteger gasLimit) {
        super(BINARY, contractAddress, web3j, transactionManager, gasPrice, gasLimit);
    }

    public RemoteCall<Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>>> getCertificateDetails() {
        final Function function = new Function("getCertificateDetails", 
                Arrays.<Type>asList(), 
                Arrays.<TypeReference<?>>asList(new TypeReference<DynamicArray<Bytes32>>() {}, new TypeReference<DynamicArray<Bytes32>>() {}, new TypeReference<DynamicArray<Bytes32>>() {}, new TypeReference<Address>() {}, new TypeReference<DynamicArray<Bytes32>>() {}));
        return new RemoteCall<Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>>>(
                new Callable<Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>>>() {
                    @Override
                    public Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>> call() throws Exception {
                        List<Type> results = executeCallMultipleValueReturn(function);;
                        return new Tuple5<List<Bytes32>, List<Bytes32>, List<Bytes32>, String, List<Bytes32>>(
                                (List<Bytes32>) results.get(0).getValue(), 
                                (List<Bytes32>) results.get(1).getValue(), 
                                (List<Bytes32>) results.get(2).getValue(), 
                                (String) results.get(3).getValue(), 
                                (List<Bytes32>) results.get(4).getValue());
                    }
                });
    }

    public static RemoteCall<Certificates> deploy(Web3j web3j, Credentials credentials, BigInteger gasPrice, BigInteger gasLimit, List<byte[]> studentId, List<byte[]> adharId, List<byte[]> collegeId, String pubKey, List<byte[]> certHash) {
        String encodedConstructor = FunctionEncoder.encodeConstructor(Arrays.<Type>asList(new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(studentId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(adharId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(collegeId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.Address(pubKey), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(certHash, org.web3j.abi.datatypes.generated.Bytes32.class))));
        return deployRemoteCall(Certificates.class, web3j, credentials, gasPrice, gasLimit, BINARY, encodedConstructor);
    }

    public static RemoteCall<Certificates> deploy(Web3j web3j, TransactionManager transactionManager, BigInteger gasPrice, BigInteger gasLimit, List<byte[]> studentId, List<byte[]> adharId, List<byte[]> collegeId, String pubKey, List<byte[]> certHash) {
        String encodedConstructor = FunctionEncoder.encodeConstructor(Arrays.<Type>asList(new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(studentId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(adharId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(collegeId, org.web3j.abi.datatypes.generated.Bytes32.class)), 
                new org.web3j.abi.datatypes.Address(pubKey), 
                new org.web3j.abi.datatypes.DynamicArray<org.web3j.abi.datatypes.generated.Bytes32>(
                        org.web3j.abi.Utils.typeMap(certHash, org.web3j.abi.datatypes.generated.Bytes32.class))));
        return deployRemoteCall(Certificates.class, web3j, transactionManager, gasPrice, gasLimit, BINARY, encodedConstructor);
    }

    public static Certificates load(String contractAddress, Web3j web3j, Credentials credentials, BigInteger gasPrice, BigInteger gasLimit) {
        return new Certificates(contractAddress, web3j, credentials, gasPrice, gasLimit);
    }

    public static Certificates load(String contractAddress, Web3j web3j, TransactionManager transactionManager, BigInteger gasPrice, BigInteger gasLimit) {
        return new Certificates(contractAddress, web3j, transactionManager, gasPrice, gasLimit);
    }
}