<?php

namespace BetterLinks\API;

use BetterLinks\Traits\ArgumentSchema;

class Settings extends Controller
{
    use ArgumentSchema;
    /**
     * Initialize hooks and option name
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        $endpoint = '/settings/';
        register_rest_route($this->namespace, $endpoint, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'args' => $this->get_settings_schema(),
            ],
        ]);

        register_rest_route($this->namespace, $endpoint, [
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_item'],
                'permission_callback' => [$this, 'permissions_check'],
                'args' => $this->get_settings_schema(),
            ],
        ]);
    }

    /**
     * Get betterlinks
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function get_items($request)
    {
        $response =  get_option(BETTERLINKS_LINKS_OPTION_NAME, '[]');
        return new \WP_REST_Response(
            [
                'success' => true,
                'data' => $response,
            ],
            200
        );
    }

    /**
     * Create OR Update betterlinks
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function create_item($request)
    {
        return new \WP_REST_Response(
            [
                'success' => true,
                'data' => [],
            ],
            200
        );
    }

    /**
     * Create OR Update betterlinks
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function update_item($request)
    {
        $response = $request->get_params();
        $response = \BetterLinks\Helper::sanitize_text_or_array_field($response);
        $response['uncloaked_categories'] = isset($response['uncloaked_categories']) && is_string($response['uncloaked_categories']) ? json_decode($response['uncloaked_categories']) : [];
        $response['affiliate_disclosure_text'] = isset($response['affiliate_disclosure_text']) && is_string($response['affiliate_disclosure_text']) ? $response['affiliate_disclosure_text'] : '';

        $enable_password_protection = !empty($response['enable_password_protection']) ? $response['enable_password_protection'] : false;

        if( class_exists('\BetterLinksPro\Helper')) {
            if( $enable_password_protection ){
                (new \BetterLinksPro\Helper)->add_password_protect_page();
            }else {
                (new \BetterLinksPro\Helper)->delete_password_protect_page();
            }
        }

        $response = json_encode($response);
        if ($response) {
            update_option(BETTERLINKS_LINKS_OPTION_NAME, $response);
        }
        // regenerate links for wildcards option update
        \BetterLinks\Helper::write_links_inside_json(); // it's better to write the links instantly here than scheduling/corning it
        return new \WP_REST_Response(
            [
                'success' => true,
                'data' => $response ? $response : [],
            ],
            200
        );
    }

    /**
     * Delete betterlinks
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete_item($request)
    {
        return new \WP_REST_Response(
            [
                'success' => true,
                'data' => [],
            ],
            200
        );
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        return apply_filters('betterlinks/api/settings_get_items_permissions_check', current_user_can('manage_options'));
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function permissions_check($request)
    {
        return apply_filters('betterlinks/api/settings_update_items_permissions_check', current_user_can('manage_options'));
    }
}
