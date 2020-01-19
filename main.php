<?php
session_start();
if(!isset($_SESSION['user_id'])) {
	echo "<meta http-equiv='refresh' content='0;url=login.php'>";
	exit;
}
$user_id = $_SESSION['user_id'];
$user_pw = $_SESSION['user_pw'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title> Power Market </title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/jumbotron.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="./js/web3.js"></script>
    <script type="text/javascript">
		var from_account = "";
		var passwd;

		function getAccountInfo(_account, _passwd) {
    		from_account = _account;
    		passwd = _passwd;
		}
	</script>
	<?php
		echo ("<script language=javascript> getAccountInfo(\"$user_id\", \"$user_pw\");</script>");
	?>

    <script type="text/javascript">
	var Web3 = require('web3');
var web3 = new Web3( new Web3.providers.HttpProvider("http://203.250.148.23:5800"));
var network_version = web3.version.network;
console.log("network_id: " + network_version);

web3.personal.unlockAccount(from_account, passwd, 0);
//var to_account = accounts[1];

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
    asignABI(data);
});

console.log(abi);
//배포된 컨트랙트 주소 설정
var contract = web3.eth.contract(abi);
var contractAddress = "0x560befc1b2482faa5e39ddde8e5ad58ca32eec5a";
var contractInstance = contract.at(contractAddress);
	</script>

</head>

