# power_market
This Dapp made with ethereum for trading power

### How to Run
Firstly, install goeth on your machine. It would be ethereum private network. Also, this network offer smart contract and managing accounts ether.
Create your admin accounts and genesis block.
create new account:
```#geth --datadir "./data" account new```

get account[0]:
```#geth --datadir "./data"  account list```

create genesis block:
```#geth --datadir "./data" init "./genesis.json"```

And then run with private network. In this project, use costom portNo 5800.
geth run:
```#geth --datadir "./data" --identity "MyNetwork" --networkid 1988 --nodiscover --rpc --rpcapi "db, eth,net, personal,  web3" --rpcaddr "0.0.0.0" --rpcport 5800 --rpccorsdomain "*" --mine --allow-insecure-unlock console```

If you want to send ether to other account, following next command.
send ether:
```eth.sendTransaction({from:eth.coinbase, to:eth.accounts[1], value: web3.toWei(300, "ether")})```

unlock account :
```personal.unlockAccount(eth.accounts[0])```

Your transaction will be processed after block mining.
mining :
```miner.start(1)```
```miner.stop()```
