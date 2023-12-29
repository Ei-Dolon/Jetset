<?php

if( ! defined( 'ABSPATH' ) ) die;

use losnappas\Ethpress\Signature;

new WPSC_Endpoints();

function hex2str($hex) {
    $str = '';
    for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
    return $str;
}

/**
 * Handle etherscan api queries for block explorer view
 */

class WPSC_Endpoints {

    // prefix name for the transient variable
    public const transientPrefix = 'wpsc_';

    const paginationOffset = 25;

    // define endpoints
    function __construct() {

        // get token supply
        add_action( 'rest_api_init', function () {

            register_rest_route( 'wpsc/v1', '/ping/', [
                'methods' => 'GET',
                'callback' => [ $this, 'ping' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_tx_contract_account/(?P<decimals>\d+)/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)/(?P<address>[a-zA-Z0-9-]+)/(?P<page>\d+)/(?P<internal>\d+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getTxAccountInContract' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_tx_contract/(?P<decimals>\d+)/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)/(?P<page>\d+)/(?P<internal>\d+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getTxFromContract' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/total_supply/(?P<decimals>\d+)/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getTotalSupply' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/balance/(?P<decimals>\d+)/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)/(?P<address>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getBalance' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_tx/(?P<decimals>\d+)/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)/(?P<txid>[a-zA-Z0-9-]+)/(?P<ignore_contract>[0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getTxId' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/remove_cache/', [
                'methods' => 'GET',
                'callback' => [ $this, 'removeCache' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_code/(?P<network>\d+)/(?P<contract>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getCode' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/format_float_custom/(?P<decimals>\d+)/(?P<float>[0-9\,\.e\+]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'formatFloatCustom' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/format_float/(?P<float>[0-9\,\.e\+]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'formatFloat' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/format_float2/(?P<float>[0-9\,\.e\+]+)/(?P<dec>\d+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'formatFloat2' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/nft/(?P<id>\d+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'nft' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/nft1155/(?P<collection_id>\d+)/(?P<id>[a-zA-Z0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'nft1155' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/insert-nft/(?P<id>\d+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftInsert' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/save-media/', [
                'methods' => 'POST',
                'callback' => [ $this, 'saveMedia' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/retrieve-media-log/', [
                'methods' => 'POST',
                'callback' => [ $this, 'retrieveMedia' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/update-nft-1155/', [
                'methods' => 'POST',
                'callback' => [ $this, 'updateNFT1155' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/nft-erc1155-owners/', [
                'methods' => 'POST',
                'callback' => [ $this, 'NFT1155Owners' ],
                'permission_callback' => '__return_true'
            ]);
            
            register_rest_route( 'wpsc/v1', '/nft-erc1155-sync/', [
                'methods' => 'POST',
                'callback' => [ $this, 'NFT1155Sync' ],
                'permission_callback' => '__return_true'
            ]);
            
            register_rest_route( 'wpsc/v1', '/nft-erc1155-my/', [
                'methods' => 'POST',
                'callback' => [ $this, 'NFT1155MyItems' ],
                'permission_callback' => '__return_true'
            ]);
            
            register_rest_route( 'wpsc/v1', '/nft-log/(?P<id>\d+)/(?P<txid>[a-zA-Z0-9-]+)/(?P<to>[a-zA-Z0-9-]+)/(?P<value>[0-9\.]+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftLog' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/save-deploy-nft/(?P<id>\d+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftSaveDeploy' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get-nft-by-id/(?P<network>\d+)/(?P<collid>\d+)/(?P<ids>[0-9-p]+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftGetByIDs' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/is-minted/(?P<id>\d+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'isMinted' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/nft-ipfs-store', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftIPFSStore' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/nft-exists-ipfs/(?P<id>\d+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'nftExistsIPFS' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get-affp-address', [
                'methods' => 'GET',
                'callback' => [ $this, 'getAffPAddress' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/wizard-add-contract', [
                'methods' => 'POST',
                'callback' => [ $this, 'wizardAddContract' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/reserve-post-id', [
                'methods' => 'POST',
                'callback' => [ $this, 'reservePostID' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get-smart-contracts/(?P<type>\d+)/(?P<account>[a-zA-Z0-9-]+)/(?P<n>[0-9-]+)', [
                'methods' => 'GET',
                'callback' => [ $this, 'getSmartContracts' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/update_post_name', [
                'methods' => 'POST',
                'callback' => [ $this, 'updatePostName' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/update_post_meta', [
                'methods' => 'POST',
                'callback' => [ $this, 'updatePostMeta' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/update_post_meta_onchain', [
                'methods' => 'POST',
                'callback' => [ $this, 'updatePostMetaOnChain' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/check_registration/(?P<account>[a-zA-Z0-9-]+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'checkRegistration' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_sign_message/(?P<account>[a-zA-Z0-9-]+)/(?P<email>[\S]+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'getSignMessage' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_sign_message2/(?P<account>[a-zA-Z0-9-]+)', [
                'methods' => 'POST',
                'callback' => [ $this, 'getSignMessage2' ],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route( 'wpsc/v1', '/get_logout_url', [
                'methods' => 'POST',
                'callback' => [ $this, 'getLogoutUrl' ],
                'permission_callback' => '__return_true'
            ]);

        });

        add_action( 'wp_ajax_wpsc_register_login', function () {
            $email = false;
            if (WPSC_helpers::valArrElement($_POST, "email")) {
                $email = $_POST['email'];
            }
            echo self::registerUser($_POST['coinbase'], $_POST['signature'], $email);
            wp_die();
        });
            
        add_action( 'wp_ajax_nopriv_wpsc_register_login', function () {
            $email = false;
            if (WPSC_helpers::valArrElement($_POST, "email")) {
                $email = $_POST['email'];
            }
            echo self::registerUser($_POST['coinbase'], $_POST['signature'], $email);
            wp_die();
        });

        add_action( 'wp_ajax_wpsc_login', function () {
            echo self::loginUser($_POST['coinbase'], $_POST['signature']);
            wp_die();
        });
            
        add_action( 'wp_ajax_nopriv_wpsc_login', function () {
            echo self::loginUser($_POST['coinbase'], $_POST['signature']);
            wp_die();
        });

        // if there is a change in a NFT clear transient endpoint response
        add_action('save_post', function ($post_id) {
            global $post; 
            if (!empty($post) and $post->post_type != 'nft'){
                return;
            }
            $transient_name = "wpsc_nft_" . $post_id;
            delete_transient($transient_name);
        });

    }

    // endpoint callbacks

    public static function ping($params) {
        return new WP_REST_Response(true);
    }

    public static function getTxAccountInContract($params) {
        
        check_ajax_referer('wp_rest', '_wpnonce');
        
        return new WP_REST_Response(
            self::getTx($params['decimals'], $params['network'], $params['contract'], $params['address'], $params['page'], $params['internal'])
        );
        
    }

    public static function getTxFromContract($params) {

        check_ajax_referer('wp_rest', '_wpnonce');
        
        return new WP_REST_Response(
            self::getTx($params['decimals'], $params['network'], $params['contract'], null, $params['page'], $params['internal'])
        );

    }

    static private function processInput($input, $decimals) {

        if ($input) {

            $hashFunction = $type = $from = $to = $value = null;

            $hashFunction = substr($input, 0, 10);
            switch ($hashFunction) {
                case "0xa9059cbb":
                    $type="transfer";
                    $to = "0x" . substr($input, 34, 40);
                    $value = hexdec(substr($input, 75, 63));
                    break;
                case "0x23b872dd":
                    $type="transferFrom";
                    $from = "0x" . substr($input, 34, 40);
                    $to = "0x" . substr($input, 98, 40);
                    $value = hexdec(substr($input, 138));
                    break;
                case "0x095ea7b3":
                    $type="approve";
                    $to = "0x" . substr($input, 34, 40);
                    $value = hexdec(substr($input, 74));
                    break;
                case "0x40c10f19":
                    $type="mint";
                    $to = "0x" . substr($input, 34, 40);
                    $value = hexdec(substr($input, 74));
                    break;
                case "0x42966c68":
                    $type="burn";
                    $value = hexdec(substr($input, 10));
                    break;
                case "0x79cc6790":
                    $type="burnFrom";
                    $from = "0x" . substr($input, 34, 40);
                    $value = hexdec(substr($input, 74));
                    break;
                case "0x8456cb59":
                    $type="pause";
                    break;
                case "0x3f4ba83a":
                    $type="resume";
                    break;
                case "0x983b2d56":
                    $type="addMinter";
                    break;
                case "0x82dc1ec4":
                    $type="addPauser";
                    break;
                case "0x98650275":
                    $type="renounceMinter";
                    break;
                case "0x6ef8d66d":
                    $type="renouncePauser";
                    break;
                case "0x80a70e5f": // raspberry
                case "0xe6c9f1f6": // raspberry wpst
                case "0x45c2e176": // bluemoon
                case "0x3e517ed1": // bluemoon wpst
                case "0x5b060530": // vanilla and pistachio
                case "0x558d4657": // chocolate
                case "0x37d325a1": // vanilla and pistachio wpst
                case "0x95d38e11": // chocolate wpst
                case "0x772d0f3c": // mango
                case "0xc19afa14": // mango wpst
                case "0x8bcf5bad": // bubblegum
                case "0x9642957f": // bubblegum wpic
                    $type="contractCreation";
                break;
                case "0xf2cc0c18":
                    $type="excludeAccount";
                    break;
                case "0xf84354f1":
                    $type="includeAccount";
                    break;
                case "0xec8ac4d8":
                    $type="icoBuyTokens";
                    break;
                case "0x":
                    $type="icoDirectTransfer";
                    break;
                default:
                    $type = $hashFunction;
                    break;
            }
            if ($value) {
                // convert wei like units to ether like
                $value = $value / 10 ** $decimals;
                $value = $value;
            }
            return [$hashFunction, $type, $from, $to, $value];
        } else {
            return [];
        }

    }

    public static function getAffPAddress() {
        $etherscan_api_key = get_option('etherscan_api_key_option');
        return [
            "affp_wallet_1" => trim(get_option("wpsc_affp_wallet_1")),
            "affp_wallet_56" => trim(get_option("wpsc_affp_wallet_56")),
            "affp_wallet_137" => trim(get_option("wpsc_affp_wallet_137")),
            "affp_wallet_43114" => trim(get_option("wpsc_affp_wallet_43114")),
            "affp_wallet_42161" => trim(get_option("wpsc_affp_wallet_42161")),
            "affp_wallet_250" => trim(get_option("wpsc_affp_wallet_250"))
        ];
    }

    private static function getApiKey($network) {

        $etherscan_api_key = get_option('etherscan_api_key_option');

        switch($network) {
        // Ethereum
        case 1:
        case 3:
        case 5:
        case 4:
                case 11155111:
        case 42:
                return trim($etherscan_api_key["api_key"]);
        // BSCScan
        case 56:
        case 97:
            return trim($etherscan_api_key["bscscan_api_key"]);        
        // Polygonscan
        case 80001:
        case 137:
            return trim($etherscan_api_key["polygonscan_api_key"]);
        // AVAX
        case 43113:
        case 43114:
            return trim($etherscan_api_key["avax_api_key"]);
        case 4002:
        case 250:
            return trim($etherscan_api_key["fantom_api_key"]);
        }

    }

    // filter tx based on contract / account address
    private static function getTx($decimals, $network, $contract, $address, $page, $internal) {

        if (!$domain = self::getNetworkDomain($network)) {
            return [];
        }

        $api_key = self::getApiKey($network);

        if (!$page) $page = 1;

        // if we have a transient stored, return it
        if ($txs = self::getTransientResponse($network, $contract, $address, $page, $internal)) {

            return $txs;

        // otherwise hit the Etherscan API
        } else {

            // filter by contract and account
            if ($address) {
                $etherscan_url = $domain . '?module=account&action=tokentx&address=' . $address . 
                    '&sort=desc&page=' . $page . '&offset=' . self::paginationOffset . '&apikey=' . trim($api_key) . 
                        '&contractAddress=' . $contract;
            // filter by contract
            } else {

                // list internal txs
                if ($internal) {
                    $txlist_endpoint = "txlistinternal";
                    $offset = 10; // internal txs are slower, so lets show less
                // list regular txs
                } else {
                    $txlist_endpoint = "txlist";
                    $offset = self::paginationOffset;
                }
                $etherscan_url = $domain . '?module=account&action=' . $txlist_endpoint . '&address=' . $contract . 
                '&page=' . $page . '&offset=' . $offset . '&sort=desc&apikey=' . trim($api_key); // we try to use the user api key
            }

            //return $etherscan_url;

            // hit the api
            $response = wp_remote_get( $etherscan_url );

            // successful?
            if ( is_array( $response ) and $response["response"]["code"] == "200") {

                $txlist = json_decode($response["body"], true);

                if (is_array($txlist) and array_key_exists('result', $txlist)) {

                    $txs=[];
                    $localeconv = localeconv();

                    // filter transactions with the contract
                    foreach ($txlist['result'] as $res) {

                        $txs_column = array_column($txs, 'txid');
                        if (array_search($res["hash"], $txs_column)!==false) continue;

                        // parse etherscan input response
                        $hashFunction = '';
                        $to = '';
                        $value = 0;

                        @list($hashFunction, $type, $transferFrom, $to, $value) = self::processInput($res["input"], $decimals);

                        // find the date format in settings or set default
                        $settings = WPSCSettingsPage::get();
                        if (!$date_format = $settings["date_format"]) {
                            $date_format = 'Y-m-d';
                        }
                        
                        $from = $res["from"];

                        if ($type=="transferFrom" or $type=="burnFrom") {
                            $from = $transferFrom;
                        }

                        // if this is an internal request then  find internal details
                        if ($internal) {
                            $txs[] = current(self::getTxId([
                                "decimals"=>$decimals, 
                                "network"=>$network, 
                                "contract"=>$contract, 
                                "txid"=>$res["hash"],
                                "ignore_contract"=>true // in this case we dont want to filter by contract
                            ]));
                        // otherwise return regular fields
                        } else {

                            // use default to address if not in input
                            if (!$to) {
                                $to = $res["to"];
                            }
                            
                            // ad it to the tx list
                            $txs[] = [
                                'blockNumber' => $res["blockNumber"],
                                'timeStamp' => ($res["timeStamp"])?date($date_format, $res["timeStamp"]):'',
                                'txid' => $res["hash"],
                                'txid_short' => WPSC_helpers::shortify($res["hash"]),
                                'from' => $from,
                                'from_short' => WPSC_helpers::shortify($from),
                                'transfer_from' => $transferFrom,
                                'transfer_from_short' => WPSC_helpers::shortify($transferFrom),
                                'hashFunction' => $hashFunction,
                                'type' => $type,
                                'to' => $to,
                                'to_short' => WPSC_helpers::shortify($to),
                                'value' => $value?WPSC_helpers::formatNumber($value):
                                    WPSC_helpers::formatNumber($res["value"] / 10 ** $decimals),
                                'isError' => WPSC_helpers::valArrElement($res, 'isError')?$res["isError"]:false,
                                'domain' => self::getNetworkDomain($network, '', 'url2')
                            ];

                        }

                    }

                    // save the wp transient api response
                    if (!empty($txs)) {
                        self::saveTransientResponse($network, $contract, $address, $page, $internal, $txs);
                    }

                    return $txs;
                }

            }

        }

        return [];

    }

    // filter tx based on contract / account address
    public static function getTxId($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        $decimals = $params['decimals'];
        $network = $params['network'];
        $contract = $params['contract'];
        $txid = $params['txid'];
        $ignore_contract = $params['ignore_contract'];

        if (!$domain = self::getNetworkDomain($network)) {
            return [];
        }

        $api_key = self::getApiKey($network);

        // if we have a transient stored, return it
        if ($txs = self::getTransientResponse($network, $txid, null, null, null)) {

            return $txs;

        // otherwise hit the Etherscan API
        } else {

            $etherscan_url = $domain . '?module=proxy&action=eth_getTransactionByHash&txhash=' . 
                $txid . '&apikey=' . $api_key; // we try to use the user api key

            // hit the api
            $response = wp_remote_get( $etherscan_url );

            // successful?
            if ( is_array( $response ) and $response["response"]["code"] == 200) {

                $res = json_decode($response["body"], true);

                if (is_array($res) and array_key_exists('result', $res)) {
                    $res = $res["result"];
                }

                if (is_array($res) and array_key_exists('hash', $res)) {

                    // filtering contract interaction comparing unchecksummed addresses
                    if ($ignore_contract or strtolower($res["to"]) == strtolower($contract)) {

                        // parse etherscan input response
                        $hashFunction = '';
                        $to = '';
                        $type = false;
                        $value = 0;

                        @list($hashFunction, $type, $transferFrom, $to, $value) = self::processInput($res["input"], $decimals);

                        // now try to get the block info to get the timestamp
                        $time_stamp = null;
                        if ($res["blockNumber"]) {

                            $etherscan_url = $domain . '?module=block&action=getblockreward&blockno=' . 
                                hexdec( substr($res["blockNumber"], 2) ) . '&apikey=' . $api_key; // we try to use the user api key

                            // hit the api
                            $response = wp_remote_get( $etherscan_url );

                            // successful?
                            if ( is_array( $response ) and $response["response"]["code"] == 200) {

                                $body = json_decode($response["body"], true);
                                $time_stamp = $body["result"]["timeStamp"];

                            }

                        }

                        $txs[] = [
                            'blockNumber' => $res["blockNumber"],
                            'timeStamp' => ($time_stamp)?date('Y-m-d', $time_stamp):'',
                            'txid' => $res["hash"],
                            'txid_short' => WPSC_helpers::shortify($res["hash"]),
                            'from' => $res["from"],
                            'from_short' => WPSC_helpers::shortify($res["from"]),
                            'transfer_from' => $transferFrom,
                            'transfer_from_short' => WPSC_helpers::shortify($transferFrom),
                            'hashFunction' => $hashFunction,
                            'type' => $type,
                            'to' => $to,
                            'to_short' => WPSC_helpers::shortify($to),
                            'value' => $value?WPSC_helpers::formatNumber($value):
                                WPSC_helpers::formatNumber(hexdec($res["value"]) / 10 ** $decimals),
                            'isError' => WPSC_helpers::valArrElement($res, 'isError')?$res["isError"]:false,
                            'domain' => self::getNetworkDomain($network, '', 'url2'),
//                            'response' => $res,
                        ];
                    }

                    // save the wp transient api response
                    if (!empty($txs)) {
                        self::saveTransientResponse($network, $txid, null, null, null, $txs);
                    }

                    return $txs;
                }

            }

        }

        return [];

    }

    // filter tx based on contract / account address
    public static function getCode($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        $network = $params['network'];
        $contract = $params['contract'];

        $api_key = self::getApiKey($network);

        if (!$domain = self::getNetworkDomain($network)) {
            return [];
        }

        // if we have a transient stored, return it
        if ($txs = self::getTransientResponse($network, $contract, 'source_code', null, null)) {

            return $txs;

        // otherwise hit the Etherscan API
        } else {

            $etherscan_url = $domain . '?module=contract&action=getsourcecode&address=' . $contract . '&apikey=' . $api_key; // we try to use the user api key

            // hit the api
            $response = wp_remote_get( $etherscan_url );

            // successful?
            if ( is_array( $response ) and $response["response"]["code"] == 200) {

                $res = json_decode($response["body"], true);

                if (is_array($res) and array_key_exists('result', $res)) {
                    self::saveTransientResponse($network, $contract, 'source_code', null, null, $res["result"]);
                    return $res["result"];
                }

            }

        }

        return [];

    }

    // get total token supply
    public static function getTotalSupply($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        if (!$domain = self::getNetworkDomain($params['network'])) {
            return [];
        }

        $decimals = $params['decimals'];

        if ($decimals === "") {
            $decimals = 18;
        }

        // if we have a transient stored, return it
        if (false and $supply = self::getTransientResponse($params['network'], $params['contract'], "total_supply", null, null)) {

            return $supply;

        // otherwise hit the Etherscan API
        } else {

            $api_key = self::getApiKey($params['network']);

            $etherscan_url = $domain . '?module=stats&action=tokensupply&contractaddress=' . $params['contract'] . '&apikey=' . $api_key; // we try to use the user api key

            // return $etherscan_url;

            // hit the api
            $response = wp_remote_get( $etherscan_url );

            // successful?
            if ( is_array( $response ) and $response["response"]["code"] == 200) {

                $supply = json_decode($response["body"], true);

                if (is_array($supply) and WPSC_helpers::valArrElement($supply, 'result')) {

                    $formatted_result = WPSC_helpers::formatNumber($supply["result"] / 10 ** $decimals);

                    // save the wp transient api response
                    self::saveTransientResponse($params['network'], $params['contract'], "total_supply", null, null, $formatted_result);

                    return $formatted_result;
                    
                }

            }

        }

        return [];

    }

    // get balance of one holder
    public static function getBalance($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        if (!$domain = self::getNetworkDomain($params['network'])) {
            return [];
        }

        $decimals = $params['decimals'];

        // if we have a transient stored, return it
        if ($balance = self::getTransientResponse($params['network'], $params['contract'], $params['address'] . "_balance", null, null)) {

            return $balance;

        // otherwise hit the Etherscan API
        } else {

            $api_key = self::getApiKey($network);

            $etherscan_url = $domain . '?module=account&action=tokenbalance&contractaddress=' . $params['contract'] . '&address=' . $params['address'] . '&tag=latest&apikey=' . $api_key; // we try to use the user api key

            // hit the api
            $response = wp_remote_get( $etherscan_url );

            // successful?
            if ( is_array( $response ) and $response["response"]["code"] == 200) {

                $balance = json_decode($response["body"], true);

                if (is_array($balance) and array_key_exists('result', $balance)) {

                    $final_balance = WPSC_helpers::formatNumber($balance["result"] / 10 ** $decimals);

                    // save the wp transient api response
                    self::saveTransientResponse($params['network'], $params['contract'], $params['address'] . "_balance", null, null, $final_balance);

                    return $final_balance;

                }

            }

        }

        return [];

    }

    // return the message for signatures
    public static function signatureMessage($account, $email=false) {
        if ($email) {
            $message = "Login account: " . $account . " to " . home_url() . " using " . $email;
        } else {
            $message = "Login account: " . $account . " to " . home_url();
        }
        return  $message . " | " . md5($message);
    }

    // return the template to sign, or false if the input is incorrect
    public static function getSignMessage($params) {
        $account = $params["account"];
        $email = $params["email"];
        if (self::validateAddress($account) and filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new WP_REST_Response(self::signatureMessage($account, $email));
        // account or email is invalid, returns false
        } else {
            return new WP_REST_Response(false);
        }
    }

    // return the template to sign, or false if the input is incorrect
    public static function getSignMessage2($params) {
        $account = $params["account"];
        if (self::validateAddress($account)) {
            return new WP_REST_Response(self::signatureMessage($account));
        // account is invalid, returns false
        } else {
            return new WP_REST_Response(false);
        }
    }

    public static function getLogoutUrl() {
        return wp_logout_url(get_permalink());
    }

    /*
        return        
        if we dont need to login returns -2
        if we the account is invalid returns -1
        if we need to login but the account does not exists returns 0
        otherwise the account exists, return the signature message with the email of the account
    */
    public static function checkRegistration($params) {
        $account = $params["account"];
        // TODO: check if the plugin is not set to register users, then return -2
        if (self::validateAddress($account)) {
            $existing_user_id = username_exists($account);
            // the account exists, return the signature message with the email of the account
            if ( false !== $existing_user_id ) {
                $user_data = get_userdata($existing_user_id);
                return new WP_REST_Response(self::signatureMessage($account, $user_data -> user_email));
            // we need to login but the account does not exists returns 0
            } else {
                return new WP_REST_Response(0);
            }
        // account is invalid, returns -1
        } else {
            return new WP_REST_Response(-1);
        }
    }

    static public function sendConfirmationEmail($user_id, $email) {

        $user = get_user_by( 'ID', $user_id );
        $code = sha1( $user_id . time() . rand(1, 1000000) );
        $activation_link = add_query_arg( 
            array('wpsc_key' => $code, 'wpsc_user' => $user_id), 
            WPSC_assets::getPage("wpsc_activate_user")
        );
        update_user_meta($user_id, 'wpsc_has_to_be_activated', $code);
        // error_log("activation_link3: $activation_link email: " . $email);
        wp_mail( $email, __("Confirm you registration", "wp-smart-contracts"), __('Click on the link to activate your subscription: ', "wp-smart-contracts") . $activation_link );
    
    }

    function registerUser($user_login, $signature, $email=false) {

        if (empty($user_login) or empty($signature)) return;

        $options = get_option( 'etherscan_api_key_option' );

        // email is required for registration, if email not present return false
        if (WPSC_helpers::valArrElement($options, "wpsc_email_registration") and !empty($options["wpsc_email_registration"]) and !$email) return false;

        $message = self::signatureMessage($user_login, $email);

        @$verified = Signature::verify( $message, $signature, $user_login );
    
        if ( $verified ) {
    
            $user_login = trim( $user_login );
            $existing_user_id = username_exists( $user_login );
            if ( false !== $existing_user_id ) {
                // user already exists
                return false;
            } else {

                $userdata['user_login'] = $user_login;
                $userdata['user_pass'] = wp_generate_password();
                if ($email) {
                    $userdata['user_email'] = $email;
                    $userdata['role'] = 'pending';
                } else {
                    $wpsc_role = WPSC_helpers::getRole();
                    if (!$wpsc_role) $wpsc_role = "subscriber";
                    $userdata['role'] = $wpsc_role;
                }

                $user_id = wp_insert_user( $userdata ) ;

                if ($email) {

                    if ( $user_id && !is_wp_error( $user_id ) ) {
                        self::sendConfirmationEmail($user_id, $email);
                        return -2;
                    } else {
                        return false;
                    }
    
                } else {

                    $user = get_user_by('login', $user_login);
                    self::wpscLogin($user);

                    if ( is_user_logged_in() ) {
                        return $user->ID;
                    } else {
                        return false;
                    }

                }

            }
        }

        return false;
    
    }

    function loginUser($user_login, $signature) {

        if (empty($user_login) or empty($signature)) {
            return false;
        }
    
        $user_login = trim($user_login);
        $existing_user_id = username_exists( $user_login );
        if ( false === $existing_user_id ) {
            // user doesn't exists
            return false;
        }

        $user_data = get_userdata($existing_user_id);
        $message = self::signatureMessage($user_login, $user_data -> user_email);
        @$verified = Signature::verify( $message, $signature, $user_login );

        if ( $verified ) {
            $user = get_user_by('login', $user_login);
            self::wpscLogin($user);    
            if ( is_user_logged_in() ) {
                return $user->ID;
            } else {
                return  false;
            }
        }

        return false;
    
    }

    static private function wpscLogin($user) {

        $user_roles = $user->roles;

        // if not confirmed, do not login the user
        if ( sizeof($user_roles) == 0 or in_array( 'pending', $user_roles, true ) ) {

            self::sendConfirmationEmail($user->ID, $user->user_email);

        } else {

            clean_user_cache( $user->ID );
            wp_clear_auth_cookie();

            wp_set_current_user( $user->ID );
            wp_set_auth_cookie( $user->ID, false );
            update_user_caches( $user );

            do_action( 'wp_login', $user->data->user_login, $user );

        }

    }

    public static function getSmartContracts($params) {
        $account = $params["account"];
        $type = $params["type"];
        $n = $params["n"];
        if ($n==-1) {
            $start = 0;
            $len = 1;
        } else {
            $start = $n * 9;
            $len = 9;
        }
        switch ($type) {
            case '0':
                # coins
                return new WP_REST_Response(WPSC_Queries::getDashboardCoins($account, $start, $len));
                break;
            case '1':
                # stakes
                return new WP_REST_Response(WPSC_Queries::getDashboardStakes($account, $start, $len));
                break;
            case '2':
                # crowdfundings
                return new WP_REST_Response(WPSC_Queries::getDashboardCrowdfunding($account, $start, $len));
                break;
            case '3':
                # nfts
                return new WP_REST_Response(WPSC_Queries::getDashboardNfts($account, $start, $len));
                break;
            case '4':
                # funds: ICO, Airdrops, Vaults
                return new WP_REST_Response(WPSC_Queries::getDashboardFunds($account, $start, $len));
                break;
            default:
                return new WP_REST_Response(false);
                break;
        }
    }

    // filter tx based on contract / account address
    public static function removeCache() {

        check_ajax_referer('wp_rest', '_wpnonce');
        global $wpdb;

        $current_user = wp_get_current_user();
        if (user_can( $current_user, 'administrator' )) {

            $wpdb->query("DELETE FROM `$wpdb->options` WHERE option_name LIKE '%_transient_" . self::transientPrefix . "%'");
            return new WP_REST_Response(true);

        }

    }

    public static function formatFloatCustom($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        $float = $params['float'];
        $decimals = $params['decimals'];

        if ($float) {
            return WPSC_helpers::formatNumber($float / 10 ** $decimals);            
        } else {
            return 0;
        }
        
    }

    // format float
    public static function formatFloat($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        $float = $params['float'];

        if ($float) {
            return WPSC_helpers::formatNumber($float / 1000000000000000000);            
        } else {
            return 0;
        }
        
    }

    // format float
    public static function formatFloat2($params) {

        check_ajax_referer('wp_rest', '_wpnonce');

        $float = $params['float'];

        if ($float) {
            return WPSC_helpers::formatNumber2($float / 1000000000000000000, $params['dec']);
        } else {
            return 0;
        }
        
    }

    // NFT Token URI
    public static function nft($params) {

        $id = $params['id'];

        if (!$id) return;

        $wpsc_nft_id = get_post_meta($id, "wpsc_nft_id", true);

        $transient_name = "wpsc_nft_" . $id;

        if ($result = get_transient($transient_name)) {
            return $result;
        }

        $res = self::getNFTData($id, $wpsc_nft_id);
        
        set_transient($transient_name, $res, 24*60*60);

        return $res;
        
    }

    /**
     * 
     * NFT 1155 Token URI
     * 
     * /wp-json/wpsc/v1/nft1155/{collection-id}/{id}
     * where {collection-id} is a unique ID predefined before deploying the collection and {id} the NFT ID, 
     * 
     */
    public static function nft1155($params) {

        $wpsc_nft_id = $params['id'];
        $collection_id = $params['collection_id'];

        if (!$wpsc_nft_id or !$collection_id) return;

        // Get NFT WP ID by getting post_id filtering wpsc_item_collection = collection_id and wpsc_nft_id = wpsc_nft_id
        $id = WPSC_Queries::getPostIDByNFTID($collection_id, $wpsc_nft_id);

        if (!is_array($id) or !array_key_exists(0, $id) or !array_key_exists("post_id", $id[0]) or !$id[0]["post_id"]) {
            // is there a redirection to other post id? (in the case of a loaded smart contract)
            $collection_id = WPSC_Queries::getCollectionIDByReference($collection_id);
            if ($collection_id) {
                $id = WPSC_Queries::getPostIDByNFTID($collection_id, $wpsc_nft_id);
                if (!is_array($id) or !array_key_exists(0, $id) or !array_key_exists("post_id", $id[0]) or !$id[0]["post_id"]) {
                    return __("Post ID not found", "wp-smart-contracts");
                }
            } else {
                return __("Post ID not found", "wp-smart-contracts");
            }
        }

        $transient_name = "wpsc_nft1155_" . $id[0]["post_id"];

        if ($result = get_transient($transient_name)) {
            return $result;
        }

        $res = self::getNFTData($id[0]["post_id"], $wpsc_nft_id);
        
        set_transient($transient_name, $res);

        return $res;
        
    }

    static public function getNFTData($id, $wpsc_nft_id=0) {

        $nft = get_post($id);

        $media_type = get_post_meta($id, "wpsc_media_type", true);
    
        if ( $wpsc_network = get_post_meta($id, 'wpsc_network', true) ) {
            list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);
        }
        $collection_id = get_post_meta($id, 'wpsc_item_collection', true);
        if ($collection_id) {
            $contract = get_post_meta($collection_id, 'wpsc_contract_address', true);
            if (isset($etherscan)) {
                $link_etherscan = $etherscan."token/".$contract."?a=".$wpsc_nft_id;
            } else {
                $link_etherscan = "";
            }
        }
    
        $res = [
            "id"=>$id,
            "name" => $nft->post_title,
            "description" => strip_tags($nft->post_content),
            "attributes" => [
                ["trait_type" => "creator", "value" => "👤".get_the_author_meta("display_name", $nft->post_author)],
                ["trait_type" => "last_modified_gmt", "value" => $nft->post_modified_gmt],
            ],
            "media-type" => $media_type,
            "external_url" => get_permalink($id),
            "author_id" => $nft->post_author,
            "author_external_url" => add_query_arg(["a" => $nft->post_author, "id"=>$collection_id], WPSC_assets::getPage("wpsc_is_nft_author")),
            "author_avatar" => get_avatar_url($nft->post_author),
            "author_account" => get_post_meta($id, 'wpsc_creator', true),
            "nft_id" => $wpsc_nft_id,
            "network" => isset($network_val)?$network_val:"",
            "network_url" => $link_etherscan,
        ];
    
        $media_urls = null;
        if ($media = get_post_meta($id, "wpsc_nft_media_json", true)) {
    
            $tmp = json_decode($media);
            if (is_array($tmp)) {
                foreach ($tmp as $url) {
                    if ($url->id) {
                        if ($ipfs = get_post_meta($url->id, 'wpsc_nft_ipfs', true)) {
                            $media_urls[]=$ipfs;
                        } elseif ($att_url = wp_get_attachment_url($url->id)) {
                            $media_urls[]=$att_url;
                        }
                    }
                }
            }
        }
    
        $cats =  wp_get_post_terms($id, "nft-taxonomy");
        if (is_array($cats)) {
            foreach ($cats as $i => $cat) {
                $ind = $i + 1;
                $res["attributes"][] = [
                    "trait_type" => "Category $ind", 
                    "value" => $cat->name,
                    "link" => add_query_arg("id", $collection_id, get_term_link($cat))
                ];
            }
        }

        $gals =  wp_get_post_terms($id, "nft-gallery");	
        if (is_array($gals)) {	
            foreach ($gals as $i => $gal) {	
                $ind = $i + 1;	
                $link = add_query_arg("id", $collection_id, get_term_link($gal));	
                $link = add_query_arg("gal_id", $gal->term_id, $link);	
                $res["attributes"][] = [	
                    "trait_type" => "Gallery $ind", 	
                    "value" => $gal->name,	
                    "link" => $link	
                ];	
            }	
        }
    
        $attrs =  wp_get_post_terms($id, "nft-tag");
        if (is_array($attrs)) {
            foreach ($attrs as $attr) {
                $res["attributes"][] = [
                    "value" => $attr->name,
                    "link" => add_query_arg("id", $collection_id, get_term_link($attr))
                ];
            }
        }
    
        switch($media_type) {
            case "image":
                if (is_array($media_urls) and array_key_exists(0, $media_urls)) {
                    $res["image"] = $media_urls[0];
                }
                break;
            default:
                if ($thumb = get_the_post_thumbnail_url($id)) {
                    $res["image"] = $thumb;
                }
                if (is_array($media_urls)) {
                    foreach($media_urls as $i=>$url) {
                        if ($i) {
                            $ind = $i + 1;
                            $res["animation_url".$ind] = $url;
                        } else {
                            $res["animation_url"] = $url;
                        }
                    }
                } elseif ($media_urls) {
                    $res["animation_url"] = $media_urls;
                }
                break;
        }

        return $res;
    
    }

    static private function validateAddress($add, $len = 40) {
        return preg_match('/^(0x)?[0-9a-fA-F]{'.$len.'}$/i', $add);
    }

    public static function NFT1155Owners($param) {

        $nft_id = (int) $param['id'];
        $contract = $param["contract"];
        $network_decimal = (int) $param["net"];

        if (!$nft_id or !$contract or !$network_decimal) return new WP_REST_Response("Invalid parameters.", 200 );

        $network_hex = "0x" . dechex($network_decimal);

        $options = get_option('etherscan_api_key_option');
        $nft_moralis_key = 
            (WPSC_helpers::valArrElement($options, "nft_moralis_key") and !empty($options["nft_moralis_key"]))?
                $options["nft_moralis_key"]:
                false;
        if (!$nft_moralis_key) return new WP_REST_Response(
            __("Invalid Moralis API key. Are you the system admin? Please setup the Moralis API keys in your WP Smart Contracts settings.", "wp-smart-contracts")
        , 200 );

        $transient_id = "moralis_owners_".$contract."_".$nft_id."_".$network_hex;

        if ($response = self::getGenericTransientResponse($transient_id)) {
            return new WP_REST_Response($response, 200 );
        }

        $url_request = 'https://deep-index.moralis.io/api/v2/nft/' . $contract . '/' . $nft_id . '/owners?chain='.$network_hex.'&format=decimal&media_items=false';
        $requests_response = \WpOrg\Requests\Requests::get( 
            $url_request, 
            ['accept' => 'application/json', 'X-API-Key' => $nft_moralis_key], 
            [],
            'GET'
        );

        if ($requests_response->status_code == "200") {
            $result = json_decode($requests_response->body)->result;
            self::saveGenericTransientResponse($transient_id, $result);
            return new WP_REST_Response($result, 200 );
        } else {
            return new WP_REST_Response(__("An error occurred: ", "wp-smart-contracts") . json_decode($requests_response->body, true)["message"], 200 );    
        }

    }

    public static function NFT1155Sync($param) {

        $contract = $param["contract"];
        $network_decimal = (int) $param["net"];

        if (!$contract or !$network_decimal) return new WP_REST_Response("Invalid parameters.", 200 );

        $network_hex = "0x" . dechex($network_decimal);

        $options = get_option('etherscan_api_key_option');
        $nft_moralis_key = 
            (WPSC_helpers::valArrElement($options, "nft_moralis_key") and !empty($options["nft_moralis_key"]))?
                $options["nft_moralis_key"]:
                false;
        if (!$nft_moralis_key) return new WP_REST_Response(
            __("Invalid Moralis API key. Are you the system admin? Please setup the Moralis API keys in your WP Smart Contracts settings.", "wp-smart-contracts")
        , 200 );

        $curl = new Wp_Http_Curl();
        @$result = $curl->request(
            'https://deep-index.moralis.io/api/v2/nft/' . $contract . '/sync?chain=' . $network_hex, 
            [
                'method' => 'PUT',
                'headers' => [
                    'accept' => 'application/json',
                    'X-API-Key' => $nft_moralis_key
                ]
            ]
        );

        return new WP_REST_Response($result, 200 );

    }

    public static function NFT1155MyItems($param) {

        $address = $param['address'];
        $contract = $param["contract"];
        $network_decimal = (int) $param["net"];

        if (!$address or !$contract or !$network_decimal) return new WP_REST_Response(__("Invalid parameters.", "wp-smart-contracts"), 200 );

        $network_hex = "0x" . dechex($network_decimal);

        $options = get_option('etherscan_api_key_option');
        $nft_moralis_key = 
            (WPSC_helpers::valArrElement($options, "nft_moralis_key") and !empty($options["nft_moralis_key"]))?
                $options["nft_moralis_key"]:
                false;

        if (!$nft_moralis_key) return new WP_REST_Response(
            __("Invalid Moralis API key. Are you the system admin? Please setup the Moralis API keys in your WP Smart Contracts settings.", "wp-smart-contracts")
        , 200 );

        $transient_id = "moralis_my_".$contract."_".$network_hex;

        if (!$response = self::getGenericTransientResponse($transient_id)) {

            $url_request = 'https://deep-index.moralis.io/api/v2/nft/' . $contract . '/owners?chain='.$network_hex.'&format=decimal&media_items=false';
            $requests_response = \WpOrg\Requests\Requests::get( 
                $url_request, 
                ['accept' => 'application/json', 'X-API-Key' => $nft_moralis_key], 
                [],
                'GET'
            );
    
            if ($requests_response->status_code == "200") {
                $response = json_decode($requests_response->body)->result;
                self::saveGenericTransientResponse($transient_id, $response);
            } else {
                return new WP_REST_Response(__("An error occurred: ", "wp-smart-contracts") . json_decode($requests_response->body, true)["message"], 200 );    
            }

        }

        if ($response and is_array($response)) {
            $ids = [];
            foreach ($response as $r) {
                if ($r->owner_of==$address) {
                    $ids[] = $r->token_id;
                }
            }
            return new WP_REST_Response($ids, 200);            
        } else {
            return new WP_REST_Response(__("An error occurred", "wp-smart-contracts"), 200 );
        }

    }

    public static function updateNFT1155($param) {

        @$author_id = (int) $param['author'];

        if ($author_id) {
            $user = get_user_by("id", $author_id);
        } else {
            $user = wp_get_current_user();
            $author_id = $user->ID;
        }

        $nickname = get_the_author_meta("display_name", $user->ID );

        $collection_id = (int) $param['collection_id'];
        $contract = $param["contract"];
        $tx = $param["tx"];
        $net_ver = $param["net_ver"];
        $account = $param["account"];
        $blockie_account = $param["blockie_account"];

        $nft_ids = $param["nft_ids"];
        $post_ids = $param["post_ids"];
        $amounts = $param["amounts"];

        $errors = false;

        foreach($post_ids as $index => $post) {

            $post_id = (int) $post;

            if ($error = WPSC_Shortcodes::validateNFTFE($collection_id, $post_id)) {
                $errors[] = $error;
            } else {

                $token_id = $nft_ids[$index]["id"];
                $blockie_id = $nft_ids[$index]["blockie"];
                $supply = $amounts[$index];
        
                // update contract and tx data
                update_post_meta($post_id, "wpsc_collection_contract", $contract);
                update_post_meta($post_id, "wpsc_txid", $tx);
                update_post_meta($post_id, "wpsc_nft_id", $token_id);
                update_post_meta($post_id, "wpsc_creator", $account);
                update_post_meta($post_id, "wpsc_network", $net_ver);
                update_post_meta($post_id, "wpsc_nft_url", get_rest_url(null, "wpsc/v1/nft1155/" . $collection_id . "/" . $token_id));
                update_post_meta($post_id, "wpsc_creator_blockie", $blockie_account);
                update_post_meta($post_id, "wpsc_nft_id_blockie", $blockie_id);

                // publish the post
                wp_update_post(['ID' => $post_id, 'post_status' => 'publish']);

            }
    
        }
        
        return new WP_REST_Response($errors, 200);

    }

    private static function doCurl($network, $body, $flags=false) {

        $ch = curl_init();

        $rpc_url = WPSC_assets::getRPC($network);
        if (!$rpc_url) return false;

        curl_setopt($ch, CURLOPT_URL, $rpc_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false; // 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if ($flags) {
            return [
                "rpc_url" => $rpc_url,
                "headers" => $headers,
                "body" => $body,
                "result" => json_decode($result, true)
            ];
        } else {
            return json_decode($result, true);            
        }

    }

    private static function getTopicHash($flavor) {

        $arr = [
            "vanilla" => '"0x2cda192087b8fe36df3af7e18c85055610634f5e41caa8634a261f59d3a437cb"',
            "pistachio" => '"0x6837ff1e738d95fc8bb5f12ce1513f42866f6c59c226c77342c4f36a1958ea10"',
            "chocolate" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "macadamia" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "ube" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "almond" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "mochi" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "matcha" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "suika" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "yuzu" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "azuki" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "ikasumi" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"',
            "bubblegum" => '"0x387ea218537e939551af33bbc2dd6c53b1fee55d377a0dce288258f972cb3a9c"'
        ];

        if (isset($arr[$flavor])) {
            return $arr[$flavor];
        }

        return false;
    
    }

    public static function checkDataOnChainUpdate($flavor, $block, $network, $txid, $account, $contract) {

        // check that the tx match the account
        $res = self::doCurl($network, "{\"jsonrpc\":\"2.0\",\"method\":\"eth_getTransactionByHash\",\"params\":[\"" .$txid. "\"],\"id\":1}");

        // check the account is from user than ran the tx
        if (!$res or !WPSC_helpers::valArrElement($res, "result") or !WPSC_helpers::valArrElement($res["result"], "from") or strtolower($res["result"]["from"])!=strtolower($account)) {
            return false;
        }

        // check that the contract was called in the txid
        if (!WPSC_helpers::valArrElement($res["result"], "to") or strtolower($res["result"]["to"])!=strtolower($contract)) {
            return false;
        }

        // check whitelisted updateAdmin functions
        $hashFunction = substr($res["result"]["input"], 0, 10);

        if (in_array($hashFunction, ["0x0948e846", "0x263f5877", "0x8a2937b0", "0xfa184c49", "0x14145b48", "0xd3ae9fa0", "0x745e96d7", "0x9fa6b40c", "0x61a09c97"])===false) return false;

        return true;

    }

    public static function checkDataOnChain($flavor, $block, $network, $txid, $account, $contract, $get_uri = false) {

        // check that the tx match the account
        $res = self::doCurl($network, "{\"jsonrpc\":\"2.0\",\"method\":\"eth_getTransactionByHash\",\"params\":[\"" .$txid. "\"],\"id\":1}");
        
        if ($get_uri) {
            if (isset($res["result"]["input"])) {
                $string = hex2str($res["result"]["input"]);
                if ($string) {
                    $string = substr($string, strpos($string, "http"));
                    if ($string) {
                        $string = substr($string, 0, strpos($string, "{id}"));
                        if ($string) {
                            $a = explode("/", $string);
                            if (isset($a[sizeof($a)-2])) {
                                return $a[sizeof($a)-2];
                            }
                        }
                    }
                }
            }
            return false;
        }
        
        if (!$res or !WPSC_helpers::valArrElement($res, "result") or !WPSC_helpers::valArrElement($res["result"], "from") or strtolower($res["result"]["from"])!=strtolower($account)) {
            return false;
        }

        $res = self::doCurl(
            $network, 
            "{\"jsonrpc\":\"2.0\",\"method\":\"eth_getLogs\",\"params\":[{\"blockHash\": \"".$block."\", " . "\"topics\": [" . self::getTopicHash($flavor) . "]}],\"id\":1}"
        );

        // check that the contract exists in the tx log
        $contract_found = false;
        // search contract in the first events
        for($i=0; $i<20; $i++) {
            if (isset($res["result"][$i]["data"]) and strtolower($contract) == strtolower("0x" . substr($res["result"][$i]["data"], 26, 40))) {
                $contract_found = true;
                break;
            }     
        }
        if (!$contract_found) return false;

        // check that the contract was generated and by a valid factory
	    if (isset($res["result"][0]["address"])) {
            if ($network_names = WPSC_helpers::getNetworks()) {
                if (isset($network_names[$network]["name"])) {
                    if ($network_name = $network_names[$network]["name"]) {
                        if (file_exists($json_file = dirname(dirname(__FILE__)).'/assets/json/factories.json')) {
                            if (is_array( $factories = json_decode( file_get_contents($json_file), true ) )) {
                                if (isset($factories[$flavor]["networks"][$network_name]["factory"])) {
                                    if (strtolower($factories[$flavor]["networks"][$network_name]["factory"]) == strtolower($res["result"][$i]["address"])) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return false;

    }

    // reserve a post id for nft collections
    public static function reservePostID() {
        $arr = [
            'post_name' => "Reserved NFT Collection",
            'post_title' => "Reserved NFT Collection",
            'post_status' => "draft",
            'post_type' => "nft-collection"
        ];
        $post_id = wp_insert_post($arr);
        if(!is_wp_error($post_id)) {
            // trash it
            wp_trash_post($post_id);
            return new WP_REST_Response($post_id, 200 );
        } else {
            return new WP_REST_Response($post_id->get_error_message(), 200 );
        }
    
    }
    
    private static function validNFTSignature($sign, $collection_id, $the_id) {

        // check the parameters
        $collection_id = (int) $collection_id;
        $the_id = (int) $the_id;
        if (!$collection_id or !$the_id or !$sign) return false;

        // get the creator of the account
        $creator = get_post_meta($the_id, "wpsc_creator", true);

        // check the signature 
        $msg = "Change NFT Item data. ID: " . $the_id . ". Collection ID: " . $collection_id;
        @$verified = Signature::verify( $msg, $sign, $creator );
        if (!$verified) return false;

        return true;

    }
    
    private static function validSignature($param) {

        // check the parameters
        $sign = sanitize_text_field($param["sign"]);
        $id = (int) sanitize_text_field($param["id"]);
        $account = sanitize_text_field($param["account"]);
        $flavor = sanitize_text_field($param["flavor"]);

        if (!$id or !$sign or !$account or !$flavor) return false;

        // check that the post correspond to the user / account
        $owner = get_post_meta($id, "wpsc_owner", true);
        if ($owner!=$account) return false;

        if (get_post_meta($id, "wpsc_flavor", true) != $flavor) return false;

        // check the signature 
        $msg = "Change post. Flavor: " . $flavor . ". ID: " . $id . ". Account: " . $account;
        @$verified = Signature::verify( $msg, $sign, $account );
        if (!$verified) return false;

        return $id;

    }
        
    public static function updatePostName($param) {
        
        $id = self::validSignature($param);
        if (!$id) return false;

        // do the update
        $name = sanitize_text_field($param["name"]);
        $res = wp_update_post( ['ID' => $id, 'post_title' => $name] );
        if (is_wp_error($res)) {
            return false;
        }

        // return the url
        return get_permalink($id);
    
    }

    public static function updatePostMetaOnChain($param) {

        $flavor = sanitize_text_field($param["flavor"]);
        $network = sanitize_text_field($param["network"]);
        $block = sanitize_text_field($param["block"]);
        $txid = sanitize_text_field($param["txid"]);
        $account = sanitize_text_field($param["account"]);
        $contract = sanitize_text_field($param["contract"]);

        $res = self::checkDataOnChainUpdate($flavor, $block, $network, $txid, $account, $contract);

        if ($res!==true) return new WP_REST_Response($res, 401);

        // get ID by contract address
        $id = WPSC_Queries::getIDByContract($contract, $network);

        if (!$id) {
            return new WP_REST_Response(__("Post ID not found", "wp-smart-contracts"), 401);
        }

        // do the update
        if (isset($param["params"]) and is_array($param["params"])) {
            foreach ($param["params"] as $key => $value) {
                $key = sanitize_text_field($key);
                $value = sanitize_text_field($value);
                if (substr($key, 0, 5)=="wpsc_") {
                    update_post_meta($id, $key, $value);
                }
            }
        }

        if ($id) {
            // return the url
            return get_permalink($id);
        }

        return false;

    }

    public static function updatePostMeta($param) {
        $id = self::validSignature($param);
        if (!$id) return false;
        // do the update
        if (isset($param["params"]) and is_array($param["params"])) {
            foreach ($param["params"] as $key => $value) {
                $key = sanitize_text_field($key);
                $value = sanitize_text_field($value);
                if (substr($key, 0, 5)=="wpsc_") {
                    update_post_meta($id, $key, $value);
                }
            }
        }
        // return the url
        return get_permalink($id);
    }

    // endpoint callback to create NFT on the interface
    public static function wizardAddContract($param) {

        $flavor = sanitize_text_field($param["flavor"]);
        $name = sanitize_text_field($param["name"]);
        $symbol = sanitize_text_field($param["symbol"]);
        $decimals = sanitize_text_field($param["decimals"]);
        $supply = sanitize_text_field($param["supply"]);
        $network = sanitize_text_field($param["network"]);
        $block = sanitize_text_field($param["block"]);
        $txid = sanitize_text_field($param["txid"]);
        $account = sanitize_text_field($param["account"]);
        $contract = sanitize_text_field($param["contract"]);

        $blockieOwner = sanitize_text_field($param["blockieOwner"]);
        $blockieContract = sanitize_text_field($param["blockieContract"]);
        $qrCode = sanitize_text_field($param["qrCode"]);

        $factory = "";

        // take factory and contract data from backend
        if (file_exists($json_factories_filename = dirname(dirname(__FILE__)).'/assets/json/factories.json') and 
            is_array( $json_factories = json_decode( file_get_contents($json_factories_filename), true ) ) and
            file_exists($json_factories_backend_filename = dirname(dirname(__FILE__)).'/assets/json/factories-backend.json') and
            is_array( $json_factories_backend = json_decode( file_get_contents($json_factories_backend_filename), true ) ) and
            $networks_json = WPSC_helpers::getNetworks() and
            isset($networks_json[$network]["name"]) and
            isset($json_factories[$flavor]["networks"][$networks_json[$network]["name"]]["version"]) and
            $version = $json_factories[$flavor]["networks"][$networks_json[$network]["name"]]["version"] and
            isset($json_factories[$flavor]["data"][$version]) and
            isset($json_factories_backend[$flavor]["data"][$version])
        ) {
            $abi = $json_factories[$flavor]["data"][$version];
            $data = $json_factories_backend[$flavor]["data"][$version];
            $factory = array_merge($abi, $data);
            $factory_json = addslashes(json_encode($factory));
        }
        
        $res = self::checkDataOnChain($flavor, $block, $network, $txid, $account, $contract);

        if ($res!==true) return new WP_REST_Response($res, 401);

        $id = false;

        if ($flavor=="vanilla" or $flavor=="pistachio" or $flavor=="chocolate" or $flavor=="macadamia") {
            $post_type = "coin";
        } else if ($flavor=="mango") {
            $post_type = "crowdfunding";
        } else if ($flavor=="ube" or $flavor=="almond") {
            $post_type = "staking";
        } else if ($flavor=="bubblegum") {
            $post_type = "ico";
        } else if ($flavor=="mochi" or $flavor=="matcha" or $flavor=="suika" or $flavor=="yuzu" or $flavor=="azuki" or $flavor=="ikasumi") {
            $id = (int) sanitize_text_field($param["id"]);
            $post_type = "nft-collection";
        }

        if ($id) {
            $post_id = $id;
            // check that the reservation is valid
            if (get_post_type($post_id) != "nft-collection" or get_post_status($post_id) != "trash") return new WP_REST_Response("Error", 401);
            $arr = [
                'ID' => $post_id,
                'post_name' => $contract,
                'post_title' => $name,
                'post_status' => "publish",
                'post_type' => $post_type
            ];
            $post_id = wp_update_post($arr);
        } else {
            $arr = [
                'post_name' => $contract,
                'post_title' => $name,
                'post_status' => "publish",
                'post_type' => $post_type
            ];
            $post_id = wp_insert_post($arr);    
        }

        if(!is_wp_error($post_id)) {

            update_post_meta($post_id, 'wpsc_flavor', $flavor);
            update_post_meta($post_id, 'wpsc_network', $network);
            update_post_meta($post_id, 'wpsc_txid', $txid);
            update_post_meta($post_id, 'wpsc_owner', $account);
            update_post_meta($post_id, 'wpsc_contract_address', $contract);
            update_post_meta($post_id, 'wpsc_factory', $factory_json);
            update_post_meta($post_id, 'wpsc_blockie', $blockieContract);
            update_post_meta($post_id, 'wpsc_blockie_owner', $blockieOwner);
            update_post_meta($post_id, 'wpsc_qr_code', $qrCode);

            if ($flavor=="vanilla" or $flavor=="pistachio" or $flavor=="chocolate" or $flavor=="macadamia") {
                update_post_meta($post_id, 'wpsc_coin_name', $name);
                update_post_meta($post_id, 'wpsc_coin_symbol', $symbol);
                update_post_meta($post_id, 'wpsc_coin_decimals', $decimals);
                update_post_meta($post_id, 'wpsc_total_supply', $supply);
            }

            if ($flavor == "chocolate") {
                $burnable = sanitize_text_field($param["burnable"]);
                $cap = sanitize_text_field($param["cap"]);
                if ($burnable) update_post_meta($post_id, 'wpsc_adv_burn', "burnable");
                update_post_meta($post_id, 'wpsc_adv_cap', $cap);
            }

            if ($flavor == "macadamia") {
                $fee = sanitize_text_field($param["fee"]);
                update_post_meta($post_id, 'wpsc_reflection_fee', $fee);
            }

            if ($flavor=="mango") {
                $minimum = sanitize_text_field($param["minimum"]);
                $approvers = sanitize_text_field($param["approvers"]);
                $name = sanitize_text_field($param["name"]);
                update_post_meta($post_id, 'wpsc_minimum', $minimum);
                update_post_meta($post_id, 'wpsc_approvers', $approvers);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="ube") {
                update_post_meta($post_id, "wpsc_token", sanitize_text_field($param["token"]));
                update_post_meta($post_id, "wpsc_apy", sanitize_text_field($param["apy"]));
                update_post_meta($post_id, "wpsc_mst", sanitize_text_field($param["mindays"]));
                update_post_meta($post_id, "wpsc_penalty", sanitize_text_field($param["penal"]));
                update_post_meta($post_id, "wpsc_minimum", sanitize_text_field($param["minamount"]));
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["token_name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_decimals", sanitize_text_field($param["decimals"]));
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="bubblegum") {
                update_post_meta($post_id, "wpsc_token", sanitize_text_field($param["token"]));
                update_post_meta($post_id, "wpsc_rate", sanitize_text_field($param["rate"]));
                update_post_meta($post_id, "wpsc_wallet", sanitize_text_field($param["wallet"]));
                update_post_meta($post_id, "wpsc_distribution_wallet", sanitize_text_field($param["wallet_distribution"]));
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["token_name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_decimals", sanitize_text_field($param["decimals"]));
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="almond") {
                update_post_meta($post_id, "wpsc_token", sanitize_text_field($param["token"]));
                update_post_meta($post_id, "wpsc_token2", sanitize_text_field($param["token2"]));
                update_post_meta($post_id, "wpsc_apy", sanitize_text_field($param["apy"]));
                update_post_meta($post_id, "wpsc_apy2", sanitize_text_field($param["apy2"]));
                update_post_meta($post_id, "wpsc_mst", sanitize_text_field($param["mindays"]));
                update_post_meta($post_id, "wpsc_penalty", sanitize_text_field($param["penal"]));
                update_post_meta($post_id, "wpsc_minimum", sanitize_text_field($param["minamount"]));
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["token_name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_decimals", sanitize_text_field($param["decimals"]));
                update_post_meta($post_id, "wpsc_name2", sanitize_text_field($param["token_name2"]));
                update_post_meta($post_id, "wpsc_symbol2", sanitize_text_field($param["symbol2"]));
                update_post_meta($post_id, "wpsc_decimals2", sanitize_text_field($param["decimals2"]));
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="mochi") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="matcha") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                update_post_meta($post_id, "wpsc_commission", sanitize_text_field($param["sales"]));
                update_post_meta($post_id, "wpsc_wallet", sanitize_text_field($param["wallet"]));
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="suika") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                update_post_meta($post_id, "wpsc_commission", sanitize_text_field($param["sales"]));
                update_post_meta($post_id, "wpsc_wallet", sanitize_text_field($param["wallet"]));
                update_post_meta($post_id, "wpsc_royalties", sanitize_text_field($param["royalty"]));
                update_post_meta($post_id, "wpsc_token", sanitize_text_field($param["payments"]));
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
            }

            if ($flavor=="yuzu") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
                // check if post id in URI differs from current post ID
                $res = self::checkDataOnChain($flavor, $block, $network, $txid, $account, $contract, true);
                if ($res and $res!=$post_id) {
                    update_post_meta($post_id, 'wpsc_nft_redirect_url_1155', $res);
                }
            }

            if ($flavor=="azuki") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                update_post_meta($post_id, "wpsc_commission", sanitize_text_field($param["sales"]));
                update_post_meta($post_id, "wpsc_wallet", sanitize_text_field($param["wallet"]));
                update_post_meta($post_id, "wpsc_royalties", sanitize_text_field($param["royalty"]));
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
                // check if post id in URI differs from current post ID
                $res = self::checkDataOnChain($flavor, $block, $network, $txid, $account, $contract, true);
                if ($res and $res!=$post_id) {
                    update_post_meta($post_id, 'wpsc_nft_redirect_url_1155', $res);
                }
            }

            if ($flavor=="ikasumi") {
                update_post_meta($post_id, "wpsc_name", sanitize_text_field($param["name"]));
                update_post_meta($post_id, "wpsc_symbol", sanitize_text_field($param["symbol"]));
                update_post_meta($post_id, "wpsc_anyone_can_mint", sanitize_text_field($param["anyone"]));
                update_post_meta($post_id, "wpsc_list_on_opensea", "on");
                update_post_meta($post_id, "wpsc_commission", sanitize_text_field($param["sales"]));
                update_post_meta($post_id, "wpsc_wallet", sanitize_text_field($param["wallet"]));
                update_post_meta($post_id, "wpsc_royalties", sanitize_text_field($param["royalty"]));
                update_post_meta($post_id, "wpsc_token", sanitize_text_field($param["payments"]));
                $pixel = sanitize_text_field($param["pixel"]);
                if (!$pixel or $pixel=="false") $pixel = "";
                else $pixel = "on";
                update_post_meta($post_id, "wpsc_pixelated_images", $pixel);
                wp_update_post(['ID' => $post_id, 'post_title' => $name]);
                // check if post id in URI differs from current post ID
                $res = self::checkDataOnChain($flavor, $block, $network, $txid, $account, $contract, true);
                if ($res and $res!=$post_id) {
                    update_post_meta($post_id, 'wpsc_nft_redirect_url_1155', $res);
                }
            }

            return new WP_REST_Response(get_permalink($post_id), 200 );
            
        } else {
            return new WP_REST_Response($post_id->get_error_message(), 200 );
        }
    
    }

    // endpoint callback to create NFT on the interface
    public static function nftInsert($param) {

        $handle = self::semaphoreWait(3);
        if ($handle===false) {
            return new WP_REST_Response([["message"=>"<span style=\"color: red\">" . __("An error occurred inserting posts", "wp-smart-contracts") . "</span>"]], 200 );
        }

        @$author_id = (int) $param['author'];

        if ($author_id) {
            $user = get_user_by("id", $author_id);
        } else {
            $user = wp_get_current_user();
            $author_id = $user->ID;
        }

        $nickname = get_the_author_meta("display_name", $author_id );

        // sanitize integer
        @$nft_id = (int) $param['nft_id'];

        $collection_id = (int) $param['collection_id'];

        if ($error = WPSC_Shortcodes::validateNFTFE($collection_id, $nft_id)) {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response($error , 200 );
        }

        // sanitize texts
        $title = sanitize_text_field($param['title']);
        if (!$title) {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response(__("Title is not valid", "wp-smart-contracts"), 200);
        }

        $collection_id = (int) $param['collection_id'];

        if ($error = WPSC_Shortcodes::validateNFTFE($collection_id, $nft_id)) {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response($error , 200 );
        }

        // sanitize texts
        $title = sanitize_text_field($param['title']);
        if (!$title) {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response(__("Title is not valid", "wp-smart-contracts"), 200);
        }

        @$tainted_custom_atts = $param['custom_atts'];
        @$tainted_custom_tax = $param['custom_tax'];
        @$tainted_custom_gals = $param['custom_gals'];

        @$owner = sanitize_text_field($param['owner']);

        @$owner = sanitize_text_field($param['owner']);
        @$skip_owner = sanitize_text_field($param['skip_owner']);
        if (!$skip_owner and !self::validateAddress($owner)) {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response(__("The recipient is not a valid address", "wp-smart-contracts"), 200);
        }

        @$supply = (int) sanitize_text_field($param['supply']);
        if (!$supply) $supply = 0;

        // if it is adding the NFT and is an ERC1155 then validate the qty
        if (!$nft_id) {
            $flavor = get_post_meta($collection_id, "wpsc_flavor", true);
            if (($flavor=="yuzu" or $flavor=="ikasumi") and !$supply) {
                self::SemaphoreSignal($handle);
                return new WP_REST_Response(__("Supply is not valid, please write a positive number", "wp-smart-contracts"), 200);
            }
        }
        
        // sanitize media json string
        $media = $param['media'];
        if (is_null(json_decode($media))) {
            $media = "";
        }
        $media_type = sanitize_text_field($param['media_type']);
        if (!in_array($media_type, ["image", "video", "audio", "document", "3dmodel"])) {
            $media_type="";
        }
        
        // sanitize textarea keeping the line breaks
        $description = $param['description'];
        if ($description) {
            $description = implode( "\n", array_map( 'sanitize_textarea_field', explode( "\n", $description ) ) );
        }
        
        // sanitize numeric arrays
        $tags = self::integrify($param['tags'], $tainted_custom_atts, "nft-tag");
        $categories = self::integrify($param['categories'], $tainted_custom_tax, "nft-taxonomy");

        $galleries=[];	
        $galleries_tainted = $param['galleries'];	
        // cycle $tainted_custom_gals and:	
        if (is_array($tainted_custom_gals) or is_array($galleries_tainted)) {	
            if (!WPSC_NFTGallery::db_check()) {	
                return new WP_REST_Response(__("An error occurred processing galleries.", "wp-smart-contracts") . " The table: " . WPSC_NFTGallery::db_table_name() . " could not be found on the database. Please check that the database user has \"CREATE TABLE\" permission.", 200 );	
            } else {	
                if (is_array($galleries_tainted)) {	
                    foreach($galleries_tainted as $gtaint) {	
                        if ($temp = (int) $gtaint) {	
                            $galleries[] = $temp;  	
                        }	
                    }	
                }	
    	
                if (is_array($tainted_custom_gals)) {	
                    foreach($tainted_custom_gals as $tainted_gal) {	
    	
                        // sanitize the string	
                        $gal = sanitize_text_field($tainted_gal);	
        	
                        // If the gallery exists with this name for the user: author_id	
                        $user_galleries = WPSC_NFTGallery::get($author_id, true);	
        	
                        $key_exists = false;	
                        if (is_array($user_galleries)) {	
                            $key_exists = array_search($gal, array_column($user_galleries, 'name'));	
                            if ($key_exists!==false) {	
                                // Add the term_id to the array galleries	
                                if (array_search($user_galleries[$key_exists]["term_id"], $galleries)===false) {	
                                    $galleries[] = $user_galleries[$key_exists]["term_id"];	
                                }	
                            }	
                        }	
        	
                        // If the gallery doesn't exists then 	
                        if ($key_exists===false) {	
        	
                            // Create the gallery for the user: author_id	
                            $new_term_id = WPSC_NFTGallery::createGalleryForUserID($author_id, $gal);	
                            if ($new_term_id) {	
                                // Add the term_id to the array galleries	
                                $galleries[] = $new_term_id;	
                            }	
        	
                        }	
        	
                    }	
    	
                }	
    	
            }	
        }	

        $status = 'publish';
        if ($param["draft"]) {
            $status = "draft";
        }

        $arr = [
            'ID' => $nft_id,
            'post_author' => $author_id,
            'post_content' => $description,
            'post_title' => $title,
            'post_status' => $status,
            'post_type' => 'nft'
        ];

        @$media_id = json_decode($media, true)[0]["id"];

        $the_id = $nft_id;

        if ($media_id) {
            $the_id = WPSC_Queries::getPostIDByMediaAndCollection($collection_id, $media_id);
        }

        if ($the_id) {
            // If the item is minted only original creator can modify the item
            $sign = sanitize_text_field($param["sign"]);
            if (self::validNFTSignature($sign, $collection_id, $the_id)) {
                wp_update_post([
                    'ID' => $the_id,
                    'post_title' => $title,
                    'post_content' => $description
                ]);    
            } else {
                return new WP_REST_Response(__("Not authorized", "wp-smart-contracts"), 401);
            }
        } else {
            // by default create the NFT from the front end in the trashcan, if they are validated onchain they will be alive
            $arr["post_status"] = "trash";
            $the_id = wp_insert_post($arr);
        }

        if ($the_id) {

            update_post_meta($the_id, "wpsc_nft_owner", $owner);
            update_post_meta($the_id, "wpsc_nft_supply", $supply);
            update_post_meta($the_id, "wpsc_item_collection", $collection_id);
            update_post_meta($the_id, "wpsc_nft_media_json", $media);
            update_post_meta($the_id, "original_author", $nickname);
            update_post_meta($the_id, "original_author_id", $author_id);
            update_post_meta($the_id, "wpsc_media_type", $media_type);
            
            if ($param["batch"]) {
                update_post_meta($the_id, "wpsc_is_batch", true);
            }
            
            wp_set_object_terms( $the_id, $galleries, 'nft-gallery', false );
            wp_set_object_terms( $the_id, $categories, 'nft-taxonomy', false );
            wp_set_object_terms( $the_id, $tags, 'nft-tag', false );

            // clear cache
            delete_transient("wpsc_nft_" . $nft_id);

            if ($param['log_id']) {
                if (self::updateTransientDataPost($param['log_id'], $param['log_index'], $the_id) === false) {
                    self::SemaphoreSignal($handle);
                    return new WP_REST_Response(__("An error occurred inserting the NFT", "wp-smart-contracts"), 200 );
                }
            }

            self::SemaphoreSignal($handle);
            return new WP_REST_Response($the_id, 200 );
        } else {
            self::SemaphoreSignal($handle);
            return new WP_REST_Response(__("An error occurred inserting the NFT", "wp-smart-contracts"), 200 );
        }

    }

    private static function upload($file) {

        $filename = basename($file);

        $content = false;
        if (substr(strtolower($file), 0, 4) == "http") {
            $file = str_replace('\\/', '/', $file);
            $response = wp_remote_get( $file ); 
            if ( is_array( $response ) && ! is_wp_error( $response ) && wp_remote_retrieve_response_code($response) == "200") {
                $content = $response['body'];
            }
        } else {
            $content = file_get_contents($file);
        }

        if ($content) {

            if ($id = WPSC_Queries::getMediaByHash($content)) {
                return $id;
            }

            $upload_file = wp_upload_bits($filename, null, $content);
            if (!$upload_file['error']) {
                $wp_filetype = wp_check_filetype($filename, null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                    update_post_meta($attachment_id, "wpsc_hash", md5($content));
                }
                return $attachment_id;
            }
    
        }

        return false;

    }

    private static function appendTransient($unique_id, $message) {
        set_transient($unique_id, get_transient($unique_id) . $message . "<br/>", 24*60*60);
    }

    private static function updateTransientData($unique_id, $data_index, $media_id, $media_url, $media_mime) {        
        $transient_id = $unique_id . "_data";
        if ($data = get_transient($transient_id) and is_array($data)) {
            $data[$data_index]["media_id"] = $media_id;
            $data[$data_index]["media_url"] = $media_url;
            $data[$data_index]["media_mime"] = $media_mime;
            set_transient($transient_id, $data, 24*60*60);
        }
    }

    private static function semaphoreGetFilename($id) {
        $upload_dir = wp_upload_dir();
        return $upload_dir['basedir'] . '/wpsc-semaphore-'.$id.'.txt';
    }

    private static function semaphoreInit($id) {
        $file = self::semaphoreGetFilename($id);
        if (!file_exists($file)) {
            if (file_put_contents($file, "semaphore")===false) return false;
        }
        return file_exists($file);
    }

    private static function semaphoreWait($id) {

        if (!self::semaphoreInit($id)) {
            return false;
        }
        
        $filename = self::semaphoreGetFilename($id);
        
        $handle = fopen($filename, 'w');
        
        if ($handle===false) {
            return false;
        }
        
        $attemp = 0;
        
        while (!flock($handle, LOCK_EX)) {
            usleep(500);
            $attemp++;
            if ($attemp==40) {
                return false;
            }
        }
        
        return $handle;

    }
    
    private static function SemaphoreSignal($handle) {
        fclose($handle);
    }

    private static function updateTransientDataPost($unique_id, $data_index, $post_id) {
        
        $handle = self::semaphoreWait(2);
        if ($handle===false) return false;

        $transient_id = $unique_id . "_data";
        if ($data = get_transient($transient_id) and is_array($data)) {
            $data[$data_index]["post_id"] = $post_id;
            set_transient($transient_id, $data, 24*60*60);
        }

        self::SemaphoreSignal($handle);

    }

    private static function returnMediaData($id, $media, $unique_id, $message, $index, $ipfs=false) {

        $ret_message = '';
        if (is_numeric($id)) {
            if ($url = wp_get_attachment_url($id)) {
                if (!empty($url)) {

                    if ($ipfs) {
                        $res = self::nftIPFSStore(["attachment_id"=>$id]);
                        if (is_object($res) and property_exists($res, "data")) {
                            if (substr($res->data, 0, 8) == "SUCCESS,") {
                                $url = substr($res->data, 8);
                            } elseif (substr($res->data, 0, 8) == "https://") {
                                $url = $res->data;
                            }
                        }
                    }

                    // get id, url and mime type
                    $mime = get_post_mime_type($id);

                    if ($unique_id) {
                        $ret_message = "Media: " . $media . " ($id) " . $message . " " . $url;
                        self::appendTransient($unique_id, $ret_message);
                        self::updateTransientData($unique_id, $index, $id, $url, $mime);
                    }

                    return [["id"=>$id, "url"=>$url, "mime"=>$mime, "message"=>$ret_message]];
                }
            }
        }

        $message = "<span style=\"color: red\">" . __("An error occurred processing media file: ", "wp-smart-contracts") . $media . "</span>";
        self::appendTransient($unique_id, $message);
        return [["message"=>$message]];

    }

    public static function retrieveMedia($param) {
        $unique_id = $param["log_id"];
        if (substr($unique_id, 0, 5)=="wpsc_") {
            return new WP_REST_Response(get_transient($unique_id), 200 );
        }
    }

    public static function saveMedia($param) {

        $handle = self::semaphoreWait(1);
        if ($handle===false) {
            return new WP_REST_Response([["message"=>"<span style=\"color: red\">" . __("An error occurred processing media file: ", "wp-smart-contracts") . $media . "</span>"]], 200 );
        }

        $media = $param["media"];
        $unique_id = $param["log_id"];
        $index = $param["index"];
        $ipfs = $param["ipfs"];
        $num = intval($media);
        if ($num>0) {
            // is a media ID, find the URL
            return new WP_REST_Response(
                self::returnMediaData($num, $media, $unique_id, __("Image ID processed", "wp-smart-contracts"), $index, $ipfs), 
            200);
        } elseif (substr(strtolower($media), 0, 4) == "http") {
            // is an URL
            if ($ret_id = self::upload($media)) {
                return new WP_REST_Response(
                    self::returnMediaData($ret_id, $media, $unique_id, __("URL downloaded", "wp-smart-contracts"), $index, $ipfs), 
                200);    
            }
        } elseif (preg_match('/^[\/\w\-. ]+$/', $media)) {
            // is a media in /uploads/wpsc ?
            $filename = wp_upload_dir()["basedir"] . '/wpsc/' . $media;
            if (file_exists($filename)) {
                if ($id = self::upload($filename)) {
                    return new WP_REST_Response(
                        self::returnMediaData($id, $media, $unique_id, __("Local file processed", "wp-smart-contracts"), $index, $ipfs),
                    200);    
                }
            }
        }
        if ($unique_id) {
            self::appendTransient($unique_id, "<span style=\"color: red\">" . __("An error occurred processing media file: ", "wp-smart-contracts") . $media . "</span>");
        }

        self::SemaphoreSignal($handle);

        return new WP_REST_Response([["message"=>"<span style=\"color: red\">" . __("An error occurred processing media file: ", "wp-smart-contracts") . $media . "</span>"]], 200 );
    }

    public static function nftLog($param) {

        $post_id = (int) $param['id'];
        $txid = sanitize_text_field($param['txid']);
        $to = sanitize_text_field($param['to']);
        if (is_numeric($param['value'])) {
            $value = $param['value'];
        } else {
            $value = 0;
        }
        $date = date("Y-m-d H:i:s");
        if (!$post_id or !$value) return new WP_REST_Response("Invalid values" , 200 );
        if (!self::validateAddress($to) or !self::validateAddress($txid, 64)) return new WP_REST_Response("Invalid data" , 200 );
        if (get_post_type($post_id)!="nft") return new WP_REST_Response("Invalid post" , 200 );

        $log_history = json_decode(get_post_meta($post_id, 'wpsc_log_history', true), true);
        if (empty($log_history)) {
            $log_history = [];
        }
        $log_history[] = ["txid"=>$txid, "to"=>$to, "value"=>$value, "date"=>$date];
        update_post_meta($post_id, "wpsc_log_history", json_encode($log_history));

        return json_encode("done");

    }
    
    public static function isMinted($param) {

        $user = wp_get_current_user();

        // sanitize integer
        $nft_id = (int) $param['id'];

        $nft_id_check = (int) get_post_meta($nft_id, 'wpsc_nft_id', true);

        if ($nft_id_check>0) {
            return new WP_REST_Response("true" , 200 );
        } else {
            return new WP_REST_Response("false" , 200 );
        }

    }

    public static function nftGetByIDs($param) {

        // sanitize integer
        $collection_id = (int) $param['collid'];
        $network = (int) $param['network'];
        
        $contract = get_post_meta($collection_id, 'wpsc_contract_address', true);

        if (!$contract) return "Collection is not deployed";
        if (!$collection_id) return "Invalid collection ID";
		if (get_post_type($collection_id)!="nft-collection") return "Invalid collection ID";

        $arr_unsanitized = explode("-", $param['ids']);

        $nfts = '';

        if (is_array($arr_unsanitized)) {
            foreach($arr_unsanitized as $elem_unsanitized) {
                // consider the case of unminted items, elem is: p[id]
                if (substr($elem_unsanitized, 0, 1)=="p" and is_numeric(substr($elem_unsanitized, 1))) {
                    $elem = $elem_unsanitized;
                } else {
                    $elem = (int) $elem_unsanitized;
                }
                if ($elem) {
                    if ($nfts) $nfts .= ",";
                    $nfts .= $elem;
                }
            }
        }

        if (!empty($nfts) and !empty($contract)) {

            $transient_id = $contract."_".$nfts;
            if ($response = self::getGenericTransientResponse($transient_id)) return new WP_REST_Response($response, 200 );
            
            if ($response = WPSC_Queries::getNFTsByID($nfts, $contract, $network)) {
                $token_uris = [];
                if (is_array($response)) {
                    foreach($response as $p) {
                        $token_uris[] = [
                            "nft" => self::nft(["id"=>$p["ID"]]),
                            "json" => get_post_meta($p["ID"], 'wpsc_nft_media_json', true),
                            "media_type" => get_post_meta($p["ID"], 'wpsc_media_type', true),
                            "ID" => $p["ID"]
                        ];
                    }
                }
                self::saveGenericTransientResponse($transient_id, $token_uris);
                return new WP_REST_Response($token_uris, 200);
            }

        }

        return new WP_REST_Response([], 200 );

    }

    public static function nftExistsIPFS($param) {
        
        // get attachment ID
        $attachment_id = (int) $param['id'];
        if (!$attachment_id or get_post_type($attachment_id)!="attachment") return new WP_REST_Response(false, 200 );

        // verify if the attachment was deployed already
        $wpsc_nft_ipfs = get_post_meta($attachment_id, 'wpsc_nft_ipfs', true);

        if ($wpsc_nft_ipfs) return new WP_REST_Response(true , 200 );

        return new WP_REST_Response(false , 200 );

    }

    public static function nftIPFSStore($param) {
        
        // get attachment ID
        $attachment_id = (int) $param['attachment_id'];
        if (!$attachment_id or get_post_type($attachment_id)!="attachment") return new WP_REST_Response("Invalid attachment", 200 );

        // verify if the attachment was deployed already
        $wpsc_nft_ipfs = get_post_meta($attachment_id, 'wpsc_nft_ipfs', true);
        if ($wpsc_nft_ipfs) return new WP_REST_Response($wpsc_nft_ipfs , 200 );

        // get nft storage api key
        $options = get_option('etherscan_api_key_option');
        $nft_storage_key = (WPSC_helpers::valArrElement($options, "nft_storage_key") and !empty($options["nft_storage_key"]))?$options["nft_storage_key"]:false;
        if (!$nft_storage_key) return new WP_REST_Response(
            __("Invalid NFT storage key. Are you the system admin? Please setup the nft.storage keys in your WP Smart Contracts settings.", "wp-smart-contracts")
        , 200 );

        // get file content
        $file = file_get_contents( get_attached_file( $attachment_id ) );
        if (!$file) return new WP_REST_Response("Invalid attachment", 200 );

        // call the NFT Storage endpoint
        $api = new \RestClient( [ 'base_url' => 'https://api.nft.storage' ] );
        $result = $api->post( '/upload', $file, ['Authorization' => 'Bearer ' . $nft_storage_key] );

        if ( 200 === $result->info->http_code ) {
            $wpsc_nft_ipfs = "https://ipfs.io/ipfs/" . $result->decode_response()->value->cid;

            // store this on the attachment post
            update_post_meta($attachment_id, 'wpsc_nft_ipfs', $wpsc_nft_ipfs);

            // clear transient cache for NFT endpoints
            WPSC_Queries::clearNFTTokenURI();

            return new WP_REST_Response("SUCCESS,".$wpsc_nft_ipfs, 200 );
        } else {
            return new WP_REST_Response($result->error, 200 );
        }

    }
    
    // endpoint callback to create NFT on the interface
    public static function nftSaveDeploy($param) {

        // on-chain verification
        $flavor = sanitize_text_field($param["flavor"]);
        $network = sanitize_text_field($param["network"]);
        $block = sanitize_text_field($param["block"]);
        $txid = sanitize_text_field($param["txid"]);
        $account = sanitize_text_field($param["account"]);
        $contract = sanitize_text_field($param["contract"]);

        $res = self::checkDataOnChainUpdate($flavor, $block, $network, $txid, $account, $contract);

        if ($res!==true) return new WP_REST_Response($res, 401);

        // sanitize integer
        $nft_id = (int) $param['nft_id'];

        $sign = $param['wpsc-nft-sign'];
        
        $collection_id = (int) $param['wpsc-item-collection'];

        // lazy minting validation
        if ($sign) {
            if (!WPSC_Queries::validateLazyMinting($nft_id, $sign)) {
                return new WP_REST_Response(__("Not valid signature found", "wp-smart-contracts"), 200 );
            }
        // regular minting validation
        } else {
            if ($error = WPSC_Shortcodes::validateNFTFE($collection_id, $nft_id)) {
                return new WP_REST_Response($error , 200 );
            }    
        }

        $nft_id_check = (int) get_post_meta($nft_id, 'wpsc_nft_id', true);

        if ($nft_id_check>0) return new WP_REST_Response(__("Item was already minted", "wp-smart-contracts"), 200 );

        $wpsc_collection_contract = WPSC_Metabox::cleanUpText($param["wpsc-collection-contract"]);
        update_post_meta($nft_id, 'wpsc_collection_contract', $wpsc_collection_contract);
    
        $wpsc_item_collection = WPSC_Metabox::cleanUpText($param["wpsc-item-collection"]);
        update_post_meta($nft_id, 'wpsc_item_collection', $wpsc_item_collection);
    
        $wpsc_network = WPSC_Metabox::cleanUpText($param["wpsc-network"]);
        update_post_meta($nft_id, 'wpsc_network', $wpsc_network);
    
        $wpsc_txid = WPSC_Metabox::cleanUpText($param["wpsc-txid"]);
        update_post_meta($nft_id, 'wpsc_txid', $wpsc_txid);
    
        $wpsc_creator = WPSC_Metabox::cleanUpText($param["wpsc-creator"]);
        update_post_meta($nft_id, 'wpsc_creator', $wpsc_creator);
    
        $wpsc_creator_blockie = WPSC_Metabox::cleanUpText($param["wpsc-creator-blockie"]);
        update_post_meta($nft_id, 'wpsc_creator_blockie', $wpsc_creator_blockie);
    
        $wpsc_nft_id = WPSC_Metabox::cleanUpText($param["wpsc-nft-id"]);
        update_post_meta($nft_id, 'wpsc_nft_id', $wpsc_nft_id);
    
        $wpsc_nft_id_blockie = WPSC_Metabox::cleanUpText($param["wpsc-nft-id-blockie"]);
        update_post_meta($nft_id, 'wpsc_nft_id_blockie', $wpsc_nft_id_blockie);
    
        $wpsc_nft_url = WPSC_Metabox::cleanUpText($param["wpsc-nft-url"]);
        update_post_meta($nft_id, 'wpsc_nft_url', $wpsc_nft_url);

        $wpsc_nft_supply = WPSC_Metabox::cleanUpText($param["wpsc-nft-supply"]);
        update_post_meta($nft_id, 'wpsc_nft_supply', $wpsc_nft_supply);

        // clear endpoint cache
        $transient_name = "wpsc_nft_" . $nft_id;
        delete_transient($transient_name);

        // change post status to publish
        wp_update_post(['ID' => $nft_id, 'post_status' => 'publish']);

        return new WP_REST_Response(get_permalink($nft_id), 200 );

    }

    static public function getNetworkDomain($network, $prefix="api", $url_field="url") {
        $arr = WPSC_helpers::getNetworks();
        if (WPSC_helpers::valArrElement($arr, $network)) {
            if (strtolower(substr($arr[$network][$url_field], 0, 6))=="https:") {
                return $arr[$network][$url_field];
            } else {
                return "https:".$arr[$network][$url_field];
            }
        }
        return false;
    }

    // get txs stored in wp transient
    private static function getTransientResponse($network, $contract, $address, $page, $internal) {

        // cache is activated?
        if (!$expiration_time = WPSCSettingsPage::get('expiration_time')) {
            return false;
        }

        $transient_name = self::transientPrefix . $network . "_" . $contract . "_" . $address . "_" . $page . "_" . $internal;

        if ($t = get_transient($transient_name)) {
            return $t;
        } else {
            return false;
        }

    }

    // store txs to wp transient
    private static function saveTransientResponse($network, $contract, $address, $page, $internal, $txs) {

        // cache is activated?
        if (!$expiration_time = WPSCSettingsPage::get('expiration_time')) {
            return false;
        }

        $transient_name = self::transientPrefix . $network . "_" . $contract . "_" . $address . "_" . $page . "_" . $internal;

        set_transient($transient_name, $txs, $expiration_time);

    }

    private static function getGenericTransientResponse($id) {

        // cache is activated?
        if (!$expiration_time = WPSCSettingsPage::get('expiration_time')) {
            return false;
        }

        $transient_name = self::transientPrefix . $id;

        if ($t = get_transient($transient_name)) {
            return $t;
        } else {
            return false;
        }

    }

    // store txs to wp transient
    private static function saveGenericTransientResponse($id, $content) {

        // cache is activated?
        if (!$expiration_time = WPSCSettingsPage::get('expiration_time')) {
            return false;
        }

        $transient_name = self::transientPrefix . $id;

        set_transient($transient_name, $content, $expiration_time);

    }

    static public function integrify($arr, $tainted_custom_atts=false, $taxonomy="nft-tag") {
        
        if (!$tainted_custom_atts and !is_array($arr)) return [];

        $new = [];

        if ($tainted_custom_atts) {

            if (is_array($tainted_custom_atts)) {
                $arr_atts = $tainted_custom_atts;
            } else {
                $arr_atts = explode(",", $tainted_custom_atts);
            }
            if (is_array($arr_atts)) {

                foreach($arr_atts as $i => $att) {

                    $sanitized_att = sanitize_text_field($att);
                    $sanitized_att = trim($att);

                    if ($att and strlen($sanitized_att)<30) {
                        if ($res = term_exists($sanitized_att, $taxonomy)) {
                            if ($term_id = $res["term_id"] and array_search($term_id, $new)===false) {
                                $new[] = (int) $term_id;
                            }
                        } else {
                            $res = wp_insert_term($sanitized_att, $taxonomy);
                            if (!is_wp_error($res) and $term_id = $res["term_id"] and array_search($term_id, $new)===false) {
                                $new[] = (int) $term_id;
                            }
                        }
                    }
                    if ($i==19) {
                        break;
                    }
                }
                 
            }
        }

        if (is_array($arr)) {
            foreach($arr as $i) {
                $j = (int) $i;
                if ($j and array_search($j, $new)===false) {
                    $new[]=$j;
                }
            }    
        }

        return $new;

    }

    static public function clearCacheForNFTEndpoints() {
        global $wpdb;
        $wpdb->query("DELETE FROM `$wpdb->options` WHERE option_name LIKE '%_transient_" . WPSC_Endpoints::transientPrefix . "nft_%'");    
    }

}