<body>

    
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <h4> Power Market <span id="id_myAccount"></span> </h4>
            </div>
        </div>
        
        <div id="navbar" class="container">
            <form class="navbar-form navbar-right" method=POST action=logout.php>
                <button type="submit" class="btn btn-success">Sign out</button>
            </form>
        </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
                
                
            <table style="table-layout: fixed" class="table table-striped" id="id_table">
                <thead>
                    <tr>
                        <th><h5>내 잔액(Ether)</h5></th>
                        <th><input type="text" id="id_balance" readonly /> </th>
                        <th><button id="id_selling">판매</button></th>
                    </tr>
                    <tr>
                        <th><h5>보유한 전력(W)</h5></th>
                        <th><input type="text" id="id_myPower" readonly /> </th>
						<th/>
                        <th><h5>평균구매가(Ether)</h5></th>
                        <th><input type="text" id="id_avgPrice" style="width:130px;"  readonly /> </th>
                        </tr>
                    <tr>
                        <th id="id_th0"> 번호 </th>
                        <th id="id_th1"> 판매자 </th>
                        <th id="id_th2"> 전력량 </th>
                        <th id="id_th3"> W당 가격 </th>
                        <th id="id_th4" style="overflow:hidden; white-space:nowrap;"> 동작 </th>
                    </tr>
                </thead>
                <tbody id="id_tbody"></tbody>
                <script type="text/javascript">
                    var market_cnt = 1;
                    var selled_cnt = 1;    
                    const Ether_pw = 1000000000000000000;
                    var marketTimer = null;
                    var selledTimer = null;


                    $(document).ready(function() {
                        document.getElementById('id_myAccount').innerHTML="("+from_account+")";
                        updateView();
                    });                    

                    function getMyAsset() {
                        var myAsset = contractInstance.getMyAsset({
                            from: from_account});
                        $('#id_balance').val(web3.fromWei(web3.eth.getBalance(from_account)).toFixed(6));
                        $('#id_myPower').val(myAsset[0]);
                        $('#id_avgPrice').val((myAsset[1]/Ether_pw).toFixed(6));
                    }

                    $('#id_selling').click(function () {
                        var myAsset = contractInstance.getMyAsset({
                            from: from_account});
                        var sellingQuantity = parseInt(prompt('판매할 전력의 양을 입력하세요.', 0));
                        var sellingPrice = parseInt(prompt('판매할 전력의 가격을 입력하세요. (Ether 기준입니다.)', 0));
                        if(sellingQuantity > 0 && sellingQuantity <=myAsset[0] && sellingPrice > 0)
                        {
                            if(confirm("상품을 등록하시겠습니까??")){
                                contractInstance.sellPower.sendTransaction(sellingQuantity, sellingPrice * Ether_pw,{
                                    from: from_account,
                                    value: web3.toWei(0, "ether"),
                                    gas: 3000000
                                }, (error, result) => {
                                    if(error == null)   alert("총 "+sellingQuantity +"(W) 의 전력이 등록되었습니다.");
                                    else    alert(error);
                                    console.log("err: "+error);
                                    console.log("result: "+result);
                                });
                            }
                            else{

                            }
                        }
                        else if(sellingQuantity <= 0)
                        {
                            alert("0 보다 큰 수를 입력해야 합니다.");
                        }
                        else if(sellingQuantity > myAsset[0])
                        {
                            alert("보유한 전력보다 많이 판매할 수 없습니다.");
                        }
                        else if(sellingPrice <= 0)
                        {
                            alert("판매 가격은 0 Ether보다 커야 합니다.");
                        }
						else if(isNaN(sellingQuantity) || isNaN(sellingPrice))
						{
							alert("숫자만 이력 가능합니다.");
						}
                    });


                    function showGoods() {
                        document.getElementById('id_th1').innerHTML="판매자"
                        document.getElementById('id_th2').innerHTML="전력량(W)"
                        document.getElementById('id_th3').innerHTML="W당 가격(Ether)"
                        $( '#id_table > tbody').empty();

                        var result = contractInstance.getGoods();
                        for(market_cnt = 1; market_cnt <= result[0].length; market_cnt++)
                        {
                            var price = result[2][market_cnt - 1]/Ether_pw;
                        $('#id_table > tbody:last').append('<tr><td>'+market_cnt+'</td>\
                            <td style="width:30%;  text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">'+result[0][market_cnt - 1]+'</td>\
                            <td>'+result[1][market_cnt - 1]+'</td>\
                            <td>'+price+'</td>\
                            <td style="overflow:hidden; white-space:nowrap;"><button id="id_buying" no=\"'+market_cnt+'\" price=\"'+price+'\" max=\"'+result[1][market_cnt - 1]+'\"  style=\"font:12px/15px sans-serif;\">구입</button></td></tr>');
                        }
                    }                        
                    
                    function showMySelled() {
                        document.getElementById('id_th1').innerHTML="전력량(W)"
                        document.getElementById('id_th2').innerHTML="W당 가격(Ether)"
                        document.getElementById('id_th3').innerHTML="판매시각"

                        $( '#id_table > tbody').empty();
                        
                        var result = contractInstance.getSelledMyPower({
                            from: from_account});
                        for(selled_cnt = 1; selled_cnt <= result[0].length; selled_cnt++)
                        {
                            var price = result[1][selled_cnt - 1]/Ether_pw;
							var selled_time = (new Date(Number(result[3][selled_cnt-1]) * 1000)).toString();
                        $('#id_table > tbody:last').append('<tr><td>'+selled_cnt+'</td>\
                            <td>'+result[0][selled_cnt-1]+'</td>\
                            <td>'+price+'</td>\
                            <td>'+selled_time.substring(0, selled_time.indexOf("GMT")) +'</td>\
                            <td style="overflow:hidden; white-space:nowrap;"><button id="id_withdraw" no=\"'+selled_cnt+'\" style=\"font:12px sans-serif;\">철회</button></td></td></tr>');
                        }
                    }
                    

                    $(document).on("click","#id_buying",function(event){
                        var buyingQuantity = parseInt(prompt('구매할 전력의 양을 입력하세요.', 0));

                        var no = parseInt(event.target.getAttribute('no') - 1);
                        var price = parseInt(event.target.getAttribute('price'));
                        var max = parseInt(event.target.getAttribute('max'));
                        var remain_balance = parseInt(web3.fromWei(web3.eth.getBalance(from_account)));

                        if(buyingQuantity > 0 && buyingQuantity <= max && (buyingQuantity * price) < remain_balance)
                        {
                            console.log(no+", "+buyingQuantity)
                            contractInstance.buyPower.sendTransaction(no, buyingQuantity,{
                                from: from_account,
                                value: web3.toWei(buyingQuantity * price, "ether"),
                                gas: 3000000
                            }, (error, result) => {
                                if(error == null)   alert("총 "+ buyingQuantity +"(W) 의 전력이 구매되었습니다.");
                                else    alert(error);
                                console.log("err: "+error);
                                console.log("result: "+result);
                            });
                            
                        }
                        else if(buyingQuantity <= 0)
                        {
                            alert("0 보다 큰 수를 입력해야 합니다.");
                        }
                        else if(buyingQuantity > max)
                        {
                            alert("최대로 구매할 수 있는 수량을 초과했습니다.");
                        }
                        else if((buyingQuantity * price) >= remain_balance)
                        {
                            alert("보유한 잔고를 초과하여 구매할 수 없습니다.");
                        }
						else if(isNaN(buyingQuantity))
						{
							alert("숫자만 이력 가능합니다.");
						}
                    });
                    $(document).on("click","#id_withdraw",function(event){
                        var no = event.target.getAttribute('no') - 1;

                        if(confirm("등록된 상품을 철회하시겠습니까?")){ 
                            contractInstance.withdrawMyPower.sendTransaction(no,{
                                from: from_account,
                                value: web3.toWei(0, "ether"),
                                gas: 3000000
                            }, (error, result) => {
                                if(error == null)   alert("철회되었습니다.");
                                else    alert(error);
                                console.log("err: "+error);
                                console.log("result: "+result);
                            });
                        }
                        else{

                        }
                    });

                    function updateView() {
                        getMyAsset();
                        setInterval(getMyAsset, 2000);

                        showGoods();
                        marketTimer = setInterval(showGoods, 2000);
                    }
                </script>
            </table>
        </div>
    </div>


    <div class="container">
        <button id="id_market" class="btn btn-success">전력시장</button>
        <button id="id_selled" class="btn btn-success">판매한 전력</button>
    </div>
    <script type="text/javascript">
        $('#id_market').click( function() {
            if(selledTimer != null && marketTimer == null) {
                clearInterval(selledTimer);
				selledTimer = null;
                showGoods();
                marketTimer = setInterval(showGoods, 1000);
                console.log("goods");
            }   
        });
        $('#id_selled').click(function () {
            if(marketTimer != null && selledTimer == null) {
                clearInterval(marketTimer);
				marketTimer = null;
                showMySelled();
                selledTimer = setInterval(showMySelled, 1000);
                console.log("sell");
            }
        });

    </script>
    <div class="container">
		<br/><br/>
        <p>Ethereum의 트랜젝션 처리속도에 따라 거래 후, 결과가 반영되기까지 수초가 걸릴 수 있습니다.</p>
	</div>
</body>
</html>
