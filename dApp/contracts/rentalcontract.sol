// RentalNFT.sol
// This contract handles the rental process using NFTs.

pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC721/IERC721.sol";

contract RentalNFT {
    address public admin;
    mapping(uint256 => uint256) public tokenIdToRentPrice;

    constructor() {
        admin = msg.sender;
    }

    function setRentPrice(uint256 tokenId, uint256 price) external {
        require(msg.sender == admin, "Only admin can set rent price");
        tokenIdToRentPrice[tokenId] = price;
    }

    function rentProperty(address nftContract, uint256 tokenId, uint64 expires) public payable nonReentrant {
        uint256 price = tokenIdToRentPrice[tokenId];
        require(price > 0, "Property not available for rent");
        require(listing.user == address(0) || block.timestamp > listing.expires, "NFT already rented");
        require(expires <= listing.endDateUNIX, "Rental period exceeds max date rentable");
        require(msg.value == price, "Incorrect payment amount");
		require(msg.value >= rentalFee, "Not enough BNB to cover rental period");

        // Handle the rental logic (e.g., update status, transfer ownership)
		Listing storage listing = _listingMap[nftContract][tokenId];
		// Transfer rental fee
		uint256 numDays = (expires - block.timestamp)/60/60/24 + 1;
		uint256 rentalFee = listing.pricePerDay * numDays;
		payable(listing.owner).transfer(rentalFee);

		// Update listing
		IERC4907(nftContract).setUser(tokenId, msg.sender, expires);
		listing.user = msg.sender;
		listing.expires = expires;

        // Transfer ownership of the NFT to the renter
        IERC721 property = IERC721(ADDRESS_OF_LUXURY_PROPERTY_CONTRACT);
        property.transferFrom(address(this), msg.sender, tokenId);

		emit NFTRented(
			IERC721(nftContract).ownerOf(tokenId),
			msg.sender,
			nftContract,
			tokenId,
			listing.startDateUNIX,
			listing.endDateUNIX,
			expires,
			rentalFee
		);

    }

    // function to rent NFT
    function rentNFT(
        address nftContract,
        uint256 tokenId,
        uint64 expires
    ) public payable nonReentrant {
        Listing storage listing = _listingMap[nftContract][tokenId];
        require(listing.user == address(0) || block.timestamp > listing.expires, "NFT already rented");
        require(expires <= listing.endDateUNIX, "Rental period exceeds max date rentable");
        // Transfer rental fee
        uint256 numDays = (expires - block.timestamp)/60/60/24 + 1;
        uint256 rentalFee = listing.pricePerDay * numDays;
        require(msg.value >= rentalFee, "Not enough BNB to cover rental period");
        payable(listing.owner).transfer(rentalFee);
        // Update listing
        IERC4907(nftContract).setUser(tokenId, msg.sender, expires);
        listing.user = msg.sender;
        listing.expires = expires;

        emit NFTRented(
            IERC721(nftContract).ownerOf(tokenId),
            msg.sender,
            nftContract,
            tokenId,
            listing.startDateUNIX,
            listing.endDateUNIX,
            expires,
            rentalFee
        );
    }
}

