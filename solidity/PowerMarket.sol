pragma solidity ^0.5.4;

contract PowerMarket {
    
    struct Info{
        uint256 remain_power;
        uint256 price_per_power;
        uint256 [] selled_power;
        uint256 [] selled_price;
        uint256 [] selled_idx;
        uint [] selled_time;
    }
    
    struct Merchandise{
        address payable user;
        uint256 power;
        uint256 price;
        uint256 sid;
    }
    
    mapping (address=>Info) myAsset;
    Merchandise [] merchandise;


    constructor() public payable
    {
        merchandise.push(Merchandise({
            user: msg.sender,
            power: 1000,
            price: 1000000000000000000,
            sid: 0}));
    }
    
    
    function buyPower(uint256 idx, uint256 _power) payable  external {
        require(_power > 0, "You should buying more than 0.");
        require(merchandise[idx].power >= _power, "Can't not buying more than selled.");
        
        
        uint256 total_power = myAsset[msg.sender].remain_power + _power;
        uint256 total_price =
        (myAsset[msg.sender].remain_power * myAsset[msg.sender].price_per_power + _power * merchandise[idx].price) / total_power;
        
        myAsset[msg.sender].remain_power = total_power;
        myAsset[msg.sender].price_per_power = total_price;
        
        merchandise[idx].power -= _power;
        
        address payable receiver = merchandise[idx].user;
        receiver.transfer(_power * merchandise[idx].price);
        
        uint256 sid = merchandise[idx].sid;
        for(uint u=0; u<myAsset[receiver].selled_idx.length; u++)
        {
            if(myAsset[receiver].selled_idx[u] == sid)
            {
                myAsset[receiver].selled_power[u] -= _power;
                if(myAsset[receiver].selled_power[u] == 0)  removeSelled(receiver, u);
                break;
            }
        }
        if(merchandise[idx].power == 0)
            removeMerchandise(idx);
        
    }

    function getMyAsset() view external returns(uint256 _return_power, uint256 _return_price) {
        return (myAsset[msg.sender].remain_power, myAsset[msg.sender].price_per_power);
    }

    function getGoods() view external
    returns(address [] memory, uint256[] memory, uint256[] memory) {
        address[] memory users = new address[](merchandise.length);
        uint256[] memory powers = new uint256[](merchandise.length);
        uint256[] memory prices = new uint256[](merchandise.length);
        
        for (uint i = 0; i < merchandise.length; i++) {
            users[i] = merchandise[i].user;
            powers[i] = merchandise[i].power;
            prices[i] = merchandise[i].price;
        }
        
        return (users, powers, prices);
    }

    function sellPower(uint256 _power, uint256 _price) external {
        require(_power > 0, "You should selling more than 0.");
        require(myAsset[msg.sender].remain_power >= _power, "Can not selling more than remain.");
        
        uint256 _sid = merchandise[merchandise.length-1].sid+1;
        merchandise.push(Merchandise({
            user: msg.sender,
            power: _power,
            price: _price,
            sid: _sid}));
        
        uint256 [] storage selled_power = myAsset[msg.sender].selled_power;
        uint256 [] storage selled_price = myAsset[msg.sender].selled_price;
        uint256 [] storage selled_idx = myAsset[msg.sender].selled_idx;
        uint256 [] storage selled_time = myAsset[msg.sender].selled_time;
        selled_power.push(_power);
        selled_price.push(_price);
        selled_idx.push(_sid);
        selled_time.push(now);
        
        myAsset[msg.sender].remain_power -= _power;
    }
    
    function getSelledMyPower() view external
    returns(uint256 [] memory, uint256[] memory, uint256[] memory, uint [] memory) {
        return (myAsset[msg.sender].selled_power,
                myAsset[msg.sender].selled_price,
                myAsset[msg.sender].selled_idx,
                myAsset[msg.sender].selled_time);
    }
    
    function withdrawMyPower(uint256 idx) external {
        require(myAsset[msg.sender].selled_idx.length > 0, "You don't sell any power.");
        
        uint256 sid = myAsset[msg.sender].selled_idx[idx];
        
        myAsset[msg.sender].remain_power += myAsset[msg.sender].selled_power[idx];
        for(uint u=0; u<merchandise.length; u++)
        {
            if(merchandise[u].sid == sid)
            {
                removeMerchandise(u);
                break;
            }
        }
        removeSelled(msg.sender, idx);
    }
    
    function setMerchandise(uint256 _power, uint256 _price) external{
        uint256 _sid = merchandise[merchandise.length-1].sid+1;
        Merchandise memory element = Merchandise({
            user: msg.sender,
            power: _power,
            price: _price,
            sid: _sid});
            
        merchandise.push(element);
        
        myAsset[msg.sender].selled_power.push(_power);
        myAsset[msg.sender].selled_price.push(_price);
        myAsset[msg.sender].selled_idx.push(_sid);
    }
    
    function removeMerchandise(uint256 idx) internal returns (bool) {
        require(merchandise.length > 0, "Array's size is 0");
        
        merchandise[idx] = merchandise[merchandise.length - 1];
        delete merchandise[merchandise.length - 1];
        merchandise.length--;
        
        return true;
    }
    
    function removeSelled(address receiver, uint256 idx) internal returns (bool){
        uint256 [] storage selled_power = myAsset[receiver].selled_power;
        uint256 [] storage selled_price = myAsset[receiver].selled_price;
        uint256 [] storage selled_idx = myAsset[receiver].selled_idx;
        uint256 [] storage selled_time = myAsset[receiver].selled_time;
        uint256 length = selled_idx.length;
        
        require(length > 0, "Array's size is 0");
        
        selled_power[idx] = selled_power[length - 1];
        delete selled_power[length - 1];
        selled_power.length--;
        
        selled_price[idx] = selled_price[length - 1];
        delete selled_price[length - 1];
        selled_price.length--;
        
        selled_idx[idx] = selled_idx[length - 1];
        delete selled_idx[length - 1];
        selled_idx.length--;
        
        selled_time[idx] = selled_time[length - 1];
        delete selled_time[length - 1];
        selled_time.length--;
        
        return true;
    }
  
}