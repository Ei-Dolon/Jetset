// LuxuryProperty.sol
// This contract manages the NFTs representing luxury properties and handles the rental process.

pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";

contract LuxuryProperty is ERC721 {
    uint256 public nextTokenId;
    address public admin;

    constructor() ERC721("LUXury Property NFT - Rental", "LUX") {
        admin = msg.sender;
    }

    function mint() external {
        require(msg.sender == admin, "Only admin can mint");
        _safeMint(msg.sender, nextTokenId);
        nextTokenId++;
    }
}

