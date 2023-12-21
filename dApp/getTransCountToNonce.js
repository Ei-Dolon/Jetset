
//ethers.js provider access to blockchain to find number of transactions for this wallet - this value is used as the nonce

async getTransactionCount(addressOrName: string | Promise<string>, blockTag?: BlockTag | Promise<BlockTag>): Promise<number> {
        await this.getNetwork();
        const params = await resolveProperties({
            address: this._getAddress(addressOrName),
            blockTag: this._getBlockTag(blockTag)
        });

        const result = await this.perform("getTransactionCount", params);
        try {
            return BigNumber.from(result).toNumber();
        } catch (error) {
            return logger.throwError("bad result from backend", Logger.errors.SERVER_ERROR, {
                method: "getTransactionCount",
                params, result, error
            });
        }
    }
