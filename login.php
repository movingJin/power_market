<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./application/views/css/login.css">
    <title>PowerMarket</title>

    <script type="text/javascript" src="./application/views/js/web3.js"></script>
    <script type="text/javascript">
        var Web3 = require('web3');
        var web3 = new Web3( new Web3.providers.HttpProvider("http://203.250.148.23:5800"));

        var accounts = web3.eth.accounts;  //account 정보 가져오기

        function login_proccess()
        {
            var account = $('#id_accounts_list').val();
            var passwd = $('#id_passwd').val();
            //var ret = web3.personal.unlockAccount(accounts, passwd, 0);
            //console.log(ret);
            alert(passwd);
        }

    </script>
  </head>

  <body cellpadding="0" cellspacing="0" marginleft="0" margintop="0" width="100%" height="100%" align="center">
	<h2 align ="center"> <strong>Power Market </strong> </h2>
	<br/>
	<br/>
	<div class="card align-middle" style="width:30rem; border-radius:20px;">
		<div class="card-title" style="margin-top:30px;">
			<h2 class="card-title text-center" style="color:#113366;">Log In</h2>
		</div>
		<div class="card-body">
      <form action="./application/views/login_ok.php" id="id_login" class="form-signin" method="POST" >
        <h5 class="form-signin-heading">Select your account</h5>

        <label for="inputEmail" class="sr-only">Your ID</label>
        <select id="id_accounts_list"  name="user_id" class="form-control" required autofocus>
            <option value="">None</option>
        </select>
        <script type="text/javascript">

            for (var u = 1; u < accounts.length ; u++) {
            	var elOptNew = document.createElement('option');
                elOptNew.text = accounts[u];
                elOptNew.value = accounts[u];

                var elSel = document.getElementById('id_accounts_list');
                try {
                    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
                } catch (ex) {
                    elSel.add(elOptNew); // IE only
                }
            }
        </script>
        <br/>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="id_passwd" name="user_pw" class="form-control" placeholder="Password"  style="width:27rem;"  required><br>
        <button id="btn-Yes" class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
      </form>
		</div>
	</div>

	<div class="modal">
	</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
  </body>
</html>
