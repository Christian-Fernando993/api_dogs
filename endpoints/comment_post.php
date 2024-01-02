<?php
    function api_comment_post($request) {
        $post_id = $request['id'];
        $user = wp_get_current_user();
        $user_id = $user->ID;

        if($user_id === 0 ) {
            $response = new WP_Error('error', 'Sem permissão.', ['status' => 401]);
            return rest_ensure_response($response);
        }
        $comment = sanitize_text_field($request['comment'] );
        $post_id = $request['id'];


        if(empty($comment)) {
            $response = new WP_Error('error', 'Dados Incompletos.', ['status' => 402]);
        }

        $response = [
            'comment_author' => $user->user_login,
            'comment_content' => $comment,
            'comment_post_ID' => $post_id,
            'user_id' => $user_id,
        ];
        
        $comment_id = wp_insert_comment($response);
        $comment = get_comment($comment_id);
        return rest_ensure_response('Comentario deletado');
    }

    function register_api_comment_post() {
        register_rest_route('api', '/comment/(?P<id>[0-9]+)', [
            'method' => WP_REST_Server::CREATABLE,
            'callback' => 'api_comment_post',
        ]);
    }
    add_action('resp_api_init', 'register_api_comment_post')    
?>