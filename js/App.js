//import getWeb3 from "./web3";

var Web3 = require('web3');
var web3 = new Web3( new Web3.providers.HttpProvider("http://203.250.148.23:5800"));
var network_version = web3.version.network;
console.log("network_id: " + network_version);

var accounts = web3.eth.accounts;  //account 정보 가져오기
var from_account = accounts[1];

web3.personal.unlockAccount(from_account, "sh910624", 0);

var abi = {};
function asignABI(data) {
    abi = data;
}
$.ajax({
    url: "./PowerMarket.json",
    async: false,
    dataType: 'json',
    success: function (data) {
        asignABI(data);
    }
});

$.getJSON('./PowerMarket.json', function (data) {
    //abi = [{ "constant": false, "inputs": [{ "name": "idx", "type": "uint256" }], "name": "withdrawMyPower", "outputs": [], "payable": false, "stateMutability": "nonpayable", "type": "function" }, { "constant": false, "inputs": [{ "name": "idx", "type": "uint256" }, { "name": "_power", "type": "uint256" }], "name": "buyPower", "outputs": [], "payable": true, "stateMutability": "payable", "type": "function" }, { "constant": true, "inputs": [], "name": "getMyAsset", "outputs": [{ "name": "_return_power", "type": "uint256" }, { "name": "_return_price", "type": "uint256" }], "payable": false, "stateMutability": "view", "type": "function" }, { "constant": true, "inputs": [], "name": "getGoods", "outputs": [{ "name": "", "type": "address[]" }, { "name": "", "type": "uint256[]" }, { "name": "", "type": "uint256[]" }], "payable": false, "stateMutability": "view", "type": "function" }, { "constant": false, "inputs": [{ "name": "_power", "type": "uint256" }, { "name": "_price", "type": "uint256" }], "name": "setMerchandise", "outputs": [], "payable": false, "stateMutability": "nonpayable", "type": "function" }, { "constant": false, "inputs": [{ "name": "_power", "type": "uint256" }, { "name": "_price", "type": "uint256" }], "name": "sellPower", "outputs": [], "payable": false, "stateMutability": "nonpayable", "type": "function" }, { "constant": true, "inputs": [], "name": "getSelledMyPower", "outputs": [{ "name": "", "type": "uint256[]" }, { "name": "", "type": "uint256[]" }, { "name": "", "type": "uint256[]" }], "payable": false, "stateMutability": "view", "type": "function" }, { "inputs": [], "payable": true, "stateMutability": "payable", "type": "constructor" }];
    asignABI(data);
});

console.log(abi);
//배포된 컨트랙트 주소 설정
var contract = web3.eth.contract(abi);
var contractAddress = "0xc65a8bf6e581141799aa200327b5792c0f1775cb";
var contractInstance = contract.at(contractAddress);

//var result = contractInstance.getGoods();
//console.log(result.toString());